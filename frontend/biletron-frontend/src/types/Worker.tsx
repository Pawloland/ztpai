import {WorkerTypePartial} from "./WorkerType.tsx";

export interface WorkerPartial {
    id_worker: number;
    nick: string;
}

export interface Worker extends WorkerPartial {
    worker_name: string;
    worker_surname: string;
    workerType: WorkerTypePartial;
}

export interface WorkerResponse {
    member: Worker[];
}