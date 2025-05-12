export interface DiscountPartial {
}

export interface Discount extends DiscountPartial {
    discount_name: string;
    amount: string; // decimal string
}

export interface DiscountResponse {
    member: Discount[];
}