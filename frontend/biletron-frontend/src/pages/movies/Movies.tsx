import {useEffect, useState} from 'react';
import {Movie, MoviesResponse} from "../../types/Movie.ts";
import Header from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";


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
            <main>
                <div className="wrapper">
                    <div className="pane right">
                        <div className="header">
                            <h2>Movies</h2>
                            <button onClick={fetchMovies}>Refresh</button>
                        </div>

                        {loading ? (
                            <p>Loading movies...</p>
                        ) : (
                            <ul>
                                {movies.map((movie) => (
                                    <li key={movie.id_movie}>
                                        <a href={`/api/movies/${movie.id_movie}`}>{movie.title}</a>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </div>
                </div>

            </main>
        </>
    );
}

export default Movies;