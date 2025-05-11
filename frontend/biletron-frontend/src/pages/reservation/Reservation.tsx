import Header from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import {FormEvent, useEffect, useState} from "react";
import styles from './Reservation.module.css';
import {useLocation, useNavigate} from "react-router";
import {Movie} from "../../types/Movie.ts";
import {getCookieURIEncodedJSONAsObject} from "../../utils/cookies.tsx";
import {AuthCookie, AuthCookieName} from "../../types/AuthCookie.ts";
import {logoutClient} from "../../services/ClientService.tsx";
import Messages from "../../components/messages/Messages.tsx";
import Poster from "../../components/poster/Poster.tsx";

function Reservation() {
    const [email, setEmail] = useState<string>("Wyloguj");
    const [loading, setLoading] = useState(true);
    const location = useLocation();
    const navigate = useNavigate();
    const [movie, setMovie] = useState<Movie>();

    const clearLocationState = () => {
        window.history.replaceState({}, '')
        window.removeEventListener("beforeunload", clearLocationState);
    }

    useEffect(() => {
        setLoading(true);
        window.addEventListener("beforeunload", clearLocationState); // clears location.state, so that on the next full page load data is fetched based on id_movie url param, not old location.state

        document.title = "RESERVATION PAGE";
        // initializeData();
        const auth_cookie = getCookieURIEncodedJSONAsObject(AuthCookieName.Client) as AuthCookie | null;
        setEmail(auth_cookie?.email ?? "Wyloguj");

        const searchParams = new URLSearchParams(location.search);
        const idFromQuery = searchParams.get('id_movie');
        const movieFromState = location.state?.movie as Movie | undefined;

        if (movieFromState) {
            setMovie(movieFromState);
        } else if (idFromQuery) {
            fetch(`/api/movies/${idFromQuery}`)
                .then((res) => {
                    if (!res.ok) {
                        navigate('/', {replace: true});
                        Messages.showMessage("Nie ma takiego filmu", 4000);
                    }
                    return res.json();
                })
                .then((movie: Movie) => {
                    setMovie(movie);
                    console.log(movie);
                })
                .catch((e) => {
                    navigate('/', {replace: true});
                    console.error(e);
                    Messages.showMessage("Wystąpił błąd podczas rezerwacji", 4000);
                });
        } else {
            Messages.showMessage("Brak wybranego filmu", 4000);
            navigate('/', {replace: true});
        }
        setLoading(false);
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
                                            hall
                                        </p>
                                        <p>
                                            screening type
                                        </p>
                                    </div>
                                    <div className={styles.seats}>
                                        siedzenia
                                    </div>
                                </div>
                                <div className={styles.details}>
                                    <label htmlFor="start">Data i godzina startu</label>
                                    <select name={"id_screening"} id={"start"} required={true}>
                                        <option value="0">1</option>
                                        <option value="0">2</option>
                                    </select>
                                    <div className={styles.summary}>
                                        <span>Typ fotela</span>
                                        <span>Ilość</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.specific}`}>
                                        <span>Normalny</span>
                                        <span id="seat_std">0</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.specific}`}>
                                        <span>Premium</span>
                                        <span id="seat_pro">0</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.specific}`}>
                                        <span>Łóżko</span>
                                        <span id="seat_bed">0</span>
                                    </div>
                                    <input id="discount_code" type="text" name="discount_name" placeholder="Wpisz kod rabatowy"/>

                                    <div className={styles.summary}>
                                        <span>Suma:</span>
                                        <span id={styles.sum}>0.00</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.discount}`}>
                                        <span>Rabat:</span>
                                        <span id={styles.disc}>0.00</span>
                                    </div>
                                    <div className={`${styles.summary} ${styles.discounted}`}>
                                        <span>Do zapłaty:</span>
                                        <span id={styles.total}>0.00</span>
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

export default Reservation;
