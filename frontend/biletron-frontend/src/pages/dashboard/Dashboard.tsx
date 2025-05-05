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
import List from "../../components/list/List.tsx";
import styles from './Dashboard.module.css';
import InsertForm, {InputType} from "../../components/inserForm/InserForm.tsx";


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

    const text: InputType = {type: 'text', required: true}
    const time: InputType = {type: 'time', required: true}
    const textarea: InputType = {type: 'textarea', required: true}
    const select: InputType = {
        type: 'select',
        required: true,
        options: languages.map((lang) => ({
            key: lang.language_name,
            value: lang.id_language.toString()
        })),
        default_option: 0
    }
    const file: InputType = {type: 'file', required: false}


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
                <InsertForm form_labels={["title", "original_title", "duration", "description", "language", "dubbing", "subtitles", "poster"]}
                            submit_text={"Dodaj film"}
                            labels={["Tytuł", "Tytuł oryginalny", "Długość", "Opis", "Język", "Dubbing", "Napisy", "Plakat"]}
                            data={[text, text, time, textarea, select, select, select, file]}
                            onSubmit={(e) => {
                                console.log(e);
                                e.preventDefault()
                            }}


                />
                <List title={"Filmy"} header={["ID", "Tytuł", "Długość"]} data={[["1", "asdadads", "1:0:0"], ["2", "asd", "1:2:0"]]}
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
                            data={[select,select,select,select]}
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
                <List title={"Nadchodzące seanse"} header={["ID", "Data", "Godzina", "Tytuł", "Sala", "Typ"]}
                      data={[
                          ["1001", "2026.01.01 Thursday", "10:00", "Alien Romulus", "2", "2D"],
                          ["1001", "2026.01.01 Thursday", "10:00", "Alien Romulus", "2", "2D"],
                          ["1001", "2026.01.01 Thursday", "10:00", "Alien Romulus", "2", "2D"],
                          ["1001", "2026.01.01 Thursday", "10:00", "Alien Romulus", "2", "2D"],
                          ["1001", "2026.01.01 Thursday", "10:00", "Alien Romulus", "2", "2D"],
                          ["1001", "2026.01.01 Thursday", "10:00", "Alien Romulus", "2", "2D"],
                          ["1001", "2026.01.01 Thursday", "10:00", "Alien Romulus", "2", "2D"],
                      ]}
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

            </main>
        </>
    );
}

export default Dashboard;