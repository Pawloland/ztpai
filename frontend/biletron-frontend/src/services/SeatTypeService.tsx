import {SeatType, SeatTypeResponse} from "../types/SeatType.tsx";

export const fetchSeatTypes = async (): Promise<SeatType[]> => {
    try {
        const response = await fetch(`/api/seat_types`)
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        const data: SeatTypeResponse = await response.json()
        return data.member
    } catch (err) {
        //console.error('Error fetching seat types:', err)
        return []
    }
}
