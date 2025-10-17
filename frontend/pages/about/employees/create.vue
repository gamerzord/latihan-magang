<template>
  <v-container class="fill-height">
    <v-row class="fill-height" align="center" justify="center">
      <v-col cols="12" md="8" lg="6">
        <v-card class="pa-6">
          <v-card-title class="text-h5">Create Employee</v-card-title>

          <v-form ref="form" @submit.prevent="submitForm">
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.employee_code"
                  label="Employee Code *"
                  variant="outlined"
                  required
                />
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.name"
                  label="Full Name *"
                  variant="outlined"
                  required
                />
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.email"
                  label="Email *"
                  type="email"
                  variant="outlined"
                  required
                />
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.phone"
                  label="Phone Number *"
                  variant="outlined"
                  required
                />
              </v-col>
              <v-col cols="12" md="6">
                <v-select
                  v-model="formData.division_id"
                  label="Division *"
                  :items="divisions"
                  item-title="name"
                  item-value="id"
                  variant="outlined"
                  required
                />
              </v-col>
              <v-col cols="12" md="6">
                <v-select
                  v-model="formData.position_id"
                  label="Position *"
                  :items="positions"
                  item-title="name"
                  item-value="id"
                  variant="outlined"
                  required
                />
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field
                  v-model="formData.join_date"
                  label="Join Date *"
                  type="date"
                  variant="outlined"
                  required
                />
              </v-col>
              <v-col cols="12" md="6">
                <v-select
                  v-model="formData.status"
                  label="Status *"
                  :items="statusOptions"
                  variant="outlined"
                  required
                />
              </v-col>
              <v-col cols="12">
                <v-textarea
                  v-model="formData.address"
                  label="Address"
                  variant="outlined"
                  rows="3"
                  required
                />
              </v-col>
            </v-row>

            <v-alert v-if="errorMessage" type="error" class="mt-4">
              {{ errorMessage }}
            </v-alert>

            <div class="d-flex gap-2">
              <v-btn color="primary" type="submit" :loading="loading">
                Create
              </v-btn>
              <v-btn variant="outlined" @click="navigateTo('/about/employees')">
                Cancel
              </v-btn>
            </div>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import type { Division, Position } from "~/types/models";
const config = useRuntimeConfig();

const loading = ref(false);
const errorMessage = ref("");

const divisions = ref<Division[]>([]);
const positions = ref<Position[]>([]);

const formData = ref({
  employee_code: "",
  name: "",
  email: "",
  phone: "",
  division_id: null as number | null,
  position_id: null as number | null,
  join_date: "",
  status: "active" as "active" | "inactive" | "resigned",
  address: "",
});

const statusOptions = [
  { title: "Active", value: "active" },
  { title: "Inactive", value: "inactive" },
  { title: "Resigned", value: "resigned" },
];

const fetchDivisions = async () => {
  try {
    const response = await $fetch<{ divisions_active: Division[] }>(`${config.public.apiBase}/divisions-active`,);
    divisions.value = response.divisions_active || [];
  } catch (err) {
    console.error("Failed to load divisions:", err);
  }
};

const fetchPositions = async () => {
  try {
    const response = await $fetch<{ positions_active: Position[] }>(
      `${config.public.apiBase}/positions-active`,
    );
    positions.value = response.positions_active || [];
  } catch (err) {
    console.error("Failed to load positions:", err);
  }
};


const submitForm = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    await $fetch(`${config.public.apiBase}/employees`, {
      method: "POST",
      body: formData.value,
    });
    navigateTo("/about/employee");
  } catch (err: any) {
    console.error("Create Failed", err);
    errorMessage.value = err?.data?.message || "Failed to create employee";
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchDivisions();
  fetchPositions();
});
</script>
