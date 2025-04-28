export enum AuthCookieName {
    Worker = 'auth_worker',
    Client = 'auth',
}

export interface AuthCookie {
    email: string;
}

export interface AuthWorkerCookie {
    nick: string;
}