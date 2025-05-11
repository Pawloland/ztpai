export interface SeatTypePartial {
    id_seat_type: number;
}

export interface SeatType extends SeatTypePartial {
    seat_name: string;
    price: string; //decimal in string format
}

export interface SeatTypeResponse {
    member: SeatType[];
}