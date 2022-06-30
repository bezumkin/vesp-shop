<template>
  <div>
    <breadcrumbs :category="category" />

    <list-products-actions v-model="listView" :sort.sync="sort" :dir.sync="dir" />

    <list-products
      v-model="page"
      :category="$route.params.category"
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
import ListProducts from '../../components/list-products'
import ListProductsActions from '../../components/list-products-actions'
import Breadcrumbs from '~/components/breadcrumbs'

export default {
  components: {Breadcrumbs, ListProductsActions, ListProducts},
  validate({params}) {
    return Boolean(params.category)
  },
  async asyncData({app, params, error}) {
    try {
      const {data} = await app.$axios.get('web/categories/' + params.category)
      return {category: data}
    } catch (e) {
      error({statusCode: e.response.status, message: e.message})
    }
  },
  data() {
    return {
      page: 1,
      total: 0,
      sort: 'title',
      dir: 'asc',
      listView: false,
      category: {},
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
