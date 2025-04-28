import styles from './Header.module.css';
import {useEffect, useRef, useState} from 'react';
import {getCookieURIEncodedJSONAsObject} from "../../utils/cookies.tsx";
import {AuthCookie} from "../../types/AuthCookie.ts";
import Icon, {AllowedIconClass} from "../icon/Icon.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";


export interface HeaderLink {
    route: AllowedRoutes;
    iconClass: AllowedIconClass;
    text: string;
}

function Header({links, title, message}: { links?: HeaderLink[] | null, title?: string | null, message?: string | null }) {
    const [authEmail, setAuthEmail] = useState<string | null>(null);
    // const [message, setMessage] = useState<string | null>(null);
    const navRef = useRef<HTMLUListElement>(null);

    const toogleActive = () => {
        if (navRef.current) {
            navRef.current.classList.toggle(styles.active);
        }
    }

    useEffect(() => {
        // Parse cookies to find "auth"
        const auth_cookie = getCookieURIEncodedJSONAsObject("auth") as AuthCookie | null;

        console.log('Auth cookie:', auth_cookie);
        if (auth_cookie) {
            try {
                setAuthEmail(auth_cookie.email || null);
            } catch (error) {
                console.error('Invalid auth cookie');
            }
        }
    }, []);

    return (
        <header className={styles._}>
            <img src="/logo.png" alt="Biletron" width="100" height="100"/>
            {title && <h1>{title}</h1>}
            {message && message}


            <ul ref={navRef}>
                {links?.map((link, index) => (
                    <li key={index}>
                        <Icon href={link.route} iconClass={link.iconClass} text={link.text}/>
                    </li>
                ))}
            </ul>

            <ul>
                <li>
                    <Icon
                        href={AllowedRoutes.Hamburger}
                        iconClass={AllowedIconClass.Hamburger}
                        text=""
                        onClick={toogleActive}
                    />
                </li>
            </ul>
        </header>
    );
}

export default Header;
