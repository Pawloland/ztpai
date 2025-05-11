export function formatDate(localDate: Date): string {
    const year = localDate.toLocaleDateString(undefined, {year: 'numeric'});
    const month = localDate.toLocaleDateString(undefined, {month: '2-digit'});
    const day = localDate.toLocaleDateString(undefined, {day: '2-digit'});
    return `${year}-${month}-${day}`;
}

export function formatWeekDay(localDate: Date): string {
    return localDate.toLocaleDateString(undefined, {weekday: "long"}).replace(/^./, c => c.toUpperCase()) // Capitalize first letter;
}

export function formatTime(localTime: Date): string {
    const hour = localTime.toLocaleTimeString(undefined, {hour: '2-digit', hour12: false});
    const minute = localTime.toLocaleTimeString(undefined, {minute: '2-digit'});
    const second = localTime.toLocaleTimeString(undefined, {second: '2-digit'});
    return `${hour}:${minute}:${second}`;
}

function __formatDateTime(localeDateTime: Date): string {
    const date = formatDate(localeDateTime)
    const time = formatTime(localeDateTime);
    return `${date} ${time}`;
}

export function formatDateTimeNoSubSecond(dateTimeString: string): string {
    const localDate = new Date(dateTimeString);
    return __formatDateTime(localDate);
}


export function formatDateTime(dateTimeString: string): string {
    const localDate = new Date(dateTimeString);
    const dateTimeNoSubSecond = __formatDateTime(localDate);
    const sub_second = dateTimeString.split(".")[1]?.split("+")[0]
    return `${dateTimeNoSubSecond}.${sub_second}`;
}

