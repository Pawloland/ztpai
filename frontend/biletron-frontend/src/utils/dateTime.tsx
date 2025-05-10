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


export function formatDateTime(dateTimeString: string): string {
    const localDate = new Date(dateTimeString);
    const year = localDate.toLocaleDateString(undefined, {year: 'numeric'});
    const month = localDate.toLocaleDateString(undefined, {month: '2-digit'});
    const day = localDate.toLocaleDateString(undefined, {day: '2-digit'});
    const hour = localDate.toLocaleTimeString(undefined, {hour: '2-digit', hour12: false});
    const minute = localDate.toLocaleTimeString(undefined, {minute: '2-digit'});
    const second = localDate.toLocaleTimeString(undefined, {second: '2-digit'});
    const sub_second = dateTimeString.split(".")[1]?.split("+")[0]
    return `${year}-${month}-${day} ${hour}:${minute}:${second}.${sub_second}`;
}

