<template>
  <v-container>
    <v-card class="pa-6">
      <v-card-title class="d-flex justify-space-between align-center">
        <span class="text-h5">Employees</span>
        <v-btn color="primary" @click="navigateTo('/employees/create')">
          <v-icon left>mdi-plus</v-icon>
          Add Employee
        </v-btn>
      </v-card-title>

      <v-alert v-if="successMessage" type="success" class="mb-4">
        {{ successMessage }}
      </v-alert>
      <v-alert v-if="errorMessage" type="error" class="mb-4">
        {{ errorMessage }}
      </v-alert>

      <!-- Filters -->
      <v-row class="mb-4">
        <v-col cols="12" md="4">
          <v-text-field
            v-model="filters.search"
            label="Search..."
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            density="compact"
            clearable
            hide-details
          />
        </v-col>

        <v-col cols="12" md="3">
          <v-select
            v-model="filters.division_id"
            label="Division"
            :items="divisions || []"
            item-title="name"
            item-value="id"
            variant="outlined"
            density="compact"
            clearable
            hide-details
          />
        </v-col>

        <v-col cols="12" md="3">
          <v-select
            v-model="filters.position_id"
            label="Position"
            :items="positions || []"
            item-title="name"
            item-value="id"
            variant="outlined"
            density="compact"
            clearable
            hide-details
          />
        </v-col>

        <v-col cols="12" md="2">
          <v-select
            v-model="filters.status"
            label="Status"
            :items="statusOptions"
            variant="outlined"
            density="compact"
            clearable
            hide-details
          />
        </v-col>
      </v-row>

      <v-data-table
        :items="employees || []"
        :headers="headers"
        :loading="loading"
        class="elevation-1"
      >
        <template #item.division_id="{ item }">
          {{ getDivisionName(item.division_id) }}
        </template>

        <template #item.position_id="{ item }">
          {{ getPositionName(item.position_id) }}
        </template>

        <template #item.status="{ item }">
          <v-chip :color="getStatusColor(item.status)" size="small">
            {{ item.status }}
          </v-chip>
        </template>

        <template #item.actions="{ item }">
          <v-btn variant="text" color="info" @click="navigateTo(`/employees/${item.id}`)">
            View
          </v-btn>
          <v-btn variant="text" color="primary" @click="navigateTo(`/employees/edit/${item.id}`)">
            Edit
          </v-btn>
          <v-btn variant="text" color="error" @click="deleteEmployee(item.id)">
            Delete
          </v-btn>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import type { Employee, Division, Position } from '~/types/models'

const token = localStorage.getItem('admin_auth_token')
const config = useRuntimeConfig()

const successMessage = ref('')
const errorMessage = ref('')
const loading = ref(false)

const filters = ref({
  search: '',
  division_id: '',
  position_id: '',
  status: ''
})

const employees = ref<Employee[]>([])
const divisions = ref<Division[]>([])
const positions = ref<Position[]>([])

const getDivisionName = (id: number | null) => {
  const found = divisions.value?.find(d => d.id === id)
  return found ? found.name : '-'
}

const getPositionName = (id: number | null) => {
  const found = positions.value?.find(p => p.id === id)
  return found ? found.name : '-'
}

const statusOptions = ['active', 'inactive', 'resigned']

const headers = [
  { title: 'Code', key: 'employee_code' },
  { title: 'Name', key: 'name' },
  { title: 'Email', key: 'email' },
  { title: 'Division', key: 'division_id' },
  { title: 'Position', key: 'position_id' },
  { title: 'Join Date', key: 'join_date' },
  { title: 'Status', key: 'status' },
  { title: 'Actions', key: 'actions' }
]

const getStatusColor = (status: string) =>
  ({ active: 'success', inactive: 'warning', resigned: 'error' }[status] || 'grey')

const getQuery = () => {
  const params = new URLSearchParams()
  if (filters.value.search) params.append('search', filters.value.search)
  if (filters.value.division_id) params.append('division_id', filters.value.division_id)
  if (filters.value.position_id) params.append('position_id', filters.value.position_id)
  if (filters.value.status) params.append('status', filters.value.status)
  return params.toString()
}

const fetchEmployees = async () => {
  loading.value = true
  errorMessage.value = ''
  try {
    const data = await $fetch<{ data: Employee[] }>(
      `${config.public.apiBase}/employees?${getQuery()}`,
      { headers: { Authorization: `Bearer ${token}` } }
    )
    employees.value = data.data || []
  } catch (err: any) {
    console.error('Error fetching employees:', err)
    errorMessage.value = err?.data?.message || 'Failed to fetch employees'
  } finally {
    loading.value = false
  }
}

const fetchDivisions = async () => {
  try {
    const data = await $fetch<{ data: Division[] }>(
      `${config.public.apiBase}/divisions-active`,
      { headers: { Authorization: `Bearer ${token}` } }
    )
    divisions.value = data.data || []
  } catch (e) {
    console.error('Failed to fetch divisions:', e)
  }
}

const fetchPositions = async () => {
  try {
    const data = await $fetch<{ data: Position[] }>(
      `${config.public.apiBase}/positions-active`,
      { headers: { Authorization: `Bearer ${token}` } }
    )
    positions.value = data.data || []
  } catch (e) {
    console.error('Failed to fetch positions:', e)
  }
}

const deleteEmployee = async (id: number) => {
  loading.value = true
  errorMessage.value = ''
  try {
    await $fetch(`${config.public.apiBase}/employees/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${token}` }
    })
    successMessage.value = 'Employee deleted successfully'
    fetchEmployees()
  } catch (err: any) {
    console.error('Delete failed:', err)
    errorMessage.value = err?.data?.message || 'Delete Failure'
  } finally {
    loading.value = false
  }
}

watch(filters, () => {
  clearTimeout((watch as any)._t)
  ;(watch as any)._t = setTimeout(fetchEmployees, 300)
}, { deep: true })

onMounted(() => {
  fetchDivisions()
  fetchPositions()
  fetchEmployees()
})
</script>