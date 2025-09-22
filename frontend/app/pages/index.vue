<script setup lang="ts">
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
        :key="product?.id"
        class="hover:shadow-xl transition-shadow flex flex-col"
      >
        <template #header>
          <img
            src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=600&q=80"
            :alt="product?.name"
            class="h-48 w-full object-cover rounded-t-xl"
          />
        </template>

        <template #default>
          <h2 class="text-lg font-semibold mb-2 text-center">
            {{ product?.name }}
          </h2>
          <p class="text-gray-500 mb-4 text-center">SKU: {{ product?.sku }}</p>
          <p class="text-primary-600 font-bold text-xl text-center">
            Rp {{ product?.price }}
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
