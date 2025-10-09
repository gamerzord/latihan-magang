<template>
  <v-container class="fill-height d-flex align-center justify-center">
    <v-card class="pa-6" max-width="600" width="100%">
      <v-card-title>Register</v-card-title>
      <v-form @submit.prevent="register">
        <v-text-field v-model="name" label="Name" />
        <v-text-field v-model="email" label="Email" />
        <v-text-field v-model="password" label="Password" type="password" />
        <v-btn color="primary" type="submit">Register</v-btn>
      </v-form>
      <v-alert v-if="errorMessage" type="error" class="mb-4">
        {{ errorMessage }}
      </v-alert>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()
const name = ref('')
const email = ref('')
const password = ref('')
const errorMessage = ref('')

const register = async () => {
  errorMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/users`, {
      method: 'POST',
      body: { name: name.value, email: email.value, password: password.value },
    })
    navigateTo('/login')
  } catch (err: any) {
    console.error('Register Failed', err)
    errorMessage.value = err?.data?.message || 'Failed to register'
  }
}
</script>