<template>
  <v-container fluid class="hero-section pa-0">
    <v-row no-gutters align="center" class="hero-content">
      <v-col cols="12" md="6" class="pa-8 pa-md-16">
        <v-fade-transition appear>
          <div>
            <p class="text-overline text-white mb-2">
              Better Solutions Better Services
            </p>
            
            <h1 class="text-h2 text-md-h1 font-weight-bold text-white mb-4">
              TRANSFORMASI DIGITAL
            </h1>
            
            <p class="text-body-1 text-white mb-6">
              Kami membantu menciptakan budaya digital organisasi dengan 
              peningkatan kompetensi digital SDM, perancangan kembali proses 
              kerja berbasis digital, dan pemanfaatan optimal teknologi informasi,
              Artificial intelligence, Internet of Things, Big Data.
            </p>
            <!-- CAROUSEL WEIRD NOT ACCURATE-->
            <v-btn color="accent" size="large" rounded>
              Hubungi Kami
            </v-btn>
          </div>
        </v-fade-transition>
      </v-col>
      
      <v-col cols="12" md="6" class="pa-8">
        <v-carousel
          v-model="current"
          hide-delimiters
          :cycle="false"
          height="400"
          :reverse="reverse"
        >
          <template #prev>
            <v-btn
              icon
              variant="flat"
              color="white"
              size="large"
              class="custom-arrow prev-arrow"
              @click="prevSlide"
            >
              <v-icon size="24">mdi-chevron-left</v-icon>
            </v-btn>
          </template>

          <template #next>
            <v-btn
              icon
              variant="flat"
              color="white"
              size="large"
              class="custom-arrow next-arrow"
              @click="nextSlide"
            >
              <v-icon size="24">mdi-chevron-right</v-icon>
            </v-btn>
          </template>

          <v-carousel-item
            v-for="(item, i) in carouselItems"
            :key="i"
            :src="item.src"
          />
        </v-carousel>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'

const carouselItems = [
  { src: '/hero-1.png' },
  { src: '/hero-2.png' },
]

const current = ref(0)

const reverse = ref(false)

const nextSlide = () => {
  reverse.value = false
  current.value = (current.value + 1) % carouselItems.length
}

const prevSlide = () => {
  reverse.value = true
  current.value = (current.value - 1 + carouselItems.length) % carouselItems.length
}
</script>

<style scoped>
.hero-section {
  background: transparent !important;
  min-height: 100vh;
}

.hero-content {
  min-height: 100vh;
  color: white;
  backdrop-filter: none;
}

.custom-arrow {
  border-radius: 50% !important;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
  width: 48px !important;
  height: 48px !important;
}

.custom-arrow:hover {
  background-color: #f5f5f5 !important;
}

/* Fixed arrow positioning */
:deep(.v-carousel__prev) {
  position: absolute !important;
  left: 16px !important;
  top: 50% !important;
  transform: translateY(-50%) !important;
  z-index: 2 !important;
}

:deep(.v-carousel__next) {
  position: absolute !important;
  right: 16px !important;
  top: 50% !important;
  transform: translateY(-50%) !important;
  z-index: 2 !important;
}

/* Remove the default controls styling that might interfere */
:deep(.v-carousel__controls) {
  background: transparent !important;
}
</style>