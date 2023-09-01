<template>
  <div>
    <breadcrumbs v-if="category && category.breadcrumbs" :categories="category.breadcrumbs" />

    <list-products-actions v-model="listView" :sort.sync="sort" :dir.sync="dir" />

    <b-row>
      <b-col md="4" lg="3">
        <list-products-filters v-model="filters" :category="category ? category.id : null" />
      </b-col>
      <b-col md="8" lg="9">
        <list-products
          v-model="page"
          :filters="filters"
          :category="category ? category.id : null"
          :limit="limit"
          :sort="sort"
          :dir="dir"
          :list-view="listView"
          @load="onLoad"
        />

        <b-pagination v-if="total > limit" v-model="page" :total-rows="total" :per-page="limit" class="mt-5" />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import ListProducts from '~/components/list-products'
import ListProductsActions from '~/components/list-products-actions'
import Breadcrumbs from '~/components/breadcrumbs'
import ListProductsFilters from '~/components/list-products-filters.vue'

export default {
  components: {ListProductsFilters, Breadcrumbs, ListProductsActions, ListProducts},
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
      filters: {},
    }
  },
  computed: {
    limit() {
      if (this.category) {
        return this.listView ? 10 : 9
      }

      return this.listView ? 20 : 18
    },
  },
  methods: {
    onLoad({total}) {
      this.total = total
    },
  },
}
</script>
