<template>
  <v-container class="fill-height d-flex align-center justify-center">
    <v-card class="pa-6" max-width="600" width="100%">
      <v-card-title>Edit User</v-card-title>
      <v-alert v-if="successMessage" type="success" class="mb-4">
        {{ successMessage }}
      </v-alert>
      <v-alert v-if="errorMessage" type="error" class="mb-4">
        {{ errorMessage }}
      </v-alert>
      <v-form v-if="user" @submit.prevent="updateUser">
        <v-text-field v-model="user.name" label="Name" />
        <v-text-field v-model="user.email" label="Email" />
        <v-text-field v-model="user.password" label="Password" type="password" />
        <v-btn color="primary" type="submit">Update User</v-btn>
      </v-form>
      <v-alert v-else type="info">Loading user data...</v-alert>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
import type { User } from '~/types/models'

const route = useRoute()
const id = route.params.id
const config = useRuntimeConfig()
const successMessage = ref('')
const errorMessage = ref('')
const loading = ref(false)

const token = localStorage.getItem('token')

const { data: user, pending, refresh } = await useFetch<User>(
  `${config.public.apiBase}/users/${id}`,
  {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  }
)

const updateUser = async () => {
  
  loading.value = true
  errorMessage.value = ''
  successMessage.value = ''

  if (!user.value) return

  try {
    await $fetch(`${config.public.apiBase}/users/${id}`, {
      method: 'PUT',
      body: user.value,
      headers: { 
        Authorization: `Bearer ${token}` 
      },
    })
    
    successMessage.value = 'User updated successfully!'
    setTimeout(() => navigateTo('/dashboard'), 1000)
  } catch (err: any) {
    console.error('Update Failed', err)
    errorMessage.value = err?.data?.message || 'Failed to update user'
  }
}
</script>