<template>
  <v-footer color="grey-darken-4" class="text-white">
    <v-container>
      <!-- Main Footer Content -->
      <v-row>
        <!-- Company Info -->
        <v-col cols="12" md="3">
          <img 
            src="/logo-white.png" 
            alt="Traspac" 
            height="30" 
            class="mb-4" 
          />
          <p class="text-caption mb-2">
            Better Solution Better Service
          </p>
          <div class="mt-4">
            <v-btn 
              icon 
              size="small" 
              variant="text"
              href="https://instagram.com/traspac"
              target="_blank"
            >
              <v-icon>mdi-instagram</v-icon>
            </v-btn>
            <v-btn 
              icon 
              size="small" 
              variant="text"
              href="mailto:info@traspac.co.id"
            >
              <v-icon>mdi-email</v-icon>
            </v-btn>
            <v-btn 
              icon 
              size="small" 
              variant="text"
              href="tel:02131997486"
            >
              <v-icon>mdi-phone</v-icon>
            </v-btn>
          </div>
        </v-col>

        <!-- Profile Links -->
        <v-col cols="12" md="3">
          <h3 class="text-h6 mb-4">Profile</h3>
          <v-list density="compact" color="transparent" class="transparent-list">
            <v-list-item 
              v-for="item in profileLinks" 
              :key="item.text"
              :to="item.link"
              class="transparent-item"
            >
              <v-list-item-title class="text-white">
                {{ item.text }}
              </v-list-item-title>
            </v-list-item>
          </v-list>
        </v-col>

        <!-- Services Links -->
        <v-col cols="12" md="3">
          <h3 class="text-h6 mb-4">Layanan dan Kontak</h3>
          <v-list density="compact" color="transparent" class="transparent-list">
            <v-list-item 
              v-for="item in serviceLinks" 
              :key="item.text"
              :to="item.link"
              class="transparent-item"
            >
              <v-list-item-title class="text-white">
                {{ item.text }}
              </v-list-item-title>
            </v-list-item>
          </v-list>
        </v-col>

        <!-- Address -->
        <v-col cols="12" md="3">
          <h3 class="text-h6 mb-4">Alamat Kami</h3>
          <p class="text-caption">
            Thamrin City Office Park Blok AA-02,<br>
            Jl. Thamrin Boulevard<br>
            Jakarta Pusat 10230
          </p>
        </v-col>
      </v-row>

      <v-divider class="my-6" />

      <!-- Copyright -->
      <v-row>
        <v-col cols="12" class="text-center text-caption">
          Copyright © 2025 PT TRASPAC Makmur Sejahtera | 
          Powered by PT TRASPAC Makmur Sejahtera
        </v-col>
      </v-row>
    </v-container>
  </v-footer>
</template>

<script setup>
const profileLinks = [
  { text: 'Home', link: '/' },
  { text: 'Tentang Kami', link: '/about' },
  { text: 'Lowongan', link: '/career' },
  { text: 'Budaya Kami', link: '/culture' }
]

const serviceLinks = [
  { text: 'Product and Service', link: '/services' },
  { text: 'Contact', link: '/contact' }
]
</script>

<style scoped>
/* Remove white background from list */
.transparent-list {
  background: transparent !important;
}

/* Remove white background from list items */
.transparent-item {
  background: transparent !important;
}

/* Remove hover backgrounds */
.transparent-item:hover {
  background: rgba(255, 255, 255, 0.1) !important;
}

/* Ensure the column itself has no background */
:deep(.v-col) {
  background: transparent !important;
}

/* Make sure VList doesn't add any backgrounds */
:deep(.v-list) {
  background: transparent !important;
}

:deep(.v-list-item) {
  background: transparent !important;
}
</style>