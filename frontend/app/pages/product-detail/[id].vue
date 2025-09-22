<script setup lang="ts">
import { useRoute } from "#app";
import { ref } from "vue";

const route = useRoute();
const productId = String(route.params.id);

// Data produk
const product = ref({
  id: productId,
  name: "Contoh Produk",
  price: 500_000,
  description:
    "Deskripsi singkat produk. Bisa diisi informasi lengkap seperti spesifikasi, bahan, atau keunggulan.",
  imageUrl: "/images/sample-product.jpg",
});

// State modal & metode pembayaran
const isPaymentModalOpen = ref(false);
const selectedMethod = ref<string>("indomaret"); // default

const paymentMethods = [
  {
    label: "Indomaret",
    value: "indomaret",
    description: "Bayar di gerai Indomaret",
  },
  { label: "QRIS", value: "qris", description: "Bayar dengan QRIS" },
  {
    label: "GoPay",
    value: "gopay",
    description: "Bayar via dompet digital GoPay",
  },
];

// Buka modal
function openModal() {
  isPaymentModalOpen.value = true;
}

// Konfirmasi pilihan
function confirmPayment() {
  if (!selectedMethod.value) {
    window.alert("Silakan pilih metode pembayaran terlebih dahulu.");
    return;
  }
  window.alert(`Metode dipilih: ${selectedMethod.value}`);
  isPaymentModalOpen.value = false;
}
</script>

<template>
  <UContainer class="py-8">
    <UCard class="w-full max-w-5xl mx-auto p-0 overflow-hidden">
      <img
        :src="product.imageUrl"
        :alt="product.name"
        class="w-full object-cover max-h-[500px]"
      />

      <div class="p-6">
        <h1 class="text-3xl font-bold mb-3">{{ product.name }}</h1>
        <p class="text-2xl font-semibold text-green-600 mb-4">
          Rp {{ product.price.toLocaleString("id-ID") }}
        </p>
        <p class="text-gray-700 leading-relaxed mb-6">
          {{ product.description }}
        </p>

        <!-- Tombol buka modal -->
        <UButton color="primary" @click="openModal">Beli Sekarang</UButton>

        <!-- Modal pilihan pembayaran -->
        <UModal
          v-model:open="isPaymentModalOpen"
          title="Pilih Metode Pembayaran"
        >
          <template #body>
            <URadioGroup
              v-model="selectedMethod"
              :items="paymentMethods"
              color="primary"
              variant="table"
            />
          </template>
          <template #footer>
            <UButton color="primary" block @click="confirmPayment">
              Konfirmasi
            </UButton>
          </template>
        </UModal>
      </div>
    </UCard>
  </UContainer>
</template>
