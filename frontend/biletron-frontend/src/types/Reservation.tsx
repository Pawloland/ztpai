import {ScreeningExpanded, ScreeningPartial} from "./Screening.tsx";
import {SeatExpandedPartial, SeatPartial} from "./Seat.tsx";
import {ClientPartial} from "./Client.tsx";

export interface ReservationPartial {
}

export interface Reservation extends ReservationPartial {
    id_reservation: number;
    screening: ScreeningPartial;
    seat: SeatPartial;
}

export interface ReservationExpanded {
    id_reservation: number;
    total_price_brutto: string;
    screening: ScreeningExpanded;
    client: ClientPartial;
    seat: SeatExpandedPartial;
}

export interface ReservationResponse {
    member: Reservation[];
}

export interface ReservationExpandedResponse {
    member: ReservationExpanded[];
}