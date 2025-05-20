import {WorkerSession, WorkerSessionResponse} from "../types/WorkerSession.tsx";

export const fetchWorkerSessions = async (): Promise<WorkerSession[]> => {
    try {
        const response = await fetch('/api/worker_sessions')
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        const data: WorkerSessionResponse = await response.json()
        return data.member
    } catch (err) {
        //console.error('Error fetching worker sessions:', err)
        return []
    }
}
