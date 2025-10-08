<template>
  <v-container class="py-16">
    <!-- Section Header -->
    <v-row justify="center" class="mb-12">
      <v-col cols="12" md="8" class="text-center">
        <h2 class="text-h3 font-weight-bold mb-4">
          Keahlian Kami
        </h2>
        <p class="text-body-1 text-grey-darken-1">
          Kami selalu menghadirkan produk yang menjadi solusi untuk 
          setiap permasalahan Anda
        </p>
      </v-col>
    </v-row>

    <!-- Services Grid -->
    <v-row>
      <v-col 
        v-for="service in services" 
        :key="service.title"
        cols="12" 
        md="4"
      >
        <v-card 
          elevation="2" 
          class="service-card pa-6 text-center" 
          hover
        >
          <v-avatar size="80" color="primary" class="mb-4">
            <v-icon size="40" color="white">
              {{ service.icon }}
            </v-icon>
          </v-avatar>
          
          <h3 class="text-h6 font-weight-bold">
            {{ service.title }}
          </h3>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
const services = [
  { 
    title: 'Human Capital Management', 
    icon: 'mdi-account' 
  },
  { 
    title: 'Web & Mobile Application', 
    icon: 'mdi-monitor' 
  },
  { 
    title: 'Consulting', 
    icon: 'mdi-check-circle' 
  },
  { 
    title: 'Business Process Reengineering', 
    icon: 'mdi-briefcase-clock' 
  },
  { 
    title: 'System Integrator', 
    icon: 'mdi-cogs' 
  },
  { 
    title: 'Enterprise Application Development', 
    icon: 'mdi-cellphone' 
  }
]
</script>

<style scoped>
.service-card {
  transition: transform 0.3s ease;
  height: 100%;
}

.service-card:hover {
  transform: translateY(-8px);
}
</style>