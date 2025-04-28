import styles from "./Icon.module.css";
import {Link} from "react-router";
import {AllowedRoutes} from "../../types/Routes.ts";


export enum AllowedIconClass {
    Logout = 'icon-logout',
    Pen = 'icon-pen',
    Hamburger = 'icon-hamburger',
    Home = 'icon-home',
}


function Icon({href, iconClass, text, onClick}: { href: AllowedRoutes; iconClass: AllowedIconClass; text: string; onClick?: () => void }) {
    return (
        <Link to={href} className={styles._} onClick={(e) => {
            if (onClick) {
                e.preventDefault();
                onClick();
            }
        }}>
            <span className={`${styles.icon} ${styles[iconClass]}`}></span>
            {text}
        </Link>
    );
}

export default Icon;