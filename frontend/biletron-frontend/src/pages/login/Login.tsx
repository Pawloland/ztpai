import Header, {HeaderLink} from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import {FormEvent, useEffect, useState} from "react";
import styles from './Login.module.css';
import {useNavigate} from "react-router";
export enum AllowedVariants {
    Worker = "worker",
    Client = "client",
}

function Login({variant}: { variant: AllowedVariants }) {
    const [message, setMessage] = useState('');
    const navigate = useNavigate();

    let links: HeaderLink[] = [{
        route: AllowedRoutes.Home,
        iconClass: AllowedIconClass.Home,
        text: 'Strona główna',
    }]

    if (variant == AllowedVariants.Client) {
        links.push({
            route: AllowedRoutes.Register,
            iconClass: AllowedIconClass.Pen,
            text: 'Zarejestruj',
        });
    }
    const action = '/api' + (variant === AllowedVariants.Worker ? AllowedRoutes.WorkerLogin : AllowedRoutes.Login);

    useEffect(() => {
        document.title = "LOGIN PAGE";
    }, []);

    const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(event.currentTarget);

        const payload: Record<string, any> = {};
        formData.forEach((value, key) => {
            payload[key] = value;
        });

        try {
            const response = await fetch(action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            if (response.ok) {
                const data = await response.json();
                console.log('Success:', data);
                setMessage('Zalogowano pomyślnie!');
                setTimeout(() => {
                    navigate(AllowedRoutes.Dashboard);
                }, 1000);
            } else {
                const errorText = await response.text();
                console.error('Server error:', errorText);
                setMessage('Wprowadź poprawne dane');
            }
        } catch (error) {
            console.error('Network error:', error);
            setMessage('Błąd sieci, spróbuj ponownie.');
        }
    };

    return (
        <>
            <Header
                title="Logowanie"
                links={links}/>
            <main className={styles._}>
                <form className={styles.auth} onSubmit={handleSubmit}>
                    {variant === AllowedVariants.Worker ? (
                        <>
                            <label htmlFor="nick">Nick:</label>
                            <input id="nick" type="text" name="nick" required/>
                        </>
                    ) : (
                        <>
                            <label htmlFor="email">E-mail:</label>
                            <input id="email" type="email" name="email" required/>
                        </>
                    )
                    }
                    <label htmlFor="password">Hasło:</label>
                    <input id="password" type="password" name="password" required/>
                    <input type="submit" value="Zaloguj"/>
                    <label>{message}</label>
                </form>
            </main>
        </>
    );
}

export default Login;