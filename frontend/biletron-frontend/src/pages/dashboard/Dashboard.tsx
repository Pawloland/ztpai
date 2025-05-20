import {FormEvent, useEffect, useState} from 'react';
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
import {deleteMovieById, fetchMovies} from "../../services/MovieService.tsx";
import {fetchLanguages} from "../../services/LanguageService.tsx";
import {Hall} from "../../types/Hall.tsx";
import {ScreeningType} from "../../types/ScreeningType.tsx";
import {fetchHalls} from "../../services/HallService.tsx";
import {fetchScreeningTypes} from "../../services/ScreeningTypeService.tsx";
import {Screening} from "../../types/Screening.tsx";
import {deleteScreeningById, fetchScreenings} from "../../services/ScreeningService.tsx";
import {Client} from "../../types/Client.tsx";
import {Worker} from "../../types/Worker.tsx";
import {deleteClientById, fetchClients} from "../../services/ClientService.tsx";
import {deleteWorkerById, fetchWorkers} from "../../services/WorkerService.tsx";
import {WorkerSession} from "../../types/WorkerSession.tsx";
import {fetchWorkerSessions} from "../../services/WorkerSessionService.tsx";
import {formatDate, formatDateTime, formatTime, formatWeekDay} from "../../utils/dateTime.tsx";
import {ClientSession} from "../../types/ClientSession.tsx";
import {fetchClientSessions} from "../../services/ClientSessionService.tsx";
import {ReservationExpanded} from "../../types/Reservation.tsx";
import {deleteReservationById, fetchReservations} from "../../services/ReservationService.tsx";


function Dashboard() {
    const [movies, setMovies] = useState<Movie[]>([]);
    const [reservations, setReservations] = useState<ReservationExpanded[]>([]);
    const [languages, setLanguages] = useState<Language[]>([]);
    const [halls, setHalls] = useState<Hall[]>([]);
    const [screeningTypes, setScreeningTypes] = useState<ScreeningType[]>([]);
    const [screenings, setScreenings] = useState<Screening[]>([]);
    const [clients, setClients] = useState<Client[]>([])
    const [workers, setWorkers] = useState<Worker[]>([])
    const [workerSessions, setWorkerSessions] = useState<WorkerSession[]>([])
    const [clientSessions, setClientSessions] = useState<ClientSession[]>([])
    const [loading, setLoading] = useState(true);
    const [nick, setNick] = useState<string>("Wyloguj");
    const navigate = useNavigate();

    const initializeData = async () => {
        setLoading(true);

        // Start all fetches at once
        const [languagesPromise, moviesPromise, reservationsPromise, hallsPromise, screeningTypesPromise, screeningsPromise, clientsPromise, workersPromise, workerSessionsPromise, clientSessionsPromise] =
            [fetchLanguages(), fetchMovies(), fetchReservations(), fetchHalls(), fetchScreeningTypes(), fetchScreenings(), fetchClients(), fetchWorkers(), fetchWorkerSessions(), fetchClientSessions()]

        // Wait for languages first
        const languages = await languagesPromise;
        setLanguages(languages);


        // Now await the remaining, already-started promises
        const [movies, reservations, halls, screeningTypes, screenings, clients, workers, workerSessions, clientSessions] =
            await Promise.all([moviesPromise, reservationsPromise, hallsPromise, screeningTypesPromise, screeningsPromise, clientsPromise, workersPromise, workerSessionsPromise, clientSessionsPromise]);
        setMovies(movies);
        setReservations(reservations as ReservationExpanded[]);
        //console.log(reservations);
        setHalls(halls);
        setScreeningTypes(screeningTypes);
        setScreenings(screenings);
        setClients(clients);
        setWorkers(workers);
        setWorkerSessions(workerSessions);
        setClientSessions(clientSessions);

        setLoading(false);
    };

    useEffect(() => {
        document.title = "ADMIN PAGE"; // Ustawia tytuł karty przeglądarki

        const auth_cookie = getCookieURIEncodedJSONAsObject(AuthCookieName.Worker) as AuthWorkerCookie | null;
        if (!auth_cookie) {
            Messages.showMessage("Nie jesteś zalogowany", 4000);
            navigate(AllowedRoutes.WorkerLogin)
        } else {
            setNick(auth_cookie?.nick || "Wyloguj");
            initializeData()
        }
    }, []);

    const handleAddMovie = (e: FormEvent) => {
        e.preventDefault();

        const form = e.target as HTMLFormElement;
        const formData = new FormData(form);

        fetch('/api/movies', {
            method: 'POST',
            body: formData,
        }).then((res) => {
            if (res.ok) {
                res.json().then((newMovie: Movie) => {
                    setMovies((prev) => [...prev, newMovie].sort((a, b) => a.title.localeCompare(b.title)));
                });
                form.reset(); // Reset the form fields
                Messages.showMessage('Film dodany pomyślnie', 4000);
            } else {
                Messages.showMessage('Nie udało się dodać filmu', 4000);
            }
        }).catch((err) => {
                Messages.showMessage('Wystąpił błąd przy dodawaniu filmu', 4000)
                //console.error('Error adding movie:', err);
            }
        )
    };

    const handleAddScreening = (e: FormEvent) => {
        e.preventDefault();

        const form = e.target as HTMLFormElement;
        const formData = new FormData(form);
        const screening = Object.fromEntries(formData.entries()) as unknown as Screening;
        screening.start_time = new Date(screening.start_time as string).toISOString();
        //console.log(screening);

        fetch('/api/screenings', {
            method: 'POST',
            body: JSON.stringify(screening),
            headers: {
                'Content-Type': 'application/ld+json',
            },
        }).then((res) => {
            if (res.ok) {
                res.json().then((newScreening: Screening) => {
                    setScreenings((prev) => [...prev, newScreening].sort((a, b) => new Date(a.start_time).getTime() - new Date(b.start_time).getTime()));
                });
                form.reset(); // Reset the form fields
                Messages.showMessage('Seans dodany pomyślnie', 4000);
            } else {
                Messages.showMessage('Nie udało się dodać seansu', 4000);
            }
        }).catch((err) => {
                Messages.showMessage('Wystąpił błąd przy dodawaniu seansu', 4000)
                //console.error('Error adding screening:', err);
            }
        )
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
    const file: InputType = {type: 'file', required: true}
    const movieSelect = (): InputType => ({
        type: 'select',
        required: true,
        options: movies.map((movie) => ({
            key: movie.title,
            value: "/api/movies/" + movie.id_movie.toString()
        })),
        default_option: 0
    })
    const hallSelect = (): InputType => ({
        type: 'select',
        required: true,
        options: halls.map((hall) => ({
            key: hall.hall_name,
            value: "/api/halls/" + hall.id_hall.toString()
        })),
        default_option: 0
    })
    const screeningTypeSelect = (): InputType => ({
        type: 'select',
        required: true,
        options: screeningTypes.map((screeningType) => ({
            key: screeningType.screening_name,
            value: "/api/screening_types/" + screeningType.id_screening_type.toString()
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
                                //console.error('Error logging out:', err)
                                // At this point, cookie might or might not be deleted by the server, IDK
                                // we can't delete HTTPOnly cookies from here, so we only delete the not HTTPOnly one
                                // to ensure, that in the UI there won't be a user nick, which implies being logged in
                                destroyCookie(AuthCookieName.Worker)
                            })
                    }
                }]}/>
            <main className={styles._}>
                {loading ?
                    <div className={styles._}>
                        <p>Ładowanie...</p>
                    </div> :
                    <>
                        <InsertForm form_labels={["title", "original_title", "duration", "description", "id_language", "id_dubbing", "id_subtitles", "poster"]}
                                    submit_text={"Dodaj film"}
                                    labels={["Tytuł", "Tytuł oryginalny", "Długość", "Opis", "Język", "Dubbing", "Napisy", "Plakat"]}
                                    data={[text, text, time, textarea, languageSelect(), languageSelect(), languageSelect(), file]}
                                    onSubmit={handleAddMovie}
                        />
                        <List title={"Filmy"} header={["ID", "Tytuł", "Długość"]}
                              data={movies.map(movie => [
                                  movie.id_movie,
                                  movie.title,
                                  new Date(movie.duration).toLocaleTimeString(undefined, {hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: 'UTC'}),

                              ])}
                              onColumnValueClick={
                                  async (value: any) => {
                                      if (await deleteMovieById(value[0])) {
                                          setMovies((prev) => prev.filter(movie => movie.id_movie !== value[0]));
                                          Messages.showMessage("Pomyślnie usunięto film", 4000);
                                      } else {
                                          Messages.showMessage("Nie masz uprawnień do usunięcia filmu, lub ma on już jakiś seans", 4000);
                                      }
                                  }
                              }
                        />
                        <InsertForm form_labels={["movie", "hall", "screeningType", "start_time"]}
                                    submit_text={"Dodaj seans"}
                                    labels={["Film", "Sala", "Typ Seansu", "Data"]}
                                    data={[movieSelect(), hallSelect(), screeningTypeSelect(), datetimeSelect()]}
                                    onSubmit={handleAddScreening}
                        />


                        <List title={"Rezerwacje"} header={["ID", "Mail", "ID Sali", "ID Fotel", "Rząd", "Kolumna", "Typ siedzenia", "Tytuł", "Typ seansu", "Rozpoczęcie", "", "", "Cena brutto"]}
                              data={reservations.map(reservation => {
                                  const start_time = new Date(reservation.screening.start_time);

                                  return [
                                      reservation.id_reservation,
                                      reservation.client.mail,
                                      reservation.screening.hall.id_hall,
                                      reservation.seat.id_seat,
                                      reservation.seat.row,
                                      reservation.seat.number,
                                      reservation.seat.seatType.seat_name,
                                      reservation.screening.movie.title,
                                      reservation.screening.screeningType.screening_name,
                                      formatDate(start_time),
                                      formatWeekDay(start_time),
                                      formatTime(start_time),
                                      reservation.total_price_brutto + " zł"
                                  ]
                              })}
                              onColumnValueClick={
                                  async (value: any) => {
                                      if (await deleteReservationById(value[0])) {
                                          setReservations((prev) => prev.filter(reservation => reservation.id_reservation !== value[0]));
                                          Messages.showMessage("Pomyślnie usunięto rezerwację", 4000);
                                      } else {
                                          Messages.showMessage("Nie masz uprawnień do usunięcia rezerwacji", 4000);
                                      }
                                  }
                              }
                        />

                        <List title={"Nadchodzące seanse"} header={["ID", "Data", "", "Godzina", "Tytuł", "Sala", "Typ"]}
                              data={screenings.map(screening => {
                                  const start_time = new Date(screening.start_time);

                                  return [
                                      screening.id_screening,
                                      formatDate(start_time),
                                      formatWeekDay(start_time),
                                      formatTime(start_time),
                                      screening.movie.title,
                                      screening.hall.hall_name,
                                      screening.screeningType.screening_name
                                  ];
                              })}
                              onColumnValueClick={
                                  async (value: any) => {
                                      if (await deleteScreeningById(value[0])) {
                                          setScreenings((prev) => prev.filter(screening => screening.id_screening !== value[0]));
                                          Messages.showMessage("Pomyślnie usunięto seans", 4000);
                                      } else {
                                          Messages.showMessage("Nie masz uprawnień do usunięcia seansu, lub ma on rezerwacje", 4000);
                                      }
                                  }
                              }
                        />

                        <List title={"Klienci"} header={["ID", "Imię", "Nazwisko", "Nick", "Mail"]}
                              data={clients.map(client => [
                                  client.id_client,
                                  client.client_name,
                                  client.client_surname,
                                  client.nick,
                                  client.mail,
                              ])}
                              onColumnValueClick={
                                  async (value: any) => {
                                      if (await deleteClientById(value[0])) {
                                          setClients((prev) => prev.filter(client => client.id_client !== value[0]));
                                          Messages.showMessage("Pomyślnie usunięto konto klienta", 4000);
                                      } else {
                                          Messages.showMessage("Nie masz uprawnień do usunięcia konta klienta, lub ma on rezerwacje", 4000);
                                      }
                                  }
                              }
                        />

                        <List title={"Konta administracyjne"} header={["ID", "Typ", "Imię", "Nazwisko", "Nick"]}
                              data={workers.map(worker => [
                                  worker.id_worker,
                                  worker.workerType.type_name,
                                  worker.worker_name,
                                  worker.worker_surname,
                                  worker.nick
                              ])}
                              onColumnValueClick={
                                  async (value: any) => {
                                      if (await deleteWorkerById(value[0])) {
                                          setWorkers((prev) => prev.filter(worker => worker.id_worker !== value[0]));
                                          Messages.showMessage("Pomyślnie usunięto konto administracyjne", 4000);
                                      } else {
                                          Messages.showMessage("Nie masz uprawnień do usunięcia konta administracyjnego", 4000);
                                      }
                                  }
                              }
                        />

                        <List title={"Sesje klientów"} header={["ID", "Nick", "Data wygaśnięcia"]}
                              data={clientSessions.map(clientSession => [
                                  clientSession.id_session_client,
                                  clientSession.client.mail,
                                  formatDateTime(clientSession.expiration_date),
                              ])}
                        />


                        <List title={"Sesje pracowników"} header={["ID", "Nick", "Data wygaśnięcia"]}
                              data={workerSessions.map(workerSession => [
                                  workerSession.id_session_worker,
                                  workerSession.worker.nick,
                                  formatDateTime(workerSession.expiration_date),
                              ])}
                        />
                    </>
                }
            </main>
        </>
    )
}

export default Dashboard;