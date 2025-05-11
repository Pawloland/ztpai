import {Seat, SeatResponse} from "../types/Seat.tsx";

export const fetchSeatsForHalls = async (id_hall: number): Promise<Seat[]> => {
    try {
        const response = await fetch(`/api/seats?hall.id_hall=${id_hall}`)
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        const data: SeatResponse = await response.json()
        return data.member
    } catch (err) {
        console.error('Error fetching seats:', err)
        return []
    }
}
