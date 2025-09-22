import type { AxiosResponse } from "axios";
import { getAxiosInstance } from "~/services/axiosInstance";

export interface ProductPayload {
  sku: string;
  name: string;
  price: number;
  reference?: string;
}

export const productApi = {
  async getAll(): Promise<AxiosResponse<any>> {
    const instance = getAxiosInstance();
    return instance.get("/products");
  },

  async getById(productId: number): Promise<AxiosResponse<any>> {
    const instance = getAxiosInstance();
    return instance.get(`/products/${productId}`);
  },

  async create(data: ProductPayload): Promise<AxiosResponse<any>> {
    const instance = getAxiosInstance();
    return instance.post("/products", data);
  },

  async update(
    productId: number,
    data: Partial<ProductPayload>
  ): Promise<AxiosResponse<any>> {
    const instance = getAxiosInstance();
    return instance.put(`/products/${productId}`, data);
  },
  async remove(productId: number): Promise<AxiosResponse<any>> {
    const instance = getAxiosInstance();
    return instance.delete(`/products/${productId}`);
  },
};
