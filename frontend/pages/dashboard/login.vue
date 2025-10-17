<template>
  <v-container class="fill-height d-flex align-center justify-center">
    <v-card class="pa-6" max-width="600" width="100%">
      <v-card-title>Login</v-card-title>
      <v-form @submit.prevent="handleLogin">
        <v-text-field 
          v-model="email" 
          label="Email"
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
            color="primary" 
            type="submit" 
            class="mr-2"
            :loading="loading"
            :disabled="loading"
          >
            Login
          </v-btn>
          <v-btn 
            to="/dashboard/register" 
            color="secondary" 
            class="mr-2"
            :disabled="loading"
          >
            Register
          </v-btn>
          <v-btn 
            to="/dashboard/admin" 
            color="deep-purple-accent-4"
            :disabled="loading"
          >
            Admin Login
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

const config = useRuntimeConfig()
const email = ref('')
const password = ref('')
const errorMessage = ref('')
const loading = ref(false)



const handleLogin = async () => {
  loading.value = true
  errorMessage.value = ''

  try {
    await $fetch('https://localhost/sanctum/csrf-cookie', {
      
    })
    await $fetch(`${config.public.apiBase}/login`, {
      method: 'POST',
      body: {
        email: email.value,
        password: password.value
      },
      
    })

    await navigateTo('/')
  } catch (err: any) {
    console.error('Login failed:', err)
    errorMessage.value = err?.data?.message || 'Invalid login'
  } finally {
    loading.value = false
  }
}
</script>