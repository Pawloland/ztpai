import styles from "./Poster.module.css";
import {Link} from 'react-router';
import {ElementType} from "react";
import {AllowedRoutes} from "../../types/Routes.ts";
import {Movie} from "../../types/Movie.ts";

function Poster({movie, poster = 'default', title = 'Missing title'}: { movie?: Movie, poster?: string; title?: string; }) {
    const Wrapper: ElementType = movie !== undefined ? Link : 'a';
    const wrapperProps = movie !== undefined
        ? {
            to: {
                pathname: AllowedRoutes.Reservation,
                search: `?id_movie=${movie.id_movie}`,
            },
            state: {movie},
        }
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
