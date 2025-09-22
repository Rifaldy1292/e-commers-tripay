<script setup lang="ts">
import { useRoute } from "#app";
import { useQuery } from "@tanstack/vue-query";
import { ref } from "vue";
import { productApi } from "~/services/api/product";
import { checkoutApi } from "~/services/api/checkout";

const route = useRoute();
const productId = String(route.params.id);
const isPaymentModalOpen = ref(false);
const selectedMethod = ref<string>("indomaret");
const isLoadingCheckout = ref(false);
const errorMessage = ref<string | null>(null);
const checkoutUrl = ref<string | null>(null);

const {
  data: product,
  isLoading,
  isError,
  refetch,
} = useQuery({
  queryKey: ["product", productId],
  queryFn: async () => {
    const res = await productApi.getById(+productId);
    return res.data;
  },
});

const paymentMethods = [
  {
    label: "Indomaret",
    value: "indomaret",
    description: "Bayar di gerai Indomaret",
  },
  // { label: "QRIS", value: "qris", description: "Bayar dengan QRIS" },
  // {
  //   label: "GoPay",
  //   value: "gopay",
  //   description: "Bayar via dompet digital GoPay",
  // },
];
const toast = useToast();

function openModal() {
  isPaymentModalOpen.value = true;
}

async function confirmPayment() {
  if (!selectedMethod.value) {
    window.alert("Silakan pilih metode pembayaran terlebih dahulu.");
    return;
  }
  if (!product.value) {
    window.alert("Produk tidak ditemukan.");
    return;
  }
  isLoadingCheckout.value = true;
  errorMessage.value = null;

  try {
    const response = await checkoutApi.createCheckout({
      productId: product.value.id,
      method: selectedMethod.value,

      buyerEmail: "mochamadrifkyrifaldy@mail.com",
      buyerPhone: "089514636994",
    });

    checkoutUrl.value = response.checkoutUrl ?? null;
    isPaymentModalOpen.value = false;

    if (checkoutUrl.value) {
      toast.add({
        title: "succes",
        description: "berhasil melakukan chechkout",
        color: "success",
      });
      setTimeout(() => {
        window.open(checkoutUrl.value as string, "_blank");
      }, 1000);
    } else {
      window.alert("Berhasil membuat invoice, tapi tidak ada link pembayaran.");
    }
  } catch (error: any) {
    errorMessage.value = error?.message ?? "Gagal membuat invoice pembayaran.";
    toast.add({
      title: "Error",
      description: "gagal melakukan checkout",
      color: "error",
    });
  } finally {
    isLoadingCheckout.value = false;
  }
}
</script>

<template>
  <UContainer class="py-8">
    <UCard class="w-full max-w-5xl mx-auto p-0 overflow-hidden">
      <img
        src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=600&q=80"
        :alt="product?.name"
        class="w-full object-cover max-h-[500px]"
      />

      <div class="p-6">
        <h1 class="text-3xl font-bold mb-3">
          <span v-if="isLoading">Memuat...</span>
          <span v-else-if="isError">Gagal mengambil produk</span>
          <span v-else>{{ product?.name }}</span>
        </h1>

        <p class="text-2xl font-semibold text-green-600 mb-4">
          Rp
          <span v-if="product?.price">
            {{ product.price.toLocaleString("id-ID") }}
          </span>
        </p>
        <p class="text-gray-700 leading-relaxed mb-6">
          Segera beli produk ini sebelum kehabisan, mumpung sedang diskon, besok
          akan kembali harga normal.
        </p>

        <UButton
          color="primary"
          @click="openModal"
          :disabled="isLoading || isError"
        >
          Beli Sekarang
        </UButton>

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
            <div v-if="errorMessage" class="text-red-600 mt-2">
              {{ errorMessage }}
            </div>
            <div v-if="isLoadingCheckout" class="text-blue-600 mt-2">
              Memproses pembayaran...
            </div>
          </template>
          <template #footer>
            <UButton
              :loading="isLoadingCheckout"
              color="primary"
              block
              @click="confirmPayment"
            >
              Konfirmasi
            </UButton>
          </template>
        </UModal>
      </div>
    </UCard>
  </UContainer>
</template>
