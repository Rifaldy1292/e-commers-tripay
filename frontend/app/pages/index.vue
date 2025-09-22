<script setup lang="ts">
const productList = [
  {
    id: 1,
    name: "Keyboard Mechanical",
    sku: "KBM-001",
    price: 650000,
    imageUrl: "/images/keyboard.jpg",
  },
  {
    id: 2,
    name: "Mouse Wireless",
    sku: "MSW-002",
    price: 250000,
    imageUrl: "/images/mouse.jpg",
  },
  {
    id: 3,
    name: "Headset Gaming",
    sku: "HSG-003",
    price: 450000,
    imageUrl: "/images/headset.jpg",
  },
];
import { useQuery } from "@tanstack/vue-query";
import { productApi } from "../services/api/product";

const {
  data: products,
  isLoading,
  isError,
  refetch,
} = useQuery({
  queryKey: ["products"],
  queryFn: async () => {
    const res = await productApi.getAll();
    return res.data;
  },
});
</script>

<template>
  <UContainer class="py-10">
    <h1 class="text-3xl font-bold mb-8 text-center">Produk Kami</h1>

    <UGrid class="w-full grid md:grid-cols-3 gap-4">
      <UCard
        v-for="product in products"
        :key="product.id"
        class="hover:shadow-xl transition-shadow flex flex-col"
      >
        <template #header>
          <img
            :src="product.imageUrl"
            :alt="product.name"
            class="h-48 w-full object-cover rounded-t-xl"
          />
        </template>

        <template #default>
          <h2 class="text-lg font-semibold mb-2 text-center">
            {{ product.name }}
          </h2>
          <p class="text-gray-500 mb-4 text-center">SKU: {{ product.sku }}</p>
          <p class="text-primary-600 font-bold text-xl text-center">
            Rp {{ product.price.toLocaleString("id-ID") }}
          </p>
        </template>

        <template #footer>
          <UButton color="primary" block :to="`/product-detail/${product.id}`"
            >Lihat Detail</UButton
          >
        </template>
      </UCard>
    </UGrid>
  </UContainer>
</template>
