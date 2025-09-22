<script setup lang="ts">
import { h, resolveComponent, ref } from "vue";
import type { TableColumn } from "@nuxt/ui";
import { useMutation, useQuery, useQueryClient } from "@tanstack/vue-query";
import { productApi } from "~/services/api/product";

const UBadge = resolveComponent("UBadge");
const UButton = resolveComponent("UButton");
const UModal = resolveComponent("UModal");
const UFormField = resolveComponent("UFormField");
const UInput = resolveComponent("UInput");

type Product = {
  id: number | string;
  sku: string;
  name: string;
  price: number;
  reference?: string;
};

const {
  data: data,
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
const toast = useToast();
const deleteProductMutation = useMutation({
  mutationFn: async (productId: number | string) => {
    return await productApi.remove(+productId);
  },
  onSuccess: () => {
    refetch();

    toast.add({
      title: "Produk berhasil dihapus",
      description: "Data produk sudah dihapus",
    });
  },
  onError: (error) => {
    console.error("Gagal menghapus produk:", error);
    toast.add({
      title: "Gagal menghapus produk",
      description: "Terjadi kesalahan saat menghapus produk",
    });
  },
});

const showConfirmModal = ref(false);
const itemToDelete = ref<number | null>(null);

function handleDelete(id: number) {
  itemToDelete.value = +id;
  console.log(itemToDelete.value);
  showConfirmModal.value = true;
}

function cancelDelete() {
  showConfirmModal.value = false;
  itemToDelete.value = null;
}
function doDelete(id: number) {
  console.log(id);
  if (id === 0) {
    console.warn("ID tidak valid");
    cancelDelete();
    return;
  }
  deleteProductMutation.mutate(+id);
  cancelDelete();
}

const showEditModal = ref(false);
const currentEdit = ref<Product | null>(null);

function handleEdit(id: number) {
  const prod = data.value.find((p: Product) => p.id === id);
  if (prod) {
    currentEdit.value = { ...prod };
    showEditModal.value = true;
  }
}
const queryClient = useQueryClient();
const editProductMutation = useMutation({
  mutationFn: async (variables: { id: number; data: Partial<Product> }) => {
    return await productApi.update(variables.id, variables.data);
  },
  onSuccess: (response, variables) => {
    queryClient.invalidateQueries({ queryKey: ["products"] });

    toast.add({
      title: "Produk berhasil diperbarui",
      description: "Data produk sudah diperbarui",
    });

    showEditModal.value = false;
  },
  onError: (error) => {
    console.error("Gagal mengedit produk:", error);
    toast.add({
      title: "Gagal mengedit produk",
      description: "Terjadi kesalahan saat menyimpan perubahan",
    });
  },
});
function cancelEdit() {
  showEditModal.value = false;
  currentEdit.value = null;
}

function saveEdit() {
  if (!currentEdit.value) {
    toast.add({
      title: "Tidak ada produk yang diedit",
      description: "Silakan pilih produk terlebih dahulu",
    });
    return;
  }

  editProductMutation.mutate({
    id: +currentEdit.value.id,
    data: {
      sku: currentEdit.value.sku,
      name: currentEdit.value.name,
      price: currentEdit.value.price,
      reference: currentEdit.value.reference,
    },
  });
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
          <UButton color="error" @click="doDelete(itemToDelete || 0)"
            >Hapus</UButton
          >
        </div>
      </template>
    </UModal>
  </UContainer>
</template>
