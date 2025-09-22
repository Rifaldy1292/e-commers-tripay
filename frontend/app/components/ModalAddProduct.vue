<script setup lang="ts">
import { ref } from "vue";
import { useMutation, useQueryClient } from "@tanstack/vue-query";
import { productApi } from "~/services/api/product";

const isAddModalOpen = ref(false);
const toast = useToast();
const newProduct = ref({
  sku: "",
  name: "",
  price: 0,
  reference: "",
  created_at: "",
  updated_at: "",
});

function resetForm() {
  newProduct.value = {
    sku: "",
    name: "",
    price: 0,
    reference: "",
    created_at: "",
    updated_at: "",
  };
}

function openAddModal() {
  resetForm();
  isAddModalOpen.value = true;
}

function closeAddModal() {
  isAddModalOpen.value = false;
}

const queryClient = useQueryClient();

const addProductMutation = useMutation({
  mutationFn: async () => {
    return await productApi.create({
      sku: newProduct.value.sku,
      name: newProduct.value.name,
      price: newProduct.value.price,
      reference: newProduct.value.reference,
    });
  },
  onSuccess: () => {
    queryClient.invalidateQueries({ queryKey: ["products"] });

    toast.add({
      title: "Produk berhasil ditambahkan",
      description: "Data sudah tersimpan ke database",
    });

    closeAddModal();
  },
  onError: (error) => {
    console.error("Gagal menambah produk:", error);

    toast.add({
      title: "Gagal menambah produk",
      description: "Terjadi kesalahan saat menyimpan data",
    });
  },
});

function submitNewProduct() {
  if (!newProduct.value.name || !newProduct.value.sku) {
    alert("Nama dan SKU wajib diisi");
    return;
  }
  addProductMutation.mutate();
}
</script>

<template>
  <div class="w-full mx-auto max-w-7xl">
    <div class="flex justify-end mb-4">
      <UButton color="primary" size="md" @click="openAddModal">
        Tambah Produk
      </UButton>
    </div>

    <UModal
      v-model:open="isAddModalOpen"
      title="Tambah Produk Baru"
      :ui="{ content: 'sm:max-w-lg w-full px-6 py-4' }"
    >
      <template #body>
        <div class="space-y-4 grid md:grid-cols-2">
          <UFormField label="SKU" name="sku" required>
            <UInput
              v-model="newProduct.sku"
              placeholder="Contoh: SKU-003"
              size="md"
              variant="outline"
            />
          </UFormField>

          <UFormField label="Nama Produk" name="name" required>
            <UInput
              v-model="newProduct.name"
              placeholder="Contoh: Produk C"
              size="md"
              variant="outline"
            />
          </UFormField>

          <UFormField label="Harga (Rp)" name="price" required>
            <UInput
              v-model="newProduct.price"
              type="number"
              min="0"
              placeholder="150000"
              size="md"
              variant="outline"
            />
          </UFormField>

          <UFormField label="Reference" name="reference" required>
            <UInput
              v-model="newProduct.reference"
              placeholder="Contoh: TRIPAY-REF-003"
              size="md"
              variant="outline"
            />
          </UFormField>
        </div>
      </template>

      <template #footer>
        <div class="flex justify-end gap-3 mt-4">
          <UButton color="primary" size="md" @click="submitNewProduct">
            Simpan
          </UButton>
          <UButton
            color="neutral"
            variant="outline"
            size="md"
            @click="closeAddModal"
          >
            Batal
          </UButton>
        </div>
      </template>
    </UModal>
  </div>
</template>
