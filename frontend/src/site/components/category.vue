<template>
  <div>
    <breadcrumbs v-if="category" :category="category" />

    <list-products-actions v-model="listView" :sort.sync="sort" :dir.sync="dir" />

    <list-products
      v-model="page"
      :category="category ? category.id : null"
      :limit="limit"
      :sort="sort"
      :dir="dir"
      :list-view="listView"
      @load="onLoad"
    />

    <b-pagination v-if="total > limit" v-model="page" :total-rows="total" :per-page="limit" class="mt-5" />
  </div>
</template>

<script>
import ListProducts from '~/components/list-products'
import ListProductsActions from '~/components/list-products-actions'
import Breadcrumbs from '~/components/breadcrumbs'

export default {
  components: {Breadcrumbs, ListProductsActions, ListProducts},
  props: {
    category: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      page: 1,
      total: 0,
      sort: 'created_at',
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
