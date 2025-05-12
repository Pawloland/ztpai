export function decimalToInt( decimalString: string):number {
    return decimalString.split(".").reduce((acc, item, index) => acc + (index === 0 ? parseInt(item) * 100 : parseInt(item)), 0)
}

export function IntToDecimal(int: number, VATint: number): string {
    return (int * (100 + VATint) / (100 * 100)).toFixed(2)
}