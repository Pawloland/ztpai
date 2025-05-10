export interface ClientPartial {
    id_client: number;
    mail: string;
}

export interface Client extends ClientPartial {
    client_name: string;
    client_surname: string;
    nick: string;
}

export interface ClientResponse {
    member: Client[];
}