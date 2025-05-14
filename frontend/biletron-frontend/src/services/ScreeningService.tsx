import {Screening, ScreeningResponse} from "../types/Screening.tsx";

export const fetchScreenings = async (): Promise<Screening[]> => {
    try {
        const response = await fetch(`/api/screenings?order[start_time]=asc&start_time[strictly_after]=${new Date().toISOString()}`)
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

export const fetchScreeningsForMovie = async (id_movie: number): Promise<Screening[]> => {
    try {
        const response = await fetch(`/api/screenings?order[start_time]=asc&start_time[strictly_after]=${new Date().toISOString()}&movie.id_movie=${id_movie}`)
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

export const deleteScreeningById = async (id: number): Promise<boolean> => {
    try {
        const response = await fetch(`/api/screenings/${id}`, {
            method: 'DELETE'
        })
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        return true
    } catch (err) {
        console.error('Error deleting screening:', err)
        return false
    }
}

