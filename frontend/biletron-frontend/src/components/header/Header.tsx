import styles from './Header.module.css';
import {useRef} from 'react';
import Icon, {AllowedIconClass} from "../icon/Icon.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";


export interface HeaderLink {
    route: AllowedRoutes;
    iconClass: AllowedIconClass;
    text: string;
    onClick?: () => void
}

function Header({links, title, message}: { links?: HeaderLink[] | null, title?: string | null, message?: string | null }) {

    const navRef = useRef<HTMLUListElement>(null);

    const toogleActive = () => {
        if (navRef.current) {
            navRef.current.classList.toggle(styles.active);
        }
    }


    return (
        <header className={styles._}>
            <img src="/logo.png" alt="Biletron"/>
            {title && <h1>{title}</h1>}
            {message && message}


            <ul ref={navRef}>
                <li>
                    <Icon
                        href={AllowedRoutes.Hamburger}
                        iconClass={AllowedIconClass.Hamburger}
                        text=""
                        onClick={toogleActive}
                        className={styles.align_right}
                    />
                </li>
                {links?.map((link, index) => (
                    <li key={index}>
                        <Icon href={link.route} iconClass={link.iconClass} text={link.text} onClick={link.onClick}/>
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
