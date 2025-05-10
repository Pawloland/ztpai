export interface HallPartial {
    id_hall: number;
    hall_name: string;
}

export interface Hall extends HallPartial {

}

export interface HallResponse {
    member: Hall[];
}