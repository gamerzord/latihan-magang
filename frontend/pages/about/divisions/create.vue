<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="8" offset-md="2">
        <v-card>
          <v-card-title class="text-h5">
            Create Division
          </v-card-title>

          <v-card-text>
            <v-form ref="form" @submit.prevent="submitForm">
              <v-text-field
                v-model="formData.name"
                label="Division Name *"
                required
                variant="outlined"
                density="comfortable"
                class="mb-3"
              />

              <v-text-field
                v-model="formData.code"
                label="Division Code *"
                required
                variant="outlined"
                density="comfortable"
                class="mb-3"
              />

              <v-textarea
                v-model="formData.description"
                label="Description"
                variant="outlined"
                density="comfortable"
                rows="3"
                class="mb-3"
              />

              <v-switch
                v-model="formData.is_active"
                label="Active Status"
                color="primary"
                class="mb-3"
              />

              <v-alert v-if="errorMessage" type="error" class="mb-3">
                {{ errorMessage }}
              </v-alert>

              <div class="d-flex gap-2">
                <v-btn
                  color="primary"
                  type="submit"
                  :loading="loading"
                >
                  Create
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
const config = useRuntimeConfig();

const loading = ref(false);

const form = ref(null)
const errorMessage = ref('')

const formData = ref({
  name: '',
  code: '',
  description: '',
  is_active: true
})

const submitForm = async () => {
  loading.value = true
  errorMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/divisions`, {
      method: 'POST',
      
      body: formData.value
    })

    navigateTo('/about/division')
  } catch (err: any) {
    console.error("Create Failed", err);
    errorMessage.value = err.data?.message || "Failed to create division";
  } finally {
    loading.value = false
  }
}
</script>