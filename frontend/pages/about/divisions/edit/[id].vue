<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="8" offset-md="2">
        <v-card>
          <v-card-title class="text-h5">
            Update Division
          </v-card-title>

          <v-card-text>
            <v-form v-if="division" ref="form" @submit.prevent="submitForm">
              <v-text-field
                v-model="division.name"
                label="Division Name *"
                required
                variant="outlined"
                density="comfortable"
                class="mb-3"
              />

              <v-text-field
                v-model="division.code"
                label="Division Code *"
                required
                variant="outlined"
                density="comfortable"
                class="mb-3"
              />

              <v-textarea
                v-model="division.description"
                label="Description"
                variant="outlined"
                density="comfortable"
                rows="3"
                class="mb-3"
              />

              <v-switch
                v-model="division.is_active"
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
                  @click="navigateTo('/about/division')"
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
import type { Division } from "~/types/models";

const route = useRoute()
const config = useRuntimeConfig();

const id = route.params.id
const loading = ref(false);
const form = ref(null)
const errorMessage = ref('')
const successMessage = ref('')

const { data: divisionsData } = await useFetch<{ division: Division }>(
  `${config.public.apiBase}/divisions/${id}`,
)

const division = computed(() => divisionsData.value?.division);

const submitForm = async () => {
  loading.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/divisions/${id}`, {
      method: 'PUT',
      body: division.value,
      
    })
    successMessage.value = "division updated successfully!";
    setTimeout(() => navigateTo("/about/division"), 1000);
  } catch (err: any) {
    console.error("Create Failed", err);
    errorMessage.value = err.data?.message || "Failed to create employee";
  } finally {
    loading.value = false
  }
}
</script>