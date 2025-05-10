export interface WorkerTypePartial {
    id_worker_type: number;
    type_name: string;
}

export interface WorkerType extends WorkerTypePartial {
}

export interface WorkerTypeResponse {
    member: WorkerType[];
}