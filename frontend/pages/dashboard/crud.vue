<template>
  <v-container>
    <v-card class="pa-6">
      <v-card-title>Simple CRUD</v-card-title>
      <v-alert v-if="successMessage" type="success" class="mb-4">
        {{ successMessage }}
      </v-alert>
      <v-alert v-if="errorMessage" type="error" class="mb-4">
        {{ errorMessage }}
      </v-alert>
      <v-row>
        <v-col cols="12" md="6">
          <h2>Products</h2>
          <v-data-table
            :items="products || []"
            :headers="productHeaders"
            class="elevation-1"
            :loading="pendingProducts"
          >
            <template #item.actions="{ item }">
              <v-btn
                variant="text"
                color="primary"
                @click="navigateTo(`/dashboard/edit-products/${item.id}`)"
              >
                Edit
              </v-btn>
              <v-btn
                variant="text"
                color="error"
                @click="deleteProduct(item.id)"
              >
                Delete
              </v-btn>
            </template>
          </v-data-table>

          <v-btn
            color="success"
            class="mt-4"
            @click="navigateTo('/dashboard/create')"
          >
            Create
          </v-btn>
        </v-col>

        <v-col cols="12" md="6">
          <h2>Users</h2>

          <v-data-table
            :items="users || []"
            :headers="userHeaders"
            class="elevation-1"
            :loading="pendingUsers"
          >
            <template #item.actions="{ item }">
              <v-btn
                variant="text"
                color="primary"
                @click="navigateTo(`/dashboard/edit-users/${item.id}`)"
              >
                Edit
              </v-btn>
              <v-btn
                variant="text"
                color="error"
                @click="deleteUser(item.id)"
              >
                Delete
              </v-btn>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
      <div class="mt-6">
        <v-btn to="/dashboard/register" color="primary" class="mr-2">Register</v-btn>
        <v-btn to="/dashboard/login" color="secondary" class="mr-2">Login</v-btn>
        <v-btn color="error" @click="logout">Logout</v-btn>
      </div>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
if (import.meta.server) {
  onMounted(() => {
    const token = useCookie('admin-auth-token').value
    if (!token) return navigateTo('/dashboard/admin')
  })
}

import type { Product, User } from '~/types/models'

const successMessage = ref('')
const config = useRuntimeConfig()
const loading = ref(false)
const errorMessage = ref('')

const { data: products, pending: pendingProducts, refresh: refreshProducts } =
  await useFetch<Product[]>(`${config.public.apiBase}/products`, {
    
  })

const { data: users, pending: pendingUsers, refresh: refreshUsers } =
  await useFetch<User[]>(`${config.public.apiBase}/users`, {
    
  })

const productHeaders = [
  { title: 'ID', key: 'id' },
  { title: 'Name', key: 'name' },
  { title: 'Qty', key: 'qty' },
  { title: 'Price', key: 'price' },
  { title: 'Description', key: 'description' },
  { title: 'Actions', key: 'actions' },
]
const userHeaders = [
  { title: 'ID', key: 'id' },
  { title: 'Name', key: 'name' },
  { title: 'Email', key: 'email' },
  { title: 'Password', key: 'password' },
  { title: 'Actions', key: 'actions' },
]

const deleteProduct = async (id: number) => {
  loading.value = true
  errorMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/products/${id}`, {
      method: 'DELETE',
      
    })
    successMessage.value = 'Product deleted successfully'
    refreshProducts()
  } catch (err: any) {
    console.error('Delete failed:', err)
    errorMessage.value = err?.data?.message || 'Delete Failure'
  } finally {
    loading.value = false
  }
}

const deleteUser = async (id: number) => {
  loading.value = true
  errorMessage.value =''

  try {
    await $fetch(`${config.public.apiBase}/users/${id}`, {
    method: 'DELETE',
    
  })
  successMessage.value = 'User deleted successfully'
  refreshUsers()
  } catch (err: any) {
    console.error('Delete failed:', err)
    errorMessage.value = err?.data?.message || 'Delete Failure'
  } finally {
    loading.value = false
  }
}

const logout = async () => {
  errorMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/logout`, {
      method: 'POST',
      
    })
  } catch (err: any) {
    console.error('Logout failed:', err)
    errorMessage.value = err?.data?.message || 'Logout Failure'
  } finally {
    await navigateTo('/dashboard/login')
  }
}

</script>