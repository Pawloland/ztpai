import {ChangeEvent, FormEvent, useEffect, useState} from 'react';
import {Language} from '../../types/Language.ts';
import {Movie} from "../../types/Movie.ts";
import Header from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import {useNavigate} from "react-router";
import {AuthCookieName, AuthWorkerCookie} from "../../types/AuthCookie.ts";
import {destroyCookie, getCookieURIEncodedJSONAsObject} from "../../utils/cookies.tsx";
import Messages from "../../components/messages/Messages.tsx";
import List from "../../components/list/List.tsx";
import styles from './Dashboard.module.css';
import InsertForm, {InputType} from "../../components/inserForm/InserForm.tsx";
import {fetchMovies} from "../../services/MovieService.tsx";
import {fetchLanguages} from "../../services/LanguageService.tsx";
import {Hall} from "../../types/Hall.tsx";
import {ScreeningType} from "../../types/ScreeningType.tsx";
import {fetchHalls} from "../../services/HallService.tsx";
import {fetchScreeningTypes} from "../../services/ScreeningTypeService.tsx";
import {Screening} from "../../types/Screening.tsx";
import {fetchScreenings} from "../../services/ScreeningService.tsx";


function Dashboard() {
    const [movies, setMovies] = useState<Movie[]>([]);
    const [languages, setLanguages] = useState<Language[]>([]);
    const [halls, setHalls] = useState<Hall[]>([]);
    const [screeningTypes, setScreeningTypes] = useState<ScreeningType[]>([]);
    const [screenings, setScreenings] = useState<Screening[]>([]);
    const [loading, setLoading] = useState(true);
    const [nick, setNick] = useState<string>("Wyloguj");
    const [formData, setFormData] = useState({
        title: '',
        original_title: '',
        duration: '',
        description: '',
        languageViaIdLanguage: '/api/languages/1',
        languageViaIdDubbing: '/api/languages/1',
        languageViaIdSubtitles: '/api/languages/1',
    });
    const navigate = useNavigate();

    const initializeData = async () => {
        setLoading(true);

        // Start all fetches at once
        const [languagesPromise, moviesPromise, hallsPromise, screeningTypesPromise, screeningPromise] =
            [fetchLanguages(), fetchMovies(), fetchHalls(), fetchScreeningTypes(), fetchScreenings()]

        // Wait for languages first
        const languages = await languagesPromise;
        setLanguages(languages);

        // Set formData after languages are ready
        const first_language_id = languages[0];
        setFormData(prev => ({
            ...prev,
            languageViaIdLanguage: `/api/languages/${first_language_id}`,
            languageViaIdDubbing: `/api/languages/${first_language_id}`,
            languageViaIdSubtitles: `/api/languages/${first_language_id}`,
        }));

        // Now await the remaining, already-started promises
        const [movies, halls, screeningTypes, screenings] =
            await Promise.all([moviesPromise, hallsPromise, screeningTypesPromise, screeningPromise]);
        setMovies(movies);
        setHalls(halls);
        setScreeningTypes(screeningTypes);
        setScreenings(screenings);

        setLoading(false);
    };

    useEffect(() => {
        document.title = "ADMIN PAGE"; // Ustawia tytuł karty przeglądarki

        initializeData();
        const auth_cookie = getCookieURIEncodedJSONAsObject(AuthCookieName.Worker) as AuthWorkerCookie | null;
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

    const text: InputType = {type: 'text', required: true}
    const time: InputType = {type: 'time', required: true}
    const textarea: InputType = {type: 'textarea', required: true}
    const languageSelect = (): InputType => ({
        type: 'select',
        required: true,
        options: languages.map((lang) => ({
            key: lang.language_name,
            value: lang.id_language.toString()
        })),
        default_option: 0
    })
    const file: InputType = {type: 'file', required: false}
    const movieSelect = (): InputType => ({
        type: 'select',
        required: true,
        options: movies.map((movie) => ({
            key: movie.title,
            value: movie.id_movie.toString()
        })),
        default_option: 0
    })
    const hallSelect = (): InputType => ({
        type: 'select',
        required: true,
        options: halls.map((hall) => ({
            key: hall.hall_name,
            value: hall.id_hall.toString()
        })),
        default_option: 0
    })
    const screeningTypeSelect = (): InputType => ({
        type: 'select',
        required: true,
        options: screeningTypes.map((screeningType) => ({
            key: screeningType.screening_name,
            value: screeningType.id_screening_type.toString()
        })),
        default_option: 0
    })
    const datetimeSelect = (): InputType => ({
        type: 'datetime-local',
        required: true
    })

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
            <main className={styles.main}>
                {loading ?
                    <div className={styles.loading}>
                        <p>Ładowanie...</p>
                    </div> :
                    <>
                        <InsertForm form_labels={["title", "original_title", "duration", "description", "language", "dubbing", "subtitles", "poster"]}
                                    submit_text={"Dodaj film"}
                                    labels={["Tytuł", "Tytuł oryginalny", "Długość", "Opis", "Język", "Dubbing", "Napisy", "Plakat"]}
                                    data={[text, text, time, textarea, languageSelect(), languageSelect(), languageSelect(), file]}
                                    onSubmit={(e) => {
                                        console.log(e);
                                        e.preventDefault()
                                    }}
                        />
                        <List title={"Filmy"} header={["ID", "Tytuł", "Długość"]}
                              data={movies.map(movie => [
                                  movie.id_movie,
                                  movie.title,
                                  new Date(movie.duration).toLocaleTimeString(undefined, {hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: 'UTC'}),

                              ])}
                              onColumnValueClick={
                                  (value: any) => {
                                      console.log(value);
                                      console.log(value[0]);
                                  }
                              }
                        />
                        <InsertForm form_labels={["title", "hall", "type", "date"]}
                                    submit_text={"Dodaj seans"}
                                    labels={["Film", "Sala", "Typ Seansu", "Data"]}
                                    data={[movieSelect(), hallSelect(), screeningTypeSelect(), datetimeSelect()]}
                                    onSubmit={(e) => {
                                        console.log(e);
                                        e.preventDefault()
                                    }}


                        />


                        <List title={"Rezerwacje"} header={["ID", "Mail", "ID Sali", "ID Fotel", "Rząd", "Kolumna", "Typ siedzenia", "Tytuł", "Typ seansu", "Rozpoczęcie", "Cena brutto"]}
                              data={[]}
                              onColumnValueClick={
                                  (value: any) => {
                                      console.log(value);
                                      console.log(value[0]);
                                  }
                              }
                        />
                        <List title={"Nadchodzące seanse"} header={["ID", "Data", "", "Godzina", "Tytuł", "Sala", "Typ"]}
                              data={screenings.map(screening => {
                                  const start_time = new Date(screening.start_time);

                                  return [
                                      screening.id_screening,
                                      start_time.toLocaleDateString(undefined, {year: 'numeric', month: '2-digit', day: '2-digit'}),
                                      start_time.toLocaleDateString(undefined, {weekday: "long"}).replace(/^./, c => c.toUpperCase()), // Capitalize first letter
                                      start_time.toLocaleTimeString(undefined, {hour: '2-digit', minute: '2-digit'}),
                                      screening.movie.title,
                                      screening.hall.hall_name,
                                      screening.screeningType.screening_name
                                  ];
                              })}
                              onColumnValueClick={
                                  (value: any) => {
                                      console.log(value);
                                      console.log(value[0]);
                                  }
                              }
                        />

                        <List title={"Klienci"} header={["ID", "Imię", "Nazwisko", "Nick", "Mail"]}
                              data={[
                                  ["31", "", "", "admin@admin.admin", "admin@admin.admin"],
                                  ["31", "", "", "admin@admin.admin", "admin@admin.admin"],
                                  ["31", "", "", "admin@admin.admin", "admin@admin.admin"],
                                  ["31", "", "", "admin@admin.admin", "admin@admin.admin"],
                                  ["31", "", "", "admin@admin.admin", "admin@admin.admin"],
                                  ["31", "", "", "admin@admin.admin", "admin@admin.admin"],
                                  ["31", "", "", "admin@admin.admin", "admin@admin.admin"],
                                  ["31", "", "", "admin@admin.admin", "admin@admin.admin"],
                              ]}
                              onColumnValueClick={
                                  (value: any) => {
                                      console.log(value);
                                      console.log(value[0]);
                                  }
                              }
                        />

                        <List title={"Konta administracyjne"} header={["ID", "Typ", "Imię", "Nazwisko", "Nick"]}
                              data={[
                                  ["7", "Admin", "admin", "admin", "admin"],
                                  ["7", "Admin", "admin", "admin", "admin"],
                                  ["7", "Admin", "admin", "admin", "admin"],
                                  ["7", "Admin", "admin", "admin", "admin"],
                                  ["7", "Admin", "admin", "admin", "admin"],
                                  ["7", "Admin", "admin", "admin", "admin"],
                                  ["7", "Admin", "admin", "admin", "admin"],
                                  ["7", "Admin", "admin", "admin", "admin"],
                              ]}
                              onColumnValueClick={
                                  (value: any) => {
                                      console.log(value);
                                      console.log(value[0]);
                                  }
                              }
                        />

                        <List title={"Sesje klientów"} header={["ID", "Nick", "Data wygaśnięcia"]}
                              data={[
                                  ["6", "admin@admin.admin", "2025-05-05 16:45:45.993925"],
                                  ["6", "admin@admin.admin", "2025-05-05 16:45:45.993925"],
                                  ["6", "admin@admin.admin", "2025-05-05 16:45:45.993925"],
                              ]}
                              onColumnValueClick={
                                  (value: any) => {
                                      console.log(value);
                                      console.log(value[0]);
                                  }
                              }
                        />


                        <List title={"Sesje pracowników"} header={["ID", "Nick", "Data wygaśnięcia"]}
                              data={[
                                  ["6", "admin", "2025-05-05 16:45:45.993925"],
                                  ["6", "admin", "2025-05-05 16:45:45.993925"],
                                  ["6", "admin", "2025-05-05 16:45:45.993925"],
                              ]}
                              onColumnValueClick={
                                  (value: any) => {
                                      console.log(value);
                                      console.log(value[0]);
                                  }
                              }
                        />

                    </>
                }
            </main>
        </>
    )
        ;
}

export default Dashboard;