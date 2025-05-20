import {ClientSession, ClientSessionResponse} from "../types/ClientSession.tsx";

export const fetchClientSessions = async (): Promise<ClientSession[]> => {
    try {
        const response = await fetch('/api/client_sessions')
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        const data: ClientSessionResponse = await response.json()
        return data.member
    } catch (err) {
        //console.error('Error fetching client sessions:', err)
        return []
    }
}
