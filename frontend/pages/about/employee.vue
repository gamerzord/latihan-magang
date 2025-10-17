<template>
  <v-container>
    <v-card class="pa-6">
      <v-card-title class="d-flex justify-space-between align-center">
        <span class="text-h5">Employees</span>
          <v-alert v-if="successMessage" type="success" class="mb-4">{{ successMessage }}</v-alert>
          <v-alert v-if="errorMessage" type="error" class="mb-4">{{ errorMessage }}</v-alert>
        <v-btn color="primary" @click="navigateTo('/about/employees/create')">
          <v-icon left>mdi-plus</v-icon>
          Add Employee
        </v-btn>
      </v-card-title>

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
        :loading="loadingEmployees"
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

        <template #item.join_date="{ item }">
          {{ formatDate(item.join_date) }}
        </template>

        <template #item.actions="{ item }">
          <v-btn variant="text" color="info" @click="navigateTo(`/about/employees/${item.id}`)">
            View
          </v-btn>
          <v-btn variant="text" color="primary" @click="navigateTo(`/about/employees/edit/${item.id}`)">
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
const loading = ref(false)

if (import.meta.server) {
  onMounted(() => {
    const token = useCookie('admin-auth-token').value
    if (!token) return navigateTo('/dashboard/admin')
  })
}

const config = useRuntimeConfig()

const successMessage = ref('')
const errorMessage = ref('')

const filters = ref({
  search: '',
  division_id: null,
  position_id: null,
  status: ''
})

const query = computed(() => {
  const params = new URLSearchParams()
  if (filters.value.search) params.append('search', filters.value.search)
  if (filters.value.division_id) params.append('division_id', filters.value.division_id)
  if (filters.value.position_id) params.append('position_id', filters.value.position_id)
  if (filters.value.status) params.append('status', filters.value.status)
  return params.toString()
})

const statusOptions = ['active', 'inactive', 'resigned']

const headers = [
  { title: 'Code', key: 'employee_code', sortable: true },
  { title: 'Name', key: 'name', sortable: true },
  { title: 'Email', key: 'email', sortable: true },
  { title: 'Division', key: 'division_id', sortable: false },
  { title: 'Position', key: 'position_id', sortable: false },
  { title: 'Join Date', key: 'join_date', sortable: true },
  { title: 'Status', key: 'status', sortable: true },
  { title: 'Actions', key: 'actions', sortable: false}
]

const getStatusColor = (status: string) =>
  ({ active: 'success', inactive: 'warning', resigned: 'error' }[status] || 'grey')

  const formatDate = (date: Date | string) => {
    const dateObj = new Date(date)

    if (isNaN(dateObj.getTime())) {
    return 'Invalid date'
  }

  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const {
  data: employeeResponse,
  pending: loadingEmployees,
  error: fetchError,
  refresh: refreshEmployees
} = await useFetch<{ employees: Employee[]} >(
  () => `${config.public.apiBase}/employees?${query.value}`,
  {
    
    watch: [query],
  }
)
const employees = computed(() => employeeResponse.value?.employees || [])

const { data: divisionsStatus } = await useFetch<{ divisions_active: Division[] }>(
  `${config.public.apiBase}/divisions-active`
);
const divisions = computed(() => divisionsStatus.value?.divisions_active || []);

const { data: positionsStatus } = await useFetch<{ positions_active: Position[] }>(
  `${config.public.apiBase}/positions-active`
);
const positions = computed(() => positionsStatus.value?.positions_active || []);

const getDivisionName = (id: number | null) => {
  const found = divisions.value?.find(d => d.id === id)
  return found ? found.name : '-'
}

const getPositionName = (id: number | null) => {
  const found = positions.value?.find(p => p.id === id)
  return found ? found.name : '-'
}

const deleteEmployee = async (id: number) => {
  loading.value = true
  successMessage.value = ''
  errorMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/employees/${id}`, {
      method: 'DELETE',
      
    })
    successMessage.value = 'Employee deleted successfully'
    refreshEmployees()
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
    errorMessage.value = 'Failed to fetch employees'
  }
})
</script>