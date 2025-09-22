import axios from "axios";
import type { AxiosInstance } from "axios";
import { useRuntimeConfig } from "#app";

export function getAxiosInstance(): AxiosInstance {
  const config = useRuntimeConfig();
  return axios.create({
    baseURL: config.public.apiUrl,
    timeout: 10000,
  });
}
