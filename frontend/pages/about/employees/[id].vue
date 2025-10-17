<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="8" offset-md="2">
        <v-card>
          <v-card-title class="d-flex justify-space-between align-center">
            <span class="text-h5">Employee Details</span>
            <div>
              <v-btn
                color="primary"
                class="mr-2"
                @click="navigateTo(`/about/employees/edit/${id}`)"
              >
                <v-icon left>mdi-pencil</v-icon>
                Edit
              </v-btn>
              <v-btn variant="outlined" @click="navigateTo('/about/employee')">
                Back
              </v-btn>
            </div>
          </v-card-title>

          <!-- Error State -->
          <v-card-text v-if="errorMessage">
            <v-alert type="error" class="mb-4">
              {{ errorMessage }}
            </v-alert>
            <v-btn @click="refresh">Retry</v-btn>
          </v-card-text>

          <!-- Loading State -->
          <v-card-text v-else-if="loading">
            <div class="d-flex justify-center pa-8">
              <v-progress-circular indeterminate color="primary" />
            </div>
          </v-card-text>

          <!-- Success State -->
          <v-card-text v-else-if="employee">
            <v-row>
              <v-col cols="12" md="6">
                <div class="mb-4">
                  <div class="text-caption text-grey">Employee Code</div>
                  <div class="text-h6">{{ employee.employee_code }}</div>
                </div>
              </v-col>

              <v-col cols="12" md="6">
                <div class="mb-4">
                  <div class="text-caption text-grey">Status</div>
                  <v-chip :color="getStatusColor(employee.status)" class="mt-1">
                    {{ employee.status }}
                  </v-chip>
                </div>
              </v-col>

              <v-col cols="12" md="6">
                <div class="mb-4">
                  <div class="text-caption text-grey">Full Name</div>
                  <div class="text-h6">{{ employee.name }}</div>
                </div>
              </v-col>

              <v-col cols="12" md="6">
                <div class="mb-4">
                  <div class="text-caption text-grey">Email</div>
                  <div class="text-body-1">{{ employee.email }}</div>
                </div>
              </v-col>

              <v-col cols="12" md="6">
                <div class="mb-4">
                  <div class="text-caption text-grey">Phone</div>
                  <div class="text-body-1">{{ employee.phone || '-' }}</div>
                </div>
              </v-col>

              <v-col cols="12" md="6">
                <div class="mb-4">
                  <div class="text-caption text-grey">Join Date</div>
                  <div class="text-body-1">
                    {{ formatDate(employee.join_date) }}
                  </div>
                </div>
              </v-col>

              <v-col cols="12" md="6">
                <div class="mb-4">
                  <div class="text-caption text-grey">Division</div>
                  <v-chip color="blue" class="mt-1">
                    {{ employee.division?.name || employee.division_id || '-' }}
                  </v-chip>
                </div>
              </v-col>

              <v-col cols="12" md="6">
                <div class="mb-4">
                  <div class="text-caption text-grey">Position</div>
                  <v-chip color="green" class="mt-1">
                    {{ employee.position?.name || employee.position_id || '-' }}
                  </v-chip>
                </div>
              </v-col>

              <v-col cols="12">
                <div class="mb-4">
                  <div class="text-caption text-grey">Address</div>
                  <div class="text-body-1">{{ employee.address || '-' }}</div>
                </div>
              </v-col>
            </v-row>

            <v-divider class="my-4" />

            <v-row>
              <v-col cols="12" md="6">
                <div class="text-caption text-grey">Created At</div>
                <div class="text-body-2">
                  {{ formatDateTime(employee.created_at) }}
                </div>
              </v-col>

              <v-col cols="12" md="6">
                <div class="text-caption text-grey">Last Updated</div>
                <div class="text-body-2">
                  {{ formatDateTime(employee.updated_at) }}
                </div>
              </v-col>
            </v-row>
          </v-card-text>

          <!-- No data state -->
          <v-card-text v-else>
            <v-alert type="warning">
              No employee data found.
            </v-alert>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import type { Employee } from '~/types/models'

const route = useRoute()
const config = useRuntimeConfig()
const id = route.params.id
const loading = ref(true)
const errorMessage = ref('')

const { data, error, pending, refresh } = await useFetch<{ employee: Employee }>(
  `${config.public.apiBase}/employees/${id}`,
  {
    onResponseError({ response }) {
      errorMessage.value = `API Error: ${response.status} - ${response.statusText}`
      console.error('API Error:', response.status, response.statusText)
    }
  }
)

const employee = computed(() => data.value?.employee)
loading.value = pending.value

watch(error, (err) => {
  if (err) {
    console.error('Failed to load employee:', err)
    errorMessage.value = 'Failed to load employee data. Please try again.'
  }
})

const getStatusColor = (status: string) =>
  ({ active: 'success', inactive: 'warning', resigned: 'error' }[status] || 'grey')

const formatDate = (date: Date | string) => {
  const dateObj = new Date(date)
  if (isNaN(dateObj.getTime())) {
    return 'Invalid date'
  }
  return dateObj.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const formatDateTime = (date: Date | string) => {
  const dateObj = new Date(date)
  if (isNaN(dateObj.getTime())) {
    return 'Invalid date'
  }
  return dateObj.toLocaleString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>