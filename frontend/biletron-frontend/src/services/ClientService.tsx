import {Client, ClientResponse} from "../types/Client.tsx";
import {AllowedRoutes} from "../types/Routes.ts";
import Messages from "../components/messages/Messages.tsx";
import {destroyCookie} from "../utils/cookies.tsx";
import {AuthCookieName} from "../types/AuthCookie.ts";

export const fetchClients = async (): Promise<Client[]> => {
    try {
        const response = await fetch('/api/clients')
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        const data: ClientResponse = await response.json()
        return data.member
    } catch (err) {
        //console.error('Error fetching clients:', err)
        return []
    }
}

export const logoutClient = async (): Promise<boolean> => {
    try {
        const res = await fetch('/api' + AllowedRoutes.Logout, {
            method: 'GET'
        });

        if (res.ok) {
            Messages.showMessage('Wylogowano pomyślnie!', 4000);
        } else {
            Messages.showMessage('Nie udało się wylogować, bo taka sesja nie istnieje', 4000);
        }

        destroyCookie(AuthCookieName.Client);
        return true;
    } catch (err) {
        //console.error('Error logging out:', err);
        Messages.showMessage('Wystąpił błąd podczas wylogowywania', 4000);
        destroyCookie(AuthCookieName.Client);
        return false;
    }
};

export const deleteClientById = async (id: number): Promise<boolean> => {
    try {
        const response = await fetch(`/api/clients/${id}`, {
            method: 'DELETE'
        })
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        return true
    } catch (err) {
        //console.error('Error deleting client:', err)
        return false
    }
}
