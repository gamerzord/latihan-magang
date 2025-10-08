<template>
  <v-container class="fill-height d-flex align-center justify-center">
    <v-card class="pa-6" max-width="600" width="100%">
      <v-card-title>Login</v-card-title>
      <v-form @submit.prevent="login">
        <v-text-field v-model="email" label="Email" />
        <v-text-field v-model="password" label="Password" type="password" />
        <div>
          <v-btn color="primary" type="submit" class="mr-2">Login</v-btn>
          <v-btn to="/dashboard/register" color="secondary" class="mr-2">Register</v-btn>
        </div>
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
import type { LoginResponse } from '~/types/models'
const config = useRuntimeConfig()
const email = ref('')
const password = ref('')
const errorMessage = ref('')
const login = async () => {
  try {
    const data = await $fetch<LoginResponse>(`${config.public.apiBase}/login`, {
      method: 'POST',
      body: { email: email.value, password: password.value },
    })
    localStorage.setItem('token', data.token)
    navigateTo('/')
  } catch (err: any) {
    errorMessage.value = err?.data?.message || 'Invalid login'
  }
}
</script>