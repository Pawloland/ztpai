import {Screening, ScreeningResponse} from "../types/Screening.tsx";

export const fetchScreenings = async (): Promise<Screening[]> => {
    try {
        const response = await fetch('/api/screenings?order[start_time]=asc')
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        let data: ScreeningResponse = await response.json()
        return data.member
    } catch (err) {
        console.error('Error fetching screenings:', err)
        return []
    }
};
