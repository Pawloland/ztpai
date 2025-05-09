export interface ScreeningType {
    id_screening_type: number;
    screening_name: string;
    price: string;
}

export interface ScreeningTypeResponse {
    member: ScreeningType[];
}