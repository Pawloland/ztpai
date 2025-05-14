import {SeatExpandedPartial} from "./Seat.tsx";

export interface ReservationFromBulk {
    id_reservation: number;
    seat: SeatExpandedPartial;
}

export interface ReservationFromBulkResponse {
    reservation: ReservationFromBulk[];
}
