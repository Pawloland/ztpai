import {Client, ClientResponse} from "../types/Client.tsx";

export const fetchClients = async (): Promise<Client[]> => {
    try {
        const response = await fetch('/api/clients')
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        const data: ClientResponse = await response.json()
        return data.member
    } catch (err) {
        console.error('Error fetching clients:', err)
        return []
    }
}
