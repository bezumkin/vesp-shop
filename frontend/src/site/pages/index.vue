<template>
  <div>
    <list-products v-model="page" :limit="limit" @load="onLoad" />

    <b-pagination v-model="page" :total-rows="total" :per-page="limit" class="mt-3" />
  </div>
</template>

<script>
import ListProducts from '../components/list-products'

export default {
  name: 'IndexPage',
  components: {ListProducts},
  data() {
    return {
      page: 1,
      limit: 20,
      total: 0,
    }
  },
  methods: {
    onLoad({total}) {
      this.total = total
    },
  },
}
</script>
