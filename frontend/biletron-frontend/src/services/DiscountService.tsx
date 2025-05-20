import {Discount} from "../types/Discount.tsx";

export const fetchDiscount = async (discount_name: string): Promise<Discount> => {
    try {
        const response = await fetch(`/api/discounts/${discount_name}`)
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        return await response.json() as Discount
    } catch (err) {
        //console.error('Error fetching discount:', err)
        throw err;
    }
}
