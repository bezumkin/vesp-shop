<template>
  <div>
    <list-products-actions v-model="listView" :sort.sync="sort" :dir.sync="dir" />

    <list-products v-model="page" :limit="limit" :sort="sort" :dir="dir" :list-view="listView" @load="onLoad" />

    <b-pagination v-model="page" :total-rows="total" :per-page="limit" class="mt-5" />
  </div>
</template>

<script>
import ListProducts from '../components/list-products'
import ListProductsActions from '../components/list-products-actions'

export default {
  name: 'IndexPage',
  components: {ListProductsActions, ListProducts},
  data() {
    return {
      page: 1,
      total: 0,
      sort: 'title',
      dir: 'asc',
      listView: false,
    }
  },
  computed: {
    limit() {
      return this.listView ? 10 : 9
    },
  },
  methods: {
    onLoad({total}) {
      this.total = total
    },
  },
}
</script>
