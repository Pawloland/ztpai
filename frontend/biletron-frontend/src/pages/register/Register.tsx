import Header from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import {useEffect} from "react";


function Register() {

    useEffect(() => {
        document.title = "REGISTER PAGE";
    }, []);
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
            <main>
                Register Page
            </main>
        </>
    );
}

export default Register;