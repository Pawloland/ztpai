import {Language, LanguageResponse} from "../types/Language.ts";

export const fetchLanguages = async (): Promise<Language[]> => {
    try {
        const response = await fetch('/api/languages')
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        const data: LanguageResponse = await response.json()
        return data.member
    } catch (err) {
        //console.error('Error fetching languages:', err)
        return []
    }
}
