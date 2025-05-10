import {ClientPartial} from "./Client.tsx";

export interface ClientSessionPartial {
    id_session_client: number;
}

export interface ClientSession extends ClientSessionPartial {
    expiration_date: string;
    client: ClientPartial
}

export interface ClientSessionResponse {
    member: ClientSession[];
}