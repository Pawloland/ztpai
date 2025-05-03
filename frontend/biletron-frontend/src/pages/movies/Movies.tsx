import styles from "./Movies.module.css";
import {useEffect, useState} from 'react';
import {Movie, MoviesResponse} from "../../types/Movie.ts";
import Header from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import Poster from "../../components/poster/Poster.tsx";
import {destroyCookie, getCookieURIEncodedJSONAsObject} from "../../utils/cookies.tsx";
import {AuthCookie, AuthCookieName} from "../../types/AuthCookie.ts";
import Messages from "../../components/messages/Messages.tsx";


function Movies() {
    const [movies, setMovies] = useState<Movie[]>([]);
    const [loading, setLoading] = useState(true);
    const [email, setEmail] = useState<string | null>(null);

    const fetchMovies = async () => {
        try {
            const response = await fetch('/api/movies');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data: MoviesResponse = await response.json();
            setMovies(data.member);
        } catch (err) {
            console.error('Error fetching movies:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        document.title = "SELECT MOVIE PAGE";
        const initializeData = async () => {
            setLoading(true);
            fetchMovies()
        };
        initializeData();
        const auth_cookie = getCookieURIEncodedJSONAsObject(AuthCookieName.Client) as AuthCookie | null;
        console.log(auth_cookie)
        setEmail(auth_cookie?.email ?? null);
        console.log(email)
    }, []);

    return (
        <>
            <Header
                title="Twój system do kupowania biletów on-line!"
                links={
                    email === null
                        ? [{
                            route: AllowedRoutes.Login,
                            iconClass: AllowedIconClass.Pen,
                            text: 'Zaloguj',
                        }, {
                            route: AllowedRoutes.Register,
                            iconClass: AllowedIconClass.Pen,
                            text: 'Zarejestruj',
                        }]
                        : [{
                            route: AllowedRoutes.Logout,
                            iconClass: AllowedIconClass.Logout,
                            text: email,
                            onClick: () => {
                                fetch('/api' + AllowedRoutes.Logout, {
                                    method: 'GET'
                                })
                                    .then((res) => {
                                        if (res.ok) {
                                            Messages.showMessage('Wylogowano pomyślnie!', 4000);
                                        } else {
                                            Messages.showMessage('Nie udało się wylogować, bo taka sesja nie istnieje', 4000);
                                        }
                                        destroyCookie(AuthCookieName.Client);
                                        setEmail(null);

                                    })
                                    .catch((err) => {
                                        console.error('Error logging out:', err);
                                        destroyCookie(AuthCookieName.Client);
                                    });
                            }
                        }]
                }
            />
            <main className={styles._}>
                {movies.map(movie => {
                    return (
                        <Poster
                            key={movie.id_movie}
                            ID_Movie={movie.id_movie}
                            poster={movie.poster}
                            title={movie.title}
                        />
                    );
                })}

            </main>
        </>
    );
}

export default Movies;