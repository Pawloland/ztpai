import {Language} from "./Language.ts";

export interface Movie {
    id_movie: number;
    title: string;
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