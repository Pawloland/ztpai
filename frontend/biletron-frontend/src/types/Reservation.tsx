import {ScreeningPartial} from "./Screening.tsx";
import {SeatPartial} from "./Seat.tsx";

export interface ReservationPartial {
}

export interface Reservation extends ReservationPartial {
    id_reservation: number;
    screening: ScreeningPartial;
    seat: SeatPartial
}

export interface ReservationResponse {
    member: Reservation[];
}