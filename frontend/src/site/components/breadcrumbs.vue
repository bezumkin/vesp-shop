<template>
  <b-breadcrumb>
    <b-breadcrumb-item :to="home">
      <fa icon="home" />
    </b-breadcrumb-item>
    <b-breadcrumb-item v-for="(i, idx) in breadcrumbs" :key="idx" :to="i.route" :active="i.active">
      {{ i.title }}
    </b-breadcrumb-item>
  </b-breadcrumb>
</template>

<script>
export default {
  name: 'Breadcrumbs',
  props: {
    categories: {
      type: Array,
      required: true,
    },
    product: {
      type: Object,
      default: null,
    },
  },
  computed: {
    home() {
      return '/' + this.$config.PRODUCTS_PREFIX
    },
    breadcrumbs() {
      const res = [...this.categories].reverse().map((i, idx) => {
        return {
          id: i.id,
          title: this.$translate(i.translations),
          route: this.$productLink(i),
          active: !this.product && this.categories.length === idx + 1,
        }
      })
      if (this.product) {
        res.push({
          id: this.product.id,
          title: this.$translate(this.product.translations),
          route: null,
          active: true,
        })
      }

      return res
    },
  },
}
</script>
