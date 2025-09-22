import { getAxiosInstance } from "~/services/axiosInstance";

export interface CreateCheckoutPayload {
  productId: number;
  method: string;
  buyerEmail: string;
  buyerPhone: string;
  note?: string;
}

export interface CheckoutResponse {
  id: number;
  amount: number;
  status: string;
  tripayReference: string;
  checkoutUrl?: string;
  createdAt: string;
  updatedAt: string;
}

export const checkoutApi = {
  async createCheckout(
    payload: CreateCheckoutPayload
  ): Promise<CheckoutResponse> {
    const { data } = await getAxiosInstance().post("/checkout", payload);

    return (data as any)?.data ?? (data as unknown as CheckoutResponse);
  },

  async getCheckoutStatus(
    checkoutId: number | string
  ): Promise<CheckoutResponse> {
    const { data } = await getAxiosInstance().get(
      `/api/checkout/${checkoutId}`
    );
    return (data as any)?.data ?? (data as unknown as CheckoutResponse);
  },
};
