import {HallPartial} from "./Hall.tsx";
import {ScreeningType} from "./ScreeningType.tsx";
import {MoviePartial} from "./Movie.ts";

export interface Screening {
    id_screening: number;
    start_time: string;
    hall: HallPartial;
    movie: MoviePartial;
    screeningType: ScreeningType;
}

export interface ScreeningResponse {
    member: Screening[];
}