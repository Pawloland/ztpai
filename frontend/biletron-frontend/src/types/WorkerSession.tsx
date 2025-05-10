import {WorkerPartial} from "./Worker.tsx";

export interface WorkerSessionPartial {
    id_session_worker: number;
}

export interface WorkerSession extends WorkerSessionPartial {
    expiration_date: string;
    worker: WorkerPartial
}

export interface WorkerSessionResponse {
    member: WorkerSession[];
}