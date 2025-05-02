import styles from "./Poster.module.css";
import {Link} from 'react-router';
import {ElementType} from "react";
import {AllowedRoutes} from "../../types/Routes.ts";

function Poster({ID_Movie, poster = 'default', title = 'Missing title'}: { ID_Movie?: number; poster?: string; title?: string; }) {
    const Wrapper: ElementType = ID_Movie !== undefined ? Link : 'a';
    const wrapperProps = ID_Movie !== undefined
        ? {to: `${AllowedRoutes.SelectPlace}?ID_Movie=${ID_Movie}`}
        : {}; // no href at all

    return (
        <Wrapper {...wrapperProps} className={styles._}>
            <div
                className={styles.poster}
                style={{backgroundImage: `url('/uploads/posters/${poster}')`}}
            >
                <div className={styles.overlay}>{title}</div>
            </div>
        </Wrapper>
    );
}

export default Poster;
