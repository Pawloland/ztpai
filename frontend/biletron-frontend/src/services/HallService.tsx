import {Hall, HallResponse} from "../types/Hall.tsx";

export const fetchHalls = async (): Promise<Hall[]> => {
    try {
        const response = await fetch('/api/halls')
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        const data: HallResponse = await response.json()
        return data.member
    } catch (err) {
        console.error('Error fetching halls:', err)
        return []
    }
}
