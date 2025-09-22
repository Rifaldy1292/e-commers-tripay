<template>
  <UContainer class="flex items-center justify-between py-4">
    <NuxtLink to="/" class="font-bold text-xl">tripay-store</NuxtLink>

    <nav class="flex gap-6">
      <NuxtLink to="/" class="hover:underline">Home</NuxtLink>
      <NuxtLink to="/invoices" class="hover:underline">Invoices</NuxtLink>
      <NuxtLink to="/admin-products" class="hover:underline"
        >Panel Admin</NuxtLink
      >
    </nav>
  </UContainer>
  <slot />

  <UFooter class="mt-10 text-center text-gray-500">
    © {{ new Date().getFullYear() }} tripay-store. All rights reserved.
  </UFooter>
</template>
