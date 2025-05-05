import styles from "./Icon.module.css";
import {Link} from "react-router";
import {AllowedRoutes} from "../../types/Routes.ts";


export enum AllowedIconClass {
    Logout = 'icon-logout',
    Pen = 'icon-pen',
    Hamburger = 'icon-hamburger',
    Home = 'icon-home',
    Bin = 'icon-bin',
}


function Icon({href, iconClass, text, onClick, className}: { href: AllowedRoutes; iconClass: AllowedIconClass; text: string; onClick?: () => void; className?: string }) {
    return (
        <Link to={href} className={`${styles._} ${className || ''}`} onClick={(e) => {
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