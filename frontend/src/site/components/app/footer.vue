<template>
  <footer class="bg-light mt-5 py-3">
    <b-container class="text-muted small">
      <b-row>
        <b-col md="6" class="font-weight-bold text-center text-md-left">Vesp Shop</b-col>
        <b-col md="6" class="text-center text-md-right mt-2 mt-md-0">bezumkin &copy; 2020 &mdash; {{ year }}</b-col>
      </b-row>
    </b-container>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter',
  data() {
    return {
      year: new Date().getFullYear(),
    }
  },
}
</script>
