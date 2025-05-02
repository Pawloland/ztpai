import styles from "./Register.module.css";
import Header from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import {FormEvent, useEffect, useState} from "react";
import {useNavigate} from "react-router";


function Register() {
    const [message, setMessage] = useState('');
    const navigate = useNavigate();

    const action = '/api' + AllowedRoutes.Register;
    useEffect(() => {
        document.title = "REGISTER PAGE";
    }, []);

    const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(event.currentTarget);

        const payload: Record<string, any> = {};
        formData.forEach((value, key) => {
            payload[key] = value;
        });

        // Check if passwords match and are at least 8 characters long
        if (payload.password !== payload.password_rep) {
            setMessage('Hasła nie są takie same');
            return;
        } else if (payload.password.length < 8) {
            setMessage('Hasło musi mieć co najmniej 8 znaków');
            return;
        }

        payload.pop('password_rep'); // Remove the password_rep field from the payload

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
                setMessage('Zarejestrowano pomyślnie!');
                setTimeout(() => {
                    navigate(AllowedRoutes.Home);
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
                title="Rejestracja"
                links={[{
                    route: AllowedRoutes.Home,
                    iconClass: AllowedIconClass.Home,
                    text: 'Strona główna',
                }, {
                    route: AllowedRoutes.Login,
                    iconClass: AllowedIconClass.Pen,
                    text: 'Zaloguj',
                }]}/>
            <main className={styles._}>
                <form className={styles.auth} onSubmit={handleSubmit}>
                    <label htmlFor="email">E-mail:</label>
                    <input id="email" type="email" name="email" required/>
                    <label htmlFor="password">Hasło:</label>
                    <input id="password" type="password" name="password" minLength={8} required/>
                    <label htmlFor="password_rep">Powtórz hasło:</label>
                    <input id="password_rep" type="password" name="password_rep" minLength={8} required/>
                    <input type="submit" value="Zarejestruj"/>
                    <label>{message}</label>
                </form>
            </main>
        </>
    );
}

export default Register;