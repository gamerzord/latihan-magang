<template>
  <v-container fluid class="cta-section py-12">
    <v-container>
      <v-row align="center">
        <v-col cols="12" md="8">
          <h3 class="text-h4 font-weight-bold text-white mb-3">
            Anda Berminat? Hubungi!
          </h3>
          <p class="text-body-1 text-white">
            Mari ciptakan produk yang menjadi solusi untuk negeri bersama kami!
          </p>
        </v-col>
        
        <v-col cols="12" md="4" class="text-right">
          <v-btn 
            color="accent" 
            size="large" 
            rounded
            to="/contact"
          >
            Hubungi Kami
          </v-btn>
        </v-col>
      </v-row>
    </v-container>
  </v-container>
</template>

<style scoped>
.cta-section {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

@media (max-width: 960px) {
  .text-right {
    text-align: center !important;
  }
}
</style>