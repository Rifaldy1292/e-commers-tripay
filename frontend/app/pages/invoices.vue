<script setup lang="ts">
import { h, resolveComponent, ref } from "vue";
import type { TableColumn } from "@nuxt/ui";

const UButton = resolveComponent("UButton");

type Invoice = {
  id: number;
  product_id: number;
  tripay_reference: string;
  buyer_email: string;
  buyer_phone: string;
  raw_response: string; // JSON disimpan sebagai string
};

const invoices = ref<Invoice[]>([
  {
    id: 1,
    product_id: 101,
    tripay_reference: "TRX-ABC-123",
    buyer_email: "customer1@example.com",
    buyer_phone: "08123456789",
    raw_response: '{"status":"paid","amount":100000}',
  },
  {
    id: 2,
    product_id: 102,
    tripay_reference: "TRX-DEF-456",
    buyer_email: "customer2@example.com",
    buyer_phone: "08198765432",
    raw_response: '{"status":"pending","amount":150000}',
  },
]);

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
        // kalau bukan string, ubah ke string agar tetap bisa tampil
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
