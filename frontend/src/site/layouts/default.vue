<template>
  <div class="min-vh-100 d-flex flex-column">
    <app-navbar />
    <b-container class="pt-4 flex-grow-1">
      <nuxt />
    </b-container>
    <app-footer />
  </div>
</template>
<script>
import AppNavbar from '../components/app/navbar'
import AppFooter from '../components/app/footer'

export default {
  components: {AppFooter, AppNavbar},
  mounted() {
    this.$store.dispatch('loadCart')
  },
}
</script>
