import styles from "./Movies.module.css";
import {useEffect, useState} from 'react';
import {Movie, MoviesResponse} from "../../types/Movie.ts";
import Header from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import Poster from "../../components/poster/Poster.tsx";


function Movies() {
    const [movies, setMovies] = useState<Movie[]>([]);
    const [loading, setLoading] = useState(true);

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
    }, []);

    return (
        <>
            <Header
                title="Twój system do kupowania biletów on-line!"
                links={[{
                    route: AllowedRoutes.Login,
                    iconClass: AllowedIconClass.Pen,
                    text: 'Zaloguj',
                }, {
                    route: AllowedRoutes.Register,
                    iconClass: AllowedIconClass.Pen,
                    text: 'Zarejestruj',
                }]}/>
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