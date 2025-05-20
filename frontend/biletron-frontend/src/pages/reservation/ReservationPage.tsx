import Header from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import {ChangeEvent, FormEvent, JSX, KeyboardEvent, useEffect, useState} from "react";
import styles from './ReservationPage.module.css';
import {useLocation, useNavigate} from "react-router";
import {Movie} from "../../types/Movie.ts";
import {getCookieURIEncodedJSONAsObject} from "../../utils/cookies.tsx";
import {AuthCookie, AuthCookieName} from "../../types/AuthCookie.ts";
import {logoutClient} from "../../services/ClientService.tsx";
import Messages from "../../components/messages/Messages.tsx";
import Poster from "../../components/poster/Poster.tsx";
import {Screening} from "../../types/Screening.tsx";
import {fetchScreeningsForMovie} from "../../services/ScreeningService.tsx";
import {formatDateTimeNoSubSecond} from "../../utils/dateTime.tsx";
import {Seat} from "../../types/Seat.tsx";
import {fetchSeatsForHalls} from "../../services/SeatService.tsx";
import {SeatType, SeatTypePartial} from "../../types/SeatType.tsx";
import {fetchSeatTypes} from "../../services/SeatTypeService.tsx";
import {addBulkReservation, fetchReservationsForScreening} from "../../services/ReservationService.tsx";
import {decimalToInt, IntToDecimal} from "../../utils/decimal.tsx";
import {fetchDiscount} from "../../services/DiscountService.tsx";


/** @example
 * interface SeatsMap {
 *   [hall_id: number]: {
 *       seats: Seat[];
 *       [screening_id: number]: Seat[];
 *   };
 * }
 */
interface SeatsMap {
    [hall_id: number]: {
        seats: Seat[];
        [screening_id: number]: Seat[];
    };
}

function ReservationPage() {
    const [email, setEmail] = useState<string>("Wyloguj");
    const [loading, setLoading] = useState(true);
    const location = useLocation();
    const navigate = useNavigate();
    const [movie, setMovie] = useState<Movie>();
    const [seatTypes, setSeatTypes] = useState<SeatType[]>([]);
    const [seats, setSeats] = useState<SeatsMap>({});
    const [screenings, setScreenings] = useState<Screening[]>([]);
    const [screening, setScreening] = useState<Screening>({} as Screening); // Initialize with an empty object to avoid undefined lint error

    const [summaryNetto, setSummaryNetto] = useState<number>(0);
    const [discountNetto, setDiscountNetto] = useState<number>(0);
    const [discountName, setDiscountName] = useState<string | null>(null);
    const [seatCounts, setSeatCounts] = useState<{ standard: number; premium: number; bed: number }>({
        standard: 0,
        premium: 0,
        bed: 0,
    });
    const [totalNetto, setTotalNetto] = useState<number>(0);

    const clearLocationState = () => {
        window.history.replaceState({}, '')
        window.removeEventListener("beforeunload", clearLocationState);
    }

    const initializeData = async () => {
        setLoading(true);


        const searchParams = new URLSearchParams(location.search);
        const idFromQuery = searchParams.get('id_movie');
        let movieResolved = location.state?.movie as Movie;

        try {
            if (movieResolved) {
                setMovie(movieResolved);
            } else if (idFromQuery) {
                const res = await fetch(`/api/movies/${idFromQuery}`);
                if (!res.ok) {
                    Messages.showMessage("Nie ma takiego filmu", 4000);
                    navigate('/', {replace: true});
                    return;
                }
                movieResolved = await res.json();
                setMovie(movieResolved);
            } else {
                Messages.showMessage("Brak wybranego filmu", 4000);
                navigate('/', {replace: true});
            }
            const [screeningsResolved, seatTypesResolved] = await Promise.all([
                fetchScreeningsForMovie(movieResolved.id_movie),
                fetchSeatTypes()
            ]);
            setScreenings(screeningsResolved);
            setScreening(screeningsResolved[0]);
            setSeatTypes(seatTypesResolved);
        } catch (e) {
            //console.error(e);
            Messages.showMessage("Wystąpił błąd podczas rezerwacji", 4000);
            navigate('/', {replace: true});
        } finally {
            setLoading(false);
        }
    };

    let blockSubmit = false;

    useEffect(() => {
        document.title = "RESERVATION PAGE";
        window.addEventListener("beforeunload", clearLocationState); // clears location.state, so that on the next full page load data is fetched based on id_movie url param, not old location.state
        const auth_cookie = getCookieURIEncodedJSONAsObject(AuthCookieName.Client) as AuthCookie | null;
        if (!auth_cookie) {
            Messages.showMessage("Nie jesteś zalogowany", 4000);
            navigate(AllowedRoutes.Login)
        } else {
            setEmail(auth_cookie.email ?? "Wyloguj");
            initializeData()
        }
    }, [location, navigate]);


    const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
        //console.log('handleSubmit blocked=', blockSubmit, event);
        event.preventDefault()
        event.stopPropagation()
        if (blockSubmit) {
            return;
        }

        const formData = new FormData(event.currentTarget);

        const id_seats = formData.getAll("id_seat[]").map((value) => parseInt(value.toString()));
        //console.log(formData, id_seats)
        const placeReservation = async () => {
            try {
                const reservations = await addBulkReservation(screening.id_screening, id_seats, discountName);
                //console.log(reservations);
                // save seats as reserved in state
                let reservedSeats: Seat[] = [];
                for (const reservation of reservations) {
                    reservedSeats.push({
                        hall: screening.hall,
                        id_seat: reservation.seat.id_seat,
                        number: reservation.seat.number,
                        row: reservation.seat.row,
                        seatType: {
                            id_seat_type: reservation.seat.seatType.id_seat_type
                        } as SeatTypePartial,
                    } as Seat);
                }
                setSeats((prevSeats) => ({
                    ...prevSeats,
                    [screening.hall.id_hall]: {
                        ...prevSeats[screening.hall.id_hall],
                        [screening.id_screening]: [
                            ...(prevSeats[screening.hall.id_hall][screening.id_screening] || []),
                            ...reservedSeats,
                        ],
                    },
                }))
                Messages.showMessage("Pomyślnie złożono rezerwację", 4000);
            } catch (error) {
                Messages.showMessage("Nie udało się złożyć rezerwacji", 4000);
                //console.error('Error fetching discount:', error);
            }
        }
        placeReservation();

    };
    const VAT = 23;

    const handleScreeningChange = (e: ChangeEvent<HTMLSelectElement>) => {
        setScreening(screenings[parseInt(e.target.value)]);
        setSummaryNetto(0);
        setSeatCounts({standard: 0, premium: 0, bed: 0});
        setTotalNetto(0);
    }

    const handleDiscountChange = (e: KeyboardEvent<HTMLInputElement>) => {
        blockSubmit = true;
        e.stopPropagation();
        const new_discount_value = e.currentTarget.value;
        if (e.key === 'Enter') {
            //console.log('handleDiscountChange', e);
            const updateDiscount = async () => {
                try {
                    const discount = await fetchDiscount(new_discount_value);
                    Messages.showMessage("Pomyślnie dodano rabat", 4000);
                    setDiscountNetto(decimalToInt(discount.amount));
                    setDiscountName(new_discount_value)
                } catch (error) {
                    Messages.showMessage("Niepoprawny kod rabatowy", 4000);
                    //console.error('Error fetching discount:', error);
                    setDiscountNetto(0);
                    setDiscountName(null);

                }
                blockSubmit = false;
            }
            updateDiscount();
        } else if (new_discount_value !== discountName) {
            setDiscountNetto(0);
            setDiscountName(null);
            blockSubmit = false;
        }
    }

    const handleSeatChange = (seat: Seat, screening: Screening, checked: boolean) => {
        const seat_type = seatTypes.find(type => type.id_seat_type === seat.seatType.id_seat_type)!;
        const seat_type_name = seat_type.seat_name;
        const seat_price_gr = decimalToInt(seat_type.price);
        const screening_price_gr = decimalToInt(screening.screeningType.price)

        const value_netto_gr = (seat_price_gr + screening_price_gr)
        const delta_netto_gr = checked ? value_netto_gr : -value_netto_gr;


        setSummaryNetto((prev) => prev + delta_netto_gr);
        setSeatCounts((prev) => ({
            standard: seat_type_name === "standard" ? prev.standard + (checked ? 1 : -1) : prev.standard,
            premium: seat_type_name === "premium" ? prev.premium + (checked ? 1 : -1) : prev.premium,
            bed: seat_type_name === "bed" ? prev.bed + (checked ? 1 : -1) : prev.bed,
        }));


        setTotalNetto((prev) =>
            Math.max(prev + delta_netto_gr, 0)
        );
    };

    const renderSeats = (screening: Screening) => {
        const rows: { [row: string]: JSX.Element[] } = {};

        if (!(screening.hall.id_hall in seats)) {
            const updateSeats = async () => {
                const [fetchedSeats, fetchedReservations] = await Promise.all([
                    fetchSeatsForHalls(screening.hall.id_hall),
                    fetchReservationsForScreening(screening.id_screening)
                ]);

                setSeats((prevSeats) => ({
                            ...prevSeats,
                            [screening.hall.id_hall]: {
                                seats: fetchedSeats,
                                [screening.id_screening]:
                                    fetchedReservations.map(
                                        (reservation) =>
                                            fetchedSeats.find(seat => seat.id_seat === reservation.seat.id_seat)!
                                    )
                            }
                        }
                    )
                )
            }
            updateSeats()
            return (
                <span>Ładownie układu sali</span>
            )

        }

        for (const seat of seats[screening.hall.id_hall].seats) {
            if (!rows[seat.row]) {
                rows[seat.row] = [];
            }
            const is_taken = seats[screening.hall.id_hall][screening.id_screening].some((reservedSeat: Seat) => reservedSeat.id_seat === seat.id_seat)
            const className = `seat_${seatTypes.find(type => type.id_seat_type === seat.seatType.id_seat_type)?.seat_name}`;

            rows[seat.row].push(
                <td key={seat.id_seat}>
                    <input
                        id={seat.id_seat.toString()}
                        className={styles[className]}
                        type="checkbox"
                        name="id_seat[]"
                        value={seat.id_seat}
                        disabled={is_taken}
                        defaultChecked={is_taken}
                        onChange={(e) => handleSeatChange(seat, screening, e.target.checked)}
                    />
                </td>
            );
        }

        return (
            <table>
                <tbody>
                {Object.entries(rows).map(([row, seats]) => (
                    <tr key={row}>
                        <th>{row.toUpperCase()}</th>
                        {seats}
                    </tr>
                ))}
                </tbody>
            </table>
        );
    };

    return (
        <>
            <Header
                title="Rezerwacja"
                links={[{
                    route: AllowedRoutes.Home,
                    iconClass: AllowedIconClass.Home,
                    text: 'Strona główna',
                }, {
                    route: AllowedRoutes.Logout,
                    iconClass: AllowedIconClass.Logout,
                    text: email,
                    onClick: async () => {
                        await logoutClient() && navigate(AllowedRoutes.Home);
                    },
                }]}/>
            <main className={styles._}>
                {loading ?
                    (
                        <div className={styles._}>
                            <p>Ładowanie...</p>
                        </div>
                    ) : (
                        <>
                            <div className={styles.left}>
                                <Poster
                                    title={movie?.title}
                                    poster={movie?.poster}
                                />
                            </div>
                            <form className={styles.right} onSubmit={handleSubmit}>
                                {/*<input type="hidden" name="ID_Movie" value={movie?.id_movie} required readOnly/>*/}
                                <div className={styles.room}>
                                    <div className={styles.screen}>
                                        <p>
                                            Sala {screening.hall.hall_name}
                                        </p>
                                        <p>
                                            {screening.screeningType.screening_name}
                                        </p>
                                    </div>
                                    <div className={styles.seats}>
                                        {renderSeats(screening)}
                                    </div>
                                </div>
                                <div className={styles.details}>
                                    <label htmlFor="start">Data i godzina startu</label>
                                    <select name={"id_screening"} id={"start"} required={true} onChange={handleScreeningChange}>
                                        {screenings.map((screening, index) => (
                                            <option key={screening.id_screening} value={index}>
                                                {formatDateTimeNoSubSecond(screening.start_time)} - {screening.screeningType.screening_name}
                                            </option>
                                        ))}
                                    </select>
                                    <div className={styles.summary}>
                                        <span>Typ fotela</span>
                                        <span>Ilość</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.specific}`}>
                                        <span>Normalny</span>
                                        <span>{seatCounts.standard}</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.specific}`}>
                                        <span>Premium</span>
                                        <span>{seatCounts.premium}</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.specific}`}>
                                        <span>Łóżko</span>
                                        <span>{seatCounts.bed}</span>
                                    </div>
                                    <input type="text" name="discount_name" placeholder="Wpisz kod rabatowy" onKeyDown={handleDiscountChange}/>

                                    <div className={styles.summary}>
                                        <span>Suma:</span>
                                        <span id={styles.sum}>{IntToDecimal(summaryNetto, VAT)}</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.discount}`}>
                                        <span>Rabat:</span>
                                        <span id={styles.disc}>{IntToDecimal(discountNetto, VAT)}</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.discounted}`}>
                                        <span>Do zapłaty:</span>
                                        <span id={styles.total}>{IntToDecimal(Math.max(totalNetto - discountNetto, 0), VAT)}</span>
                                    </div>
                                    <button type="submit">Potwierdzam i płacę</button>
                                </div>
                            </form>
                        </>
                    )}
            </main>
        </>
    );
}

export default ReservationPage;
