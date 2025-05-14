import {Worker, WorkerResponse} from "../types/Worker.tsx";

export const fetchWorkers = async (): Promise<Worker[]> => {
    try {
        const response = await fetch('/api/workers')
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        const data: WorkerResponse = await response.json()
        return data.member
    } catch (err) {
        console.error('Error fetching workers:', err)
        return []
    }
}

export const deleteWorkerById = async (id: number): Promise<boolean> => {
    try {
        const response = await fetch(`/api/workers/${id}`, {
            method: 'DELETE'
        })
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        return true
    } catch (err) {
        console.error('Error deleting worker:', err)
        return false
    }
}
