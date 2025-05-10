export interface ScreeningTypePartial {
    id_screening_type: number;
    screening_name: string;
}

export interface ScreeningType extends ScreeningTypePartial {
    price: string;
}

export interface ScreeningTypeResponse {
    member: ScreeningType[];
}