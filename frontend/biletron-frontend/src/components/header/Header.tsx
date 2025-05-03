import styles from './Header.module.css';
import {useRef, useState} from 'react';
import Icon, {AllowedIconClass} from "../icon/Icon.tsx";
import {AllowedRoutes} from "../../types/Routes.ts";


export interface HeaderLink {
    route: AllowedRoutes;
    iconClass: AllowedIconClass;
    text: string;
    onClick?: () => void
}


function Header({links, title}: { links?: HeaderLink[] | null, title?: string | null }) {
    const [c, setC] = useState<number>(0);

    const navRef = useRef<HTMLUListElement>(null);

    const toggleActive = () => {
        navRef.current?.classList.toggle(styles.active);
    };


    return (
        <header className={styles._}>
            <img src="/logo.png" alt="Logo" onClick={
                () => {
                    setC(c+1);
                    // Messages.showMessage(c.toString(),4000);
                }
            }/>
            {title && <h1>{title}</h1>}
            <ul ref={navRef}>
                <li>
                    <Icon
                        href={AllowedRoutes.Hamburger}
                        iconClass={AllowedIconClass.Hamburger}
                        text=""
                        onClick={toggleActive}
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
                        onClick={toggleActive}
                    />
                </li>
            </ul>
        </header>
    );
}
export default Header;
