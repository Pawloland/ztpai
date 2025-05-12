import {HallPartial} from "./Hall.tsx";
import {ScreeningType, ScreeningTypePartial} from "./ScreeningType.tsx";
import {MoviePartial} from "./Movie.ts";

export interface ScreeningPartial {
    id_screening: number;
}

export interface Screening extends ScreeningPartial {
    start_time: string;
    hall: HallPartial;
    movie: MoviePartial;
    screeningType: ScreeningType;
}

export interface ScreeningExpanded {
    start_time: string;
    hall: HallPartial;
    movie: MoviePartial;
    screeningType: ScreeningTypePartial;
}

export interface ScreeningResponse {
    member: Screening[];
}