export interface Language {
    id_language: number;
    language_name: string;
    code: string;
}

export interface LanguageResponse {
    member: Language[];
}
