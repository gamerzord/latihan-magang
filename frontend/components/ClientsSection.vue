<template>
  <v-container class="py-16 grey lighten-4">
    <!-- Section Header -->
    <v-row justify="center" class="mb-8">
      <v-col cols="12" md="8" class="text-center">
        <h2 class="text-h3 font-weight-bold mb-4">
          Pelanggan Kami
        </h2>
        <p class="text-body-1">
          Beberapa pelanggan yang pernah bekerja sama dengan kami
        </p>
      </v-col>
    </v-row>

    <!-- Tabs -->
    <v-tabs 
      v-model="tab" 
      centered 
      color="primary" 
      class="mb-8"
    >
      <v-tab value="military">Militer</v-tab>
      <v-tab value="industry">Industri</v-tab>
      <v-tab value="government">Pemerintahan</v-tab>
    </v-tabs>

    <!-- Tab Content -->
    <v-window v-model="tab">
      <v-window-item 
        v-for="category in categories" 
        :key="category.value"
        :value="category.value"
      >
        <v-row justify="center">
          <v-col 
            v-for="client in category.clients" 
            :key="client.name"
            cols="6" 
            sm="4" 
            md="3" 
            lg="2"
          >
            <v-img 
              :src="client.logo" 
              :alt="client.name"
              contain
              height="100"
              class="client-logo"
            />
          </v-col>
        </v-row>
      </v-window-item>
    </v-window>
  </v-container>
</template>

<script setup>
const tab = ref('military')

const categories = [
  {
    value: 'military',
    clients: [
      { name: 'TNI', logo: '/clients/tni.png' },
      { name: 'TNI AL', logo: '/clients/tni-al.png' }
    ]
  },
  {
    value: 'industry',
    clients: [
      { name: 'Jasindo', logo: '/clients/jasindo.png' },
      { name: 'Jasamarga', logo: '/clients/jasamarga.png' },
      { name: 'Radiant Utama', logo: '/clients/radiant.png' }
    ]
  },
  {
    value: 'government',
    clients: [
      { name: 'Kemnaker', logo: '/clients/kemnaker.png' },
      { name: 'Kemenkes', logo: '/clients/kemenkes.png' },
      { name: 'BSN', logo: '/clients/bsn.png' },
      { name: 'BMKG', logo: '/clients/bmkg.png' },
      { name: 'Pemkot Malang', logo: '/clients/malang.png' }
    ]
  }
]
</script>

<style scoped>
.client-logo {
  filter: grayscale(100%);
  opacity: 0.7;
  transition: all 0.3s ease;
}

.client-logo:hover {
  filter: grayscale(0%);
  opacity: 1;
}
</style>