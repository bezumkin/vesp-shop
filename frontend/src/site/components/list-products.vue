<template>
  <div class="products-list">
    <div v-for="product in products" :key="product.id" class="product">
      {{ product.id }}. {{ product.title }} &mdash; {{ product.price }} руб.
    </div>
  </div>
</template>

<script>
export default {
  name: 'ListProducts',
  props: {
    value: {
      type: [String, Number],
      default: 1,
    },
    limit: {
      type: [String, Number],
      default: 20,
    },
    sort: {
      type: String,
      default: 'id',
    },
    dir: {
      type: String,
      default: 'asc',
    },
  },
  data() {
    return {
      loading: false,
      total: 0,
      products: [],
    }
  },
  fetchOnServer: false,
  async fetch() {
    try {
      const params = {limit: this.limit, page: this.page, sort: this.sort, dir: this.dir}
      const {data} = await this.$axios.get('web/products', {params})
      this.products = data.rows
      this.total = data.total
      this.$emit('load', data)
    } catch (e) {
      console.error(e)
    }
  },
  computed: {
    page: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
  watch: {
    page() {
      this.$fetch()
    },
  },
}
</script>
