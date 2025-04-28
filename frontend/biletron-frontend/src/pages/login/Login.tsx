import Header, {HeaderLink} from "../../components/header/Header.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";
import {AllowedIconClass} from "../../components/icon/Icon.tsx";
import {useEffect} from "react";

export enum AllowedVariants {
    Worker = "worker",
    Client = "client",
}

function Login({variant}: { variant: AllowedVariants }) {

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

    useEffect(() => {
        document.title = "LOGIN PAGE";
    }, []);

    return (
        <>
            <Header
                title="Logowanie"
                links={links}/>
            <main>
                {variant}
                login
                password
            </main>
        </>
    );
}

export default Login;