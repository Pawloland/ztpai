import {Reservation, ReservationExpanded, ReservationExpandedResponse, ReservationResponse} from "../types/Reservation.tsx";
import {ReservationFromBulk, ReservationFromBulkResponse} from "../types/BulkReservation.tsx";

/*
Depending on auth cookies it returns Reservation[] for clients or guest users, and ReservationExpanded[] for worker users
 */
export const fetchReservations = async (): Promise<Reservation[] | ReservationExpanded[]> => {
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

export const fetchReservationsForScreening = async (id_screening: number): Promise<Reservation[] | ReservationExpanded[]> => {
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

export const addBulkReservation = async (id_screening: number, id_seat: number[], discount_name?: string | null): Promise<ReservationFromBulk[]> => {
    try {
        const response = await fetch(`/api/bulk_reservations`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/ld+json',
            },
            body: JSON.stringify({
                id_screening: id_screening,
                id_seat: id_seat,
                discount_name: discount_name,
            }),
        })
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        return (await response.json() as ReservationFromBulkResponse).reservation
    } catch (err) {
        console.error('Error adding reservation:', err)
        throw err;
    }
}

export const deleteReservationById = async (id: number): Promise<boolean> => {
    try {
        const response = await fetch(`/api/reservations/${id}`, {
            method: 'DELETE'
        })
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        return true
    } catch (err) {
        console.error('Error deleting reservation:', err)
        return false
    }
}



