<script setup lang="ts">
import { h, resolveComponent, ref } from "vue";
import type { TableColumn } from "@nuxt/ui";

const UBadge = resolveComponent("UBadge");
const UButton = resolveComponent("UButton");
const UModal = resolveComponent("UModal");
const UFormField = resolveComponent("UFormField");
const UInput = resolveComponent("UInput");

type Product = {
  id: number;
  sku: string;
  name: string;
  price: number;
  reference: string;
  created_at: string;
  updated_at: string;
};

const data = ref<Product[]>([
  {
    id: 1,
    sku: "SKU-001",
    name: "Produk A",
    price: 120000,
    reference: "TRIPAY-REF-001",
    created_at: "2025-09-21 10:00:00",
    updated_at: "2025-09-21 12:00:00",
  },
  {
    id: 2,
    sku: "SKU-002",
    name: "Produk B",
    price: 200000,
    reference: "TRIPAY-REF-002",
    created_at: "2025-09-20 09:00:00",
    updated_at: "2025-09-20 11:30:00",
  },
]);

// Modal konfirmasi hapus
const showConfirmModal = ref(false);
const itemToDelete = ref<number | null>(null);

function handleDelete(id: number) {
  itemToDelete.value = id;
  showConfirmModal.value = true;
}

function cancelDelete() {
  showConfirmModal.value = false;
  itemToDelete.value = null;
}

function doDelete() {
  if (itemToDelete.value !== null) {
    data.value = data.value.filter((p) => p.id !== itemToDelete.value);
  }
  cancelDelete();
}

// Modal edit
const showEditModal = ref(false);
const currentEdit = ref<Product | null>(null);

function handleEdit(id: number) {
  const prod = data.value.find((p) => p.id === id);
  if (prod) {
    currentEdit.value = { ...prod };
    showEditModal.value = true;
  }
}

function cancelEdit() {
  showEditModal.value = false;
  currentEdit.value = null;
}

function saveEdit() {
  if (currentEdit.value) {
    const idx = data.value.findIndex((p) => p.id === currentEdit.value!.id);
    if (idx !== -1) {
      data.value[idx] = { ...currentEdit.value };
    }
  }
  cancelEdit();
}

const columns: TableColumn<Product>[] = [
  {
    accessorKey: "id",
    header: "Id",
    cell: ({ row }) => `${row.getValue("id")}`,
  },
  {
    accessorKey: "sku",
    header: "SKU",
    cell: ({ row }) =>
      h("span", { class: "font-mono text-xs" }, row.getValue("sku")),
  },
  {
    accessorKey: "name",
    header: "Nama Produk",
  },
  {
    accessorKey: "price",
    header: () => h("div", { class: "text-right" }, "Harga"),
    cell: ({ row }) => {
      const amount = Number(row.getValue("price"));
      const formatted = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
      }).format(amount);
      return h(
        "div",
        { class: "text-right font-medium text-green-600" },
        formatted
      );
    },
  },
  {
    accessorKey: "reference",
    header: "Reference",
    cell: ({ row }) =>
      h("span", { class: "text-xs text-gray-400" }, row.getValue("reference")),
  },
  {
    accessorKey: "action",
    header: "Action",
    cell: ({ row }) => {
      return h("div", { class: "flex gap-2" }, [
        h(
          UButton,
          {
            size: "sm",
            color: "primary",
            onClick: () => handleEdit(row.getValue("id")),
          },
          "Edit"
        ),
        h(
          UButton,
          {
            size: "sm",
            color: "error",
            variant: "outline",
            onClick: () => handleDelete(row.getValue("id")),
          },
          "Hapus"
        ),
      ]);
    },
  },
];
</script>

<template>
  <UContainer class="py-8">
    <ModalAddProduct />
    <UCard class="max-w-6xl mx-auto space-y-4">
      <template #header>
        <h1 class="text-2xl font-bold">Halaman Admin Produk</h1>
      </template>

      <UTable :data="data" :columns="columns" class="text-sm" />
    </UCard>

    <!-- Modal Edit Product -->
    <UModal v-model:open="showEditModal" title="Edit Produk">
      <template #body>
        <div v-if="currentEdit" class="space-y-4 px-4 pb-2">
          <UFormField label="SKU" name="sku" required>
            <UInput v-model="currentEdit!.sku" placeholder="SKU" />
          </UFormField>
          <UFormField label="Nama Produk" name="name" required>
            <UInput v-model="currentEdit!.name" placeholder="Nama Produk" />
          </UFormField>
          <UFormField label="Harga (Rp)" name="price" required>
            <UInput
              v-model="currentEdit!.price"
              type="number"
              min="0"
              placeholder="Harga"
            />
          </UFormField>
          <UFormField label="Reference" name="reference" required>
            <UInput v-model="currentEdit!.reference" placeholder="Reference" />
          </UFormField>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-3 px-4 py-2">
          <UButton variant="outline" color="neutral" @click="cancelEdit">
            Batal
          </UButton>
          <UButton color="primary" @click="saveEdit">Simpan</UButton>
        </div>
      </template>
    </UModal>

    <!-- Modal Konfirmasi Hapus -->
    <UModal v-model:open="showConfirmModal" title="Konfirmasi Hapus">
      <template #body>
        <div class="p-4">
          Apakah Anda yakin ingin menghapus produk dengan ID
          {{ itemToDelete }}?
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-3 p-4">
          <UButton variant="outline" color="neutral" @click="cancelDelete">
            Batal
          </UButton>
          <UButton color="error" @click="doDelete">Hapus</UButton>
        </div>
      </template>
    </UModal>
  </UContainer>
</template>
