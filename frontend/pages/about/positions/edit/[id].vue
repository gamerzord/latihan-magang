<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="8" offset-md="2">
        <v-card>
          <v-card-title class="text-h5">
            Update Position
          </v-card-title>

          <v-card-text>
            <v-form v-if="position" ref="form" @submit.prevent="submitForm">
              <v-text-field
                v-model="position.name"
                label="Position Name *"
                required
                variant="outlined"
                density="comfortable"
                class="mb-3"
              />

              <v-text-field
                v-model="position.code"
                label="Position Code *"
                required
                variant="outlined"
                density="comfortable"
                class="mb-3"
              />

              <v-select
                v-model="position.level"
                label="Level *"
                :items="levelOptions"
                required
                variant="outlined"
                density="comfortable"
                class="mb-3"
              />

              <v-textarea
                v-model="position.description"
                label="Description"
                variant="outlined"
                density="comfortable"
                rows="3"
                class="mb-3"
              />

              <v-switch
                v-model="position.is_active"
                label="Active Status"
                color="primary"
                class="mb-3"
              />

              <v-alert v-if="errorMessage" type="error" class="mb-3">
                {{ errorMessage }}</v-alert>
              
              <v-alert v-if="successMessage" type="success" class="mb-4">
                {{ successMessage }}</v-alert>

              <div class="d-flex gap-2">
                <v-btn
                  color="primary"
                  type="submit"
                  :loading="loading"
                >
                  Update
                </v-btn>
                <v-btn
                  variant="outlined"
                  @click="navigateTo('/about/position')"
                >
                  Cancel
                </v-btn>
              </div>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import type { Position } from "~/types/models";

if (import.meta.server) {
  onMounted(() => {
    const token = useCookie('admin-auth-token').value
    if (!token) return navigateTo('/dashboard/admin')
  })
}

const config = useRuntimeConfig();
const route = useRoute()
const loading = ref(false);
const id = route.params.id
const form = ref(null)
const errorMessage = ref('')
const successMessage = ref('')

const levelOptions = [
  { title: 'Staff', value: 'staff' },
  { title: 'Supervisor', value: 'supervisor' },
  { title: 'Manager', value: 'manager' },
  { title: 'Director', value: 'director' },
  { title: 'Executive', value: 'executive' }
]

const { data: positionsData } = await useFetch<{ position: Position }>(
  `${config.public.apiBase}/positions/${id}`,
)

const position = computed(() => positionsData.value?.position);

const submitForm = async () => {
  loading.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/positions/${id}`, {
      method: 'PUT',
      body: position.value,
      
    })
    successMessage.value = "position updated successfully!";
    setTimeout(() => navigateTo("/about/position"), 1000);
  } catch (err: any) {
    console.error("Create Failed", err);
    errorMessage.value = err.data?.message || "Failed to create position";
  } finally {
    loading.value = false
  }
}
</script>