<script setup lang="ts">
import { h, resolveComponent, ref } from "vue";
import type { TableColumn } from "@nuxt/ui";
import { invoiceApi, type Invoice } from "~/services/api/invoices";
import { useQuery } from "@tanstack/vue-query";

const UButton = resolveComponent("UButton");

const {
  data: invoices,
  isLoading,
  isError,
  refetch,
} = useQuery({
  queryKey: ["invoices"],
  queryFn: async () => {
    const res = await invoiceApi.getAll();
    return res.data;
  },
});

const columns: TableColumn<Invoice>[] = [
  {
    accessorKey: "id",
    header: "ID",
    cell: ({ row }) => `${row.getValue("id")}`,
  },
  {
    accessorKey: "product_id",
    header: "Product ID",
    cell: ({ row }) => `${row.getValue("product_id")}`,
  },
  {
    accessorKey: "tripay_reference",
    header: "TriPay Reference",
    cell: ({ row }) => row.getValue("tripay_reference"),
  },
  {
    accessorKey: "buyer_email",
    header: "Buyer Email",
    cell: ({ row }) => row.getValue("buyer_email"),
  },
  {
    accessorKey: "buyer_phone",
    header: "Buyer Phone",
    cell: ({ row }) => row.getValue("buyer_phone"),
  },
  {
    accessorKey: "raw_response",
    header: "Raw Response",
    cell: ({ row }) => {
      const value = row.getValue("raw_response");
      let jsonString: string;

      if (typeof value === "string") {
        jsonString = value;
      } else {
        jsonString = JSON.stringify(value);
      }

      try {
        const obj = JSON.parse(jsonString);
        return JSON.stringify(obj, null, 2);
      } catch {
        return jsonString;
      }
    },
  },
];
</script>

<template>
  <UContainer class="py-8">
    <UCard class="max-w-6xl mx-auto">
      <template #header>
        <h1 class="text-2xl font-bold">Daftar Invoices</h1>
      </template>
      <UTable :data="invoices" :columns="columns" class="flex-1 text-sm" />
    </UCard>
  </UContainer>
</template>
