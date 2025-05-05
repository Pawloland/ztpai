import stylesOther from "../icon/Icon.module.css";
import styles from "./BaseIcon.module.css"
import {AllowedIconClass} from "../icon/Icon.tsx";


function BaseIcon({iconClass, onClick}: { iconClass: AllowedIconClass; onClick?: () => void; className?: string }) {
    return (
        <span className={`${stylesOther.icon} ${stylesOther[iconClass]} ${styles.icon}`} onClick={onClick}></span>
    );
}

export default BaseIcon;