// tetap nama file sama

import axios from "axios";
import type { AxiosInstance } from "axios";
import { useRuntimeConfig } from "#app";

// ubah export default instance jadi export fungsi yang membuat instance
export function getAxiosInstance(): AxiosInstance {
  const config = useRuntimeConfig();
  return axios.create({
    baseURL: config.public.apiUrl,
    timeout: 10000,
  });
}
