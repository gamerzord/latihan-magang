<template>
    <Header position="fixed" />
    
    <v-main>
      <HeroSection />
      <ServicesSection />
      <ClientsSection />
      <ContactCTA />
    </v-main>

    <Footer />
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
</script>