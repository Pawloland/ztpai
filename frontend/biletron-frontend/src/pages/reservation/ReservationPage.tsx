import Header from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import {ChangeEvent, FormEvent, JSX, useEffect, useState} from "react";
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
import {SeatType} from "../../types/SeatType.tsx";
import {fetchSeatTypes} from "../../services/SeatTypeService.tsx";
import {fetchReservationsForScreening} from "../../services/ReservationService.tsx";
import {decimalToInt, IntToDecimal} from "../../utils/decimal.tsx";


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
    const [discount, setDiscount] = useState<number>(0);
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
            console.error(e);
            Messages.showMessage("Wystąpił błąd podczas rezerwacji", 4000);
            navigate('/', {replace: true});
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        document.title = "RESERVATION PAGE";
        window.addEventListener("beforeunload", clearLocationState); // clears location.state, so that on the next full page load data is fetched based on id_movie url param, not old location.state
        initializeData()
        const auth_cookie = getCookieURIEncodedJSONAsObject(AuthCookieName.Client) as AuthCookie | null;
        setEmail(auth_cookie?.email ?? "Wyloguj");
    }, [location, navigate]);


    const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
        // event.preventDefault(); // Prevent default form submission
        //
        // const formData = new FormData(event.currentTarget);
        //
        // const payload: Record<string, any> = {};
        // formData.forEach((value, key) => {
        //     payload[key] = value;
        // });
        //
        // try {
        //     const response = await fetch(action, {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //         },
        //         body: JSON.stringify(payload),
        //     });
        //
        //     if (response.ok) {
        //         const data = await response.json();
        //         console.log('Success:', data);
        //         setMessage('Zalogowano pomyślnie!');
        //         setTimeout(() => {
        //             if (variant === AllowedVariants.Worker) {
        //                 navigate(AllowedRoutes.Dashboard);
        //             } else {
        //                 navigate(AllowedRoutes.Home);
        //             }
        //         }, 1000);
        //     } else {
        //         const errorText = await response.text();
        //         console.error('Server error:', errorText);
        //         setMessage('Wprowadź poprawne dane');
        //     }
        // } catch (error) {
        //     console.error('Network error:', error);
        //     setMessage('Błąd sieci, spróbuj ponownie.');
        // }
    };
    const VAT = 23;

    // const handleSeatChange = (seat: any, screening: Screening, checked: boolean) => {
    //     const value = (1 + VAT / 100) * (parseFloat(seat.price) + parseFloat(screening.screeningType.price));
    //     const delta = checked ? value : -value;
    //
    //     const summary = document.getElementById("sum")!;
    //     const discount = document.getElementById("disc")!;
    //     const total = document.getElementById("total")!;
    //
    //     const std = document.getElementById("seat_std")!;
    //     const pro = document.getElementById("seat_pro")!;
    //     const bed = document.getElementById("seat_bed")!;
    //
    //     summary.innerText = (parseFloat(summary.innerText) + delta).toFixed(2);
    //
    //     if (seat.seat_name === "standard") std.innerText = `${parseInt(std.innerText) + (checked ? 1 : -1)}`;
    //     if (seat.seat_name === "premium") pro.innerText = `${parseInt(pro.innerText) + (checked ? 1 : -1)}`;
    //     if (seat.seat_name === "bed") bed.innerText = `${parseInt(bed.innerText) + (checked ? 1 : -1)}`;
    //
    //     // recalculate total
    //     total.innerText = Math.max(parseFloat(summary.innerText) - parseFloat(discount.innerText), 0).toFixed(2);
    // };

    const handleScreeningChange = (e: ChangeEvent<HTMLSelectElement>) => {
        setScreening(screenings[parseInt(e.target.value)]);
        setSummaryNetto(0);
        setSeatCounts({standard: 0, premium: 0, bed: 0});
        setTotalNetto(0);
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
            Math.max(prev + delta_netto_gr - discount, 0)
        );

        // setTotal((prevSummary, prevDiscount) =>
        //     Math.max(parseFloat(prevSummary) - parseFloat(prevDiscount), 0).toFixed(2)
        // );
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
                            <form className={styles.right}>
                                <input type="hidden" name="ID_Movie" value={movie?.id_movie} required readOnly/>
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
                                    <input id="discount_code" type="text" name="discount_name" placeholder="Wpisz kod rabatowy"/>

                                    <div className={styles.summary}>
                                        <span>Suma:</span>
                                        <span id={styles.sum}>{IntToDecimal(summaryNetto, VAT)}</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.discount}`}>
                                        <span>Rabat:</span>
                                        <span id={styles.disc}>{discount.toFixed(2)}</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.discounted}`}>
                                        <span>Do zapłaty:</span>
                                        <span id={styles.total}>{IntToDecimal(totalNetto, VAT)}</span>
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
