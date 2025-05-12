import {Reservation, ReservationExpanded, ReservationExpandedResponse, ReservationResponse} from "../types/Reservation.tsx";

export const fetchReservations = async (): Promise<Reservation[]|ReservationExpanded[]> => {
    try {
        const response = await fetch(`/api/reservations`)
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        let data: ReservationResponse = await response.json()
        return data.member
    } catch (err) {
        console.error('Error fetching reservations:', err)
        return []
    }
}

export const fetchReservationsForScreening = async (id_screening: number): Promise<Reservation[]|ReservationExpanded[]> => {
    try {
        const response = await fetch(`/api/reservations?screening.id_screening=${id_screening}`)
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        let data: ReservationExpandedResponse = await response.json()
        return data.member
    } catch (err) {
        console.error('Error fetching reservations:', err)
        return []
    }
};



