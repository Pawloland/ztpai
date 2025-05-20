import {ScreeningType, ScreeningTypeResponse} from "../types/ScreeningType.tsx";

export const fetchScreeningTypes = async (): Promise<ScreeningType[]> => {
    try {
        const response = await fetch('/api/screening_types')
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        const data: ScreeningTypeResponse = await response.json()
        return data.member
    } catch (err) {
        //console.error('Error fetching screening types:', err)
        return []
    }
}
