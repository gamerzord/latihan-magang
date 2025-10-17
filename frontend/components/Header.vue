<template>
  <v-app-bar elevation="0" color="transparent" class="header">
    <v-container>
      <v-row align="center" no-gutters>
        <v-col cols="auto" class="logo-col">
          <img src="/logo.png" alt="Traspac Logo" height="40" />
        </v-col>

        <!-- Desktop Menu -->
        <v-col cols="auto" class="hidden-sm-and-down">
          <div class="menu-wrapper">
            <template v-for="(item, index) in menuItems" :key="item.text">
              <v-menu
                v-if="item.children"
                offset-y
                transition="fade transition"
              >
                <template #activator="{ props }">
                  <v-btn
                    text
                    v-bind="props"
                    class="nav-btn"
                  >
                    {{ item.text }}
                    <v-icon size="14" class="ml-1">mdi-chevron-down</v-icon>
                  </v-btn>
                </template>
                <v-list class="dropdown-list">
                  <v-list-item
                    v-for="sub in item.children"
                    :key="sub.text"
                    :to="sub.link"
                    class="dropwdown-item"
                  >
                    <v-list-item-title>{{ sub.text }}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>

              <v-btn
                v-else
                text
                :to="item.link"
                class="nav-btn"
              >
                {{ item.text }}
              </v-btn>

              <span v-if="index < menuItems.length - 1" class="dot-separator">•</span>
            </template>
          </div>
        </v-col>

        <!-- Mobile Menu Icon -->
        <v-col cols="auto" class="hidden-md-and-up">
          <v-app-bar-nav-icon color="white" @click="drawer = !drawer" />
        </v-col>
      </v-row>
    </v-container>
  </v-app-bar>

  <!-- Mobile Drawer -->
  <v-navigation-drawer v-model="drawer" temporary>
    <v-list>
      <template v-for="item in menuItems" :key="item.text">
        <v-list-group
          v-if="item.children"
          :value="false"
        >
          <template #activator>
            <v-list-item-title>{{ item.text }}</v-list-item-title>
          </template>
          <v-list-item
            v-for="sub in item.children"
            :key="sub.text"
            :to="sub.link"
          >
            <v-list-item-title>{{ sub.text }}</v-list-item-title>
          </v-list-item>
        </v-list-group>

        <v-list-item
          v-else
          :to="item.link"
        >
          <v-list-item-title>{{ item.text }}</v-list-item-title>
        </v-list-item>
      </template>
    </v-list>
  </v-navigation-drawer>
</template>

<script setup>
const drawer = ref(false)

const menuItems = [
  { text: 'CRUD', link: '/dashboard/crud' },
  { text: 'Beranda', link: '/' },
  { text: 'Tentang Kami',
    children: [
      { text: 'Profil Kami', link: '/about/profile' },
      { text: 'Budaya Kami', link: '/about/culture' },
      { text: 'CSR', link: '/about/csr' },
      { text: 'Gallery', link: '/about/gallery' },
      { text: 'Beasiswa', link: '/about/scholarship' },
      { text: 'Employee', link: '/about/employee' },
      { text: 'Division', link: '/about/division' },
      { text: 'Position', link: '/about/position' }
    ]
  },
  { text: 'Produk dan Layanan', link: '/services' },
  { text: 'Traspac Competition', link: '/competition' },
  { text: 'Lowongan', link: 'https://karir.traspac.id/' },
  { text: 'Kontak', link: '/contact' }
]
</script>

<style scoped>
.header {
  position: static !important;
  background: transparent !important;
  box-shadow: none !important;
  padding: 12px 0;
  display: flex;
  align-items: center;
}

.logo-col {
  display: flex !important;
  justify-content: center !important;
  align-items: center !important;
  margin-left: auto !important;
  margin-right: auto !important;
}

.menu-wrapper {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: auto;
}

.nav-btn {
  color: white !important;
  font-weight: 400;
  text-transform: none;
  font-size: 14px;
  min-width: auto !important;
}

.nav-btn:hover {
  color: #cce0ff !important;
  background: transparent !important;
}

.dropdown-list { 
  background-color: rgba(255, 255, 255, 0.1); 
  backdrop-filter: blur(6px); 
}

.dropdown-item { 
  color: #fff !important; 
}

.dropdown-item:hover { 
  background-color: rgba(255, 255, 255, 0.2); 
}

.dot-separator {
  color: transparent;
}
</style>