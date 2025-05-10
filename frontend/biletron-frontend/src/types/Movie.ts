import {Language} from "./Language.ts";


export interface MoviePartial {
    id_movie: number;
    title: string;
}

export interface Movie extends MoviePartial{
    original_title: string;
    duration: string;
    description: string;
    poster: string;
    languageViaIdLanguage: Language;
    languageViaIdDubbing: Language | null | undefined;
    languageViaIdSubtitles: Language | null | undefined;
}

export interface MoviesResponse {
    member: Movie[];
}