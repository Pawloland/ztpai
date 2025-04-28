export function getCookieURIEncodedJSONAsObject(cookieName: string): object | null {
    return document.cookie.split('; ').reduce((acc: Record<string, object | null>, cookie) => {
        const [key, value] = cookie.split('=');
        try {
            acc[key] = JSON.parse(decodeURIComponent(value));
        } catch (e) {
            acc[key] = null;
        }
        return acc;
    }, {})[cookieName] || null;
}
