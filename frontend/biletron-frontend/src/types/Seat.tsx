import {HallPartial} from "./Hall.tsx";
import {SeatTypeExpandedPartial, SeatTypePartial} from "./SeatType.tsx";

export interface SeatPartial {
    id_seat: number;
}

export interface SeatExpandedPartial extends SeatPartial {
    row: string;
    number: number;
    seatType: SeatTypeExpandedPartial
}

export interface Seat extends SeatPartial {
    row: string;
    number: string;
    hall: HallPartial
    seatType: SeatTypePartial
}

export interface SeatResponse {
    member: Seat[];
}