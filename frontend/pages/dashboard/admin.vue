<template>
  <v-container class="fill-height d-flex align-center justify-center">
    <v-card class="pa-6" max-width="600" width="100%">
      <v-card-title>Admin Login</v-card-title>
      <v-form @submit.prevent="handleLogin">
        <v-text-field 
          v-model="email" 
          label="Admin Email"
          :disabled="loading"
        />
        <v-text-field 
          v-model="password" 
          label="Password" 
          type="password"
          :disabled="loading"
        />
        <div>
          <v-btn 
            color="deep-purple-accent-4"
            type="submit" 
            class="mr-2"
            :loading="loading"
            :disabled="loading"
          >
            Login as Admin
          </v-btn>
          <v-btn 
            to="/dashboard/login" 
            color="secondary"
            :disabled="loading"
          >
            Back to User Login
          </v-btn>
        </div>
      </v-form>
      <v-alert v-if="errorMessage" type="error" class="mt-4">
        {{ errorMessage }}
      </v-alert>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import type { LoginResponse } from '~/types/models'

const config = useRuntimeConfig()
const email = ref('')
const password = ref('')
const errorMessage = ref('')
const loading = ref(false)

const handleLogin = async () => {
  loading.value = true
  errorMessage.value = ''
  
  try {
    const data = await $fetch<LoginResponse>(`${config.public.apiBase}/login`, {
      method: 'POST',
      body: { 
        email: email.value, 
        password: password.value 
      },
    })
    
    if (data.token) {
      localStorage.setItem('auth_token', data.token)
      localStorage.setItem('user_role', data.user.role) 
    }

    await navigateTo('/')
  } catch (err: any) {
    console.error('Admin Login Failed', err)
    errorMessage.value = err?.data?.message || 'Invalid admin credentials'
  } finally {
    loading.value = false
  }
}
</script>