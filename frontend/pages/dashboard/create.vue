<template>
  <v-container class="fill-height d-flex align-center justify-center">
    <v-card class="pa-6" max-width="600" width="100%">
      <v-card-title>Create a Product</v-card-title>

      <v-form @submit.prevent="createProduct">
        <v-text-field v-model="name" label="Name" required />
        <v-text-field v-model="qty" label="Qty" required />
        <v-text-field v-model="price" label="Price" required />
        <v-text-field v-model="description" label="Description" required />

        <v-btn color="primary" type="submit">Save Product</v-btn>
      </v-form>

      <v-alert
        v-if="errorMessage"
        type="error"
        class="mt-4"
      >{{ errorMessage }}</v-alert>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()

const name = ref('')
const qty = ref<number | null>(null)
const price = ref<number | null>(null)
const description = ref('')
const errorMessage = ref('')

const createProduct = async () => {
  try {
    const token = localStorage.getItem('token')
    await $fetch(`${config.public.apiBase}/products`, {
      method: 'POST',
      body: {
        name: name.value,
        qty: qty.value,
        price: price.value,
        description: description.value,
      },
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    navigateTo('/dashboard/crud')
  } catch (err: any) {
    errorMessage.value = err?.data?.message || 'Failed to create product'
  }
}
</script>