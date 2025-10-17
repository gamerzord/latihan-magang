<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <v-card>
          <v-card-title class="d-flex justify-space-between align-center">
            <span class="text-h5">Position Master</span>
            <v-alert v-if="successMessage" type="success" class="mb-4">{{ successMessage }}</v-alert>
            <v-alert v-if="errorMessage" type="error" class="mb-4">{{ errorMessage }}</v-alert>
            <v-btn color="primary" @click="navigateTo('/about/positions/create')">
              <v-icon left>mdi-plus</v-icon>
              Add Position
            </v-btn>
          </v-card-title>

          <v-card-text>
            <v-text-field
              v-model="search"
              label="Search positions..."
              prepend-inner-icon="mdi-magnify"
              variant="outlined"
              density="compact"
              clearable
              hide-details
              class="mb-4"
            />

            <v-data-table
              :items="positions || []"
              :headers="positionHeaders"
              :search="search"
              :loading="loadingPositions"
              class="elevation-1"
            >
              <template #item.level="{ item }">
                <v-chip :color="getLevelColor(item.level)" size="small">
                  {{ item.level }}
                </v-chip>
              </template>

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
                <v-btn
                  icon="mdi-pencil"
                  size="small"
                  variant="text"
                  color="primary"
                  @click="navigateTo(`/about/positions/edit/${item.id}`)"
                />

          <v-btn 
            variant="text"
            color="error" 
            @click="deletePosition(item.id)"
          >
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
import type { Position } from '~/types/models'

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

const positionHeaders = [
  { title: 'Code', key: 'code', sortable: true },
  { title: 'Name', key: 'name', sortable: true },
  { title: 'Level', key: 'level', sortable: true },
  { title: 'Description', key: 'description', sortable: false },
  { title: 'Status', key: 'is_active', sortable: true },
  { title: 'Employees', key: 'employees_count', sortable: true },
  { title: 'Actions', key: 'actions', sortable: false}
]

const getLevelColor = (level: string) => (
  {
    staff: 'blue',
    supervisor: 'green',
    manager: 'orange',
    director: 'purple',
    executive: 'red'
  }
  [level] || 'grey'
)

const {
  data: positionsData,
  pending: loadingPositions,
  error: fetchError,
  refresh: refreshPositions
} = await useFetch<{ positions: Position[] }>(`${config.public.apiBase}/positions`)

const positions = computed(() => positionsData.value?.positions || [])

const deletePosition = async (id: number) => {
  loading.value = true
  successMessage.value = ''
  errorMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/positions/${id}`, {
      method: 'DELETE',
      
    })
    successMessage.value = 'Position deleted successfully'
    refreshPositions()
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
    errorMessage.value = 'Failed to fetch positions'
  }
})
</script>