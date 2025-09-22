import type { AxiosResponse } from "axios";
import { getAxiosInstance } from "~/services/axiosInstance";

export interface Invoice {
  id: number;
  product_id: number;
  tripay_reference: string;
  buyer_email: string;
  buyer_phone: string;
  raw_response: any;
}

export const invoiceApi = {
  getAll(): Promise<AxiosResponse<Invoice[]>> {
    const instance = getAxiosInstance();
    return instance.get("/invoices");
  },

  getById(invoiceId: number): Promise<AxiosResponse<Invoice>> {
    const instance = getAxiosInstance();
    return instance.get(`/invoices/${invoiceId}`);
  },
};
