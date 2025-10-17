<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <v-card>
          <v-card-title class="d-flex justify-space-between align-center">
            <span class="text-h5">Division Master</span>
            <v-alert v-if="successMessage" type="success" class="mb-4">{{ successMessage }}</v-alert>
            <v-alert v-if="errorMessage" type="error" class="mb-4">{{ errorMessage }}</v-alert>
            <v-btn color="primary" @click="navigateTo('/about/divisions/create')">
              <v-icon left>mdi-plus</v-icon>
              Add Division
            </v-btn>
          </v-card-title>

          <v-card-text>
            <v-text-field
              v-model="search"
              label="Search divisions..."
              prepend-inner-icon="mdi-magnify"
              variant="outlined"
              density="compact"
              clearable
              hide-details
              class="mb-4"
            />

            <v-data-table
              :headers="headers"
              :items="divisions || []"
              :search="search"
              :loading="loadingDivisions"
              class="elevation-1"
            >
              <template #item.is_active="{ item }">
                <v-chip :color="item.is_active ? 'success' : 'error'" size="small">
                  {{ item.is_active ? 'Active' : 'Inactive' }}
                </v-chip>
              </template>

              <template #item.employees_count="{ item }">
                <v-chip size="small" color="info">
                  {{ item.employees_count }} employees
                </v-chip>
              </template>
              
              <template #item.actions="{ item }">
                <v-btn icon="mdi-pencil" size="small" variant="text" color="primary" @click="navigateTo(`/about/divisions/edit/${item.id}`)">
                </v-btn>
                <v-btn variant="text" color="error" @click="deleteDivision(item.id)">
                    Delete
                </v-btn>
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import type { Division } from '~/types/models'

if (import.meta.server) {
  onMounted(() => {
    const token = useCookie('admin-auth-token').value
    if (!token) return navigateTo('/dashboard/admin')
  })
}

const config = useRuntimeConfig()

const successMessage = ref('')
const errorMessage = ref('')
const search = ref('')
const loading = ref(false)

const headers = [
  { title: 'Code', key: 'code', sortable: true },
  { title: 'Name', key: 'name', sortable: true },
  { title: 'Description', key: 'description', sortable: false },
  { title: 'Status', key: 'is_active', sortable: true },
  { title: 'Employees', key: 'employees_count', sortable: true },
  { title: 'Actions', key: 'actions', sortable: false}
]

const {
  data: divisionsData,
  pending: loadingDivisions,
  error: fetchError,
  refresh: refreshDivisions
} = await useFetch<{ divisions: Division[] }>(`${config.public.apiBase}/divisions`)

const divisions = computed(() => divisionsData.value?.divisions || [])

const deleteDivision = async (id: number) => {
  loading.value = true
  successMessage.value = ''
  errorMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/divisions/${id}`, {
      method: 'DELETE',
      
    })
    successMessage.value = 'Division deleted successfully'
    refreshDivisions()
  } catch (err: any) {
    console.error('Delete failed:', err)
    errorMessage.value = err?.data?.message || 'Delete Failure'
  } finally {
    loading.value = false
  }
}

watch(fetchError, (err) => {
  if (err) {
    console.error('Fetch error:', err)
    errorMessage.value = 'Failed to fetch divisions'
  }
})
</script>