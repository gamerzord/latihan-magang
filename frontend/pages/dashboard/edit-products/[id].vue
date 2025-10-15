<template>
  <v-container class="fill-height d-flex align-center justify-center">
    <v-card class="pa-6" max-width="600" width="100%">
      <v-card-title>Edit Product</v-card-title>
      <v-alert v-if="successMessage" type="success" class="mb-4">
        {{ successMessage }}
      </v-alert>
      <v-alert v-if="errorMessage" type="error" class="mb-4">
        {{ errorMessage }}
      </v-alert>
      <v-form v-if="product" @submit.prevent="updateProduct">
        <v-text-field 
          v-model="product.name" 
          label="Name"
          :disabled="loading"
        />
        <v-text-field 
          v-model="product.qty" 
          label="Qty" 
          type="number"
          :disabled="loading"
        />
        <v-text-field 
          v-model="product.price" 
          label="Price" 
          type="number"
          :disabled="loading"
        />
        <v-text-field 
          v-model="product.description" 
          label="Description"
          :disabled="loading"
        />
        <v-btn 
          color="primary" 
          type="submit"
          :loading="loading"
          :disabled="loading"
        >
          Update Product
        </v-btn>
      </v-form>
      <v-alert v-else type="info">Loading product data...</v-alert>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
import type { Product } from '~/types/models'

const route = useRoute()
const id = route.params.id
const config = useRuntimeConfig()
const successMessage = ref('')
const errorMessage = ref('')
const loading = ref(false)

const token = localStorage.getItem('admin_auth_token')

const { data: product, pending, refresh } = await useFetch<Product>(
  `${config.public.apiBase}/products/${id}`,
  {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  }
)

const updateProduct = async () => {
  if (!product.value) return

  loading.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/products/${id}`, {
      method: 'PUT',
      body: product.value,
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    successMessage.value = 'Product updated successfully!'
    setTimeout(() => navigateTo('/dashboard/crud'), 1500)
  } catch (err: any) {
    console.error('Update failed:', err)
    errorMessage.value = err?.data?.message || 'Failed to update product'
  } finally {
    loading.value = false
  }
}
</script>