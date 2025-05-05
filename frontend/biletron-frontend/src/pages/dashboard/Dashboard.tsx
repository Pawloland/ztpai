import {ChangeEvent, FormEvent, useEffect, useState} from 'react';
import {Language, LanguageResponse} from '../../types/Language.ts';
import {Movie, MoviesResponse} from "../../types/Movie.ts";
import Header from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import {useNavigate} from "react-router";
import {AuthCookieName, AuthWorkerCookie} from "../../types/AuthCookie.ts";
import {destroyCookie, getCookieURIEncodedJSONAsObject} from "../../utils/cookies.tsx";
import Messages from "../../components/messages/Messages.tsx";
import styles from './Dashboard.module.css';


function Dashboard() {
    const [movies, setMovies] = useState<Movie[]>([]);
    const [languages, setLanguages] = useState<Language[]>([]);
    const [loading, setLoading] = useState(true);
    const [nick, setNick] = useState<string>("Wyloguj");
    const [formData, setFormData] = useState({
        title: '',
        original_title: '',
        duration: '',
        description: '',
        // poster: '',
        languageViaIdLanguage: '/api/languages/1',
        languageViaIdDubbing: '/api/languages/1',
        languageViaIdSubtitles: '/api/languages/1',
    });
    const navigate = useNavigate();

    const fetchLanguages = async () => {
        try {
            const response = await fetch('/api/languages');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data: LanguageResponse = await response.json();
            setLanguages(data.member);
            if (data.member.length > 0) {
                setFormData(prev => ({
                    ...prev,
                    languageViaIdLanguage: `/api/languages/${data.member[0].id_language}`,
                    languageViaIdDubbing: `/api/languages/${data.member[0].id_language}`,
                    languageViaIdSubtitles: `/api/languages/${data.member[0].id_language}`,
                }));
            }
            return true;
        } catch (err) {
            console.error('Error fetching languages:', err);
            return false;
        }
    };

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
        document.title = "ADMIN PAGE"; // Ustawia tytuł karty przeglądarki
        const initializeData = async () => {
            setLoading(true);
            const languagesLoaded = await fetchLanguages();
            if (languagesLoaded) {
                await fetchMovies();
            } else {
                setLoading(false);
            }
        };

        initializeData();
        const auth_cookie = getCookieURIEncodedJSONAsObject(AuthCookieName.Worker) as AuthWorkerCookie | null;
        console.log(auth_cookie)
        setNick(auth_cookie?.nick || "Wyloguj");

    }, []);

    const handleInputChange = (e: ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
        const {name, value} = e.target;
        setFormData(prev => {
            return {
                ...prev,
                [name]: value
            }
        });
    };

    const handleAddMovie = (e: FormEvent) => {
        e.preventDefault();
        fetch('/api/movies', {
            method: 'POST',
            headers: {'Content-Type': 'application/ld+json'},
            body: JSON.stringify(formData),
        })
            .then((res) => {
                if (res.ok) {
                    fetchMovies(); // Refresh the movie list
                    setFormData({
                        title: '',
                        original_title: '',
                        duration: '',
                        description: '',
                        // poster: '',
                        languageViaIdLanguage: '/api/languages/1',
                        languageViaIdDubbing: '/api/languages/1',
                        languageViaIdSubtitles: '/api/languages/1',
                    });
                } else {
                    console.error('Failed to add movie');
                }
            })
            .catch((err) => console.error('Error adding movie:', err));
    };

    return (
        <>
            <Header
                title="Panel administracyjny"
                links={[{
                    route: AllowedRoutes.WorkerLogout,
                    iconClass: AllowedIconClass.Logout,
                    text: nick,
                    onClick: () => {
                        fetch('/api' + AllowedRoutes.WorkerLogout, {
                            method: 'GET'
                        })
                            .then((res) => {
                                if (res.ok) {
                                    Messages.showMessage('Wylogowano pomyślnie!', 4000);
                                    setTimeout(() => {
                                        navigate(AllowedRoutes.WorkerLogin)
                                    }, 1000)
                                } else {
                                    Messages.showMessage('Nie udało się wylogować, bo taka sesja nie istnieje', 4000);
                                }
                            })
                            .catch((err) => {
                                console.error('Error logging out:', err)
                                // At this point, cookie might or might not be deleted by the server, IDK
                                // we can't delete HTTPOnly cookies from here, so we only delete the not HTTPOnly one
                                // to ensure, that in the UI there won't be a user nick, which implies being logged in
                                destroyCookie(AuthCookieName.Worker)
                            })
                    }
                }]}/>
            <main>
                <div className="wrapper">
                    <div className="pane left">
                        <h2>Add Movie</h2>
                        <form onSubmit={handleAddMovie}>
                            <label>
                                Title:
                                <input type="text" name="title" value={formData.title} onChange={handleInputChange} required/>
                            </label>
                            <label>
                                Original Title:
                                <input type="text" name="original_title" value={formData.original_title} onChange={handleInputChange} required/>
                            </label>
                            <label>
                                Duration:
                                <input
                                    type="time"
                                    step="1"
                                    name="duration"
                                    value={formData.duration}
                                    onChange={handleInputChange}
                                    required
                                />
                            </label>
                            <label>
                                Description:
                                <textarea name="description" value={formData.description} onChange={handleInputChange} required/>
                            </label>
                            {/*<label>*/}
                            {/*    Poster filename:*/}
                            {/*    <input type="text" name="poster" value={formData.poster} onChange={handleInputChange} required/>*/}
                            {/*</label>*/}
                            <label>
                                Language:
                                <select
                                    name="languageViaIdLanguage"
                                    value={formData.languageViaIdLanguage}
                                    onChange={handleInputChange}
                                    required
                                >
                                    {languages.map(lang => (
                                        <option key={`/api/languages/${lang.id_language}`} value={`/api/languages/${lang.id_language}`}>
                                            {lang.language_name}
                                        </option>
                                    ))}
                                </select>
                            </label>
                            <label>
                                Dubbing:
                                <select
                                    name="languageViaIdDubbing"
                                    value={formData.languageViaIdDubbing}
                                    onChange={handleInputChange}
                                    required
                                >
                                    {languages.map(lang => (
                                        <option key={`/api/languages/${lang.id_language}`} value={`/api/languages/${lang.id_language}`}>
                                            {lang.language_name}
                                        </option>
                                    ))}
                                </select>
                            </label>
                            <label>
                                Subtitles:
                                <select
                                    name="languageViaIdSubtitles"
                                    value={formData.languageViaIdSubtitles}
                                    onChange={handleInputChange}
                                    required
                                >
                                    {languages.map(lang => (
                                        <option key={`/api/languages/${lang.id_language}`} value={`/api/languages/${lang.id_language}`}>
                                            {lang.language_name}
                                        </option>
                                    ))}
                                </select>
                            </label>
                            <button type="submit">Add Movie</button>
                        </form>
                    </div>
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
                                        <button
                                            onClick={() => {
                                                fetch(`/api/movies/${movie.id_movie}`, {
                                                    method: 'DELETE',
                                                    headers: {'Content-Type': 'application/json'},
                                                })
                                                    .then((res) => {
                                                        if (res.ok) {
                                                            fetchMovies(); // Refresh the movie list
                                                        } else {
                                                            console.error('Failed to delete movie');
                                                        }
                                                    })
                                                    .catch((err) => console.error('Error deleting movie:', err));
                                            }}
                                        >
                                            Delete
                                        </button>
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

export default Dashboard;