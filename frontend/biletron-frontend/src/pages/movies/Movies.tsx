import styles from "./Movies.module.css";
import {useEffect, useState} from 'react';
import {Movie} from "../../types/Movie.ts";
import Header from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import Poster from "../../components/poster/Poster.tsx";
import {getCookieURIEncodedJSONAsObject} from "../../utils/cookies.tsx";
import {AuthCookie, AuthCookieName} from "../../types/AuthCookie.ts";
import {fetchMoviesWithScreeningsInFuture} from "../../services/MovieService.tsx";
import {logoutClient} from "../../services/ClientService.tsx";
import {useNavigate} from "react-router";


function Movies() {
    const [movies, setMovies] = useState<Movie[]>([]);
    const [loading, setLoading] = useState(true);
    const [email, setEmail] = useState<string | null>(null);
    const navigate = useNavigate();

    const initializeData = async () => {
        setLoading(true);
        setMovies(await fetchMoviesWithScreeningsInFuture());
        setLoading(false);
    };

    useEffect(() => {
        document.title = "SELECT MOVIE PAGE";
        initializeData();
        const auth_cookie = getCookieURIEncodedJSONAsObject(AuthCookieName.Client) as AuthCookie | null;
        setEmail(auth_cookie?.email ?? null);
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
                            onClick: async () => {
                                await logoutClient() && navigate(AllowedRoutes.Home);
                            },
                        }]
                }
            />
            <main className={styles._}>
                {loading ?
                    (
                        <div className={styles._}>
                            <p>Ładowanie...</p>
                        </div>
                    )
                    :
                    movies.map(movie => {
                        return (
                            <Poster
                                key={movie.id_movie}
                                movie={movie}
                                poster={movie.poster}
                                title={movie.title}
                            />
                        );
                    })
                }
            </main>
        </>
    );
}

export default Movies;