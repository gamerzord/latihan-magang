<template>
  <v-container class="fill-height d-flex align-center justify-center">
    <v-card class="pa-6" max-width="900" width="100%">
      <v-card-title class="text-h5">Update Employee</v-card-title>

      <v-form ref="form" @submit.prevent="submitForm">
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              v-model="formData.employee_code"
              label="Employee Code *"
              required
              variant="outlined"
            />
          </v-col>

          <v-col cols="12" md="6">
            <v-text-field
              v-model="formData.name"
              label="Full Name *"
              required
              variant="outlined"
            />
          </v-col>

          <v-col cols="12" md="6">
            <v-text-field
              v-model="formData.email"
              label="Email *"
              type="email"
              required
              variant="outlined"
            />
          </v-col>

          <v-col cols="12" md="6">
            <v-text-field
              v-model="formData.phone"
              label="Phone Number"
              variant="outlined"
            />
          </v-col>

          <v-col cols="12" md="6">
            <v-select
              v-model="formData.division_id"
              label="Division *"
              :items="divisions"
              item-title="name"
              item-value="id"
              required
              variant="outlined"
            />
          </v-col>

          <v-col cols="12" md="6">
            <v-select
              v-model="formData.position_id"
              label="Position *"
              :items="positions"
              item-title="name"
              item-value="id"
              required
              variant="outlined"
            />
          </v-col>

          <v-col cols="12" md="6">
            <v-select
              v-model="formData.status"
              label="Status *"
              :items="statusOptions"
              required
              variant="outlined"
            />
          </v-col>

          <v-col cols="12">
            <v-textarea
              v-model="formData.address"
              label="Address"
              variant="outlined"
              rows="3"
            />
          </v-col>
        </v-row>

        <v-alert v-if="successMessage" type="success" class="mb-4">
          {{ successMessage }}
        </v-alert>

        <v-alert v-if="errorMessage" type="error" class="mt-4">
          {{ errorMessage }}
        </v-alert>

        <div class="d-flex gap-2">
          <v-btn color="primary" type="submit" :loading="loading">Update</v-btn>
          <v-btn variant="outlined" @click="navigateTo('/about/employee')">Cancel</v-btn>
        </div>
      </v-form>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import type { Employee, Division, Position } from "~/types/models";

const route = useRoute();
const config = useRuntimeConfig();
const id = route.params.id;

const loading = ref(false);
const errorMessage = ref("");
const successMessage = ref("");

const formData = ref({
  employee_code: "",
  name: "",
  email: "",
  phone: "",
  division_id: null as number | null,
  position_id: null as number | null,
  status: "active" as "active" | "inactive" | "resigned",
  address: "",
});

const statusOptions = [
  { title: "Active", value: "active" },
  { title: "Inactive", value: "inactive" },
  { title: "Resigned", value: "resigned" },
];

const { data: divisionsStatus } = await useFetch<{ divisions_active: Division[] }>(
  `${config.public.apiBase}/divisions-active`
);
const divisions = computed(() => divisionsStatus.value?.divisions_active || []);

const { data: positionsStatus } = await useFetch<{ positions_active: Position[] }>(
  `${config.public.apiBase}/positions-active`
);
const positions = computed(() => positionsStatus.value?.positions_active || []);

const { data: employeeData, error: employeeError } =
  await useFetch<{ employee: Employee }>(
    `${config.public.apiBase}/employees/${id}`
  );

if (employeeError.value) {
  errorMessage.value = "Failed to load employee data.";
} else if (employeeData.value) {
  formData.value = employeeData.value.employee
}

const submitForm = async () => {
  loading.value = true;
  errorMessage.value = "";
  successMessage.value = "";

  try {
    await $fetch(`${config.public.apiBase}/employees/${id}`, {
      method: "PUT",
      body: formData.value,
    });
    successMessage.value = "Employee updated successfully!";
    setTimeout(() => navigateTo("/about/employee"), 1000);
  } catch (err: any) {
    console.error("Update failed:", err);
    errorMessage.value = err?.data?.message || "Failed to update employee.";
  } finally {
    loading.value = false;
  }
};
</script>
