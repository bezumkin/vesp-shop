<template>
  <div>
    <category v-if="isCategory" :category="record" />
    <product v-else :product="record" />
  </div>
</template>

<script>
import Product from '~/components/product'
import Category from '~/components/category'

export default {
  name: 'ProductsDynamicPage',
  components: {Category, Product},
  validate({params, route, redirect}) {
    if (route.fullPath.endsWith('/')) {
      return redirect(route.fullPath.slice(0, -1))
    }
    return Boolean(params.pathMatch)
  },
  async asyncData({app, params, error}) {
    try {
      const {data} = await app.$axios.get('web/resource/' + params.pathMatch)
      return {record: data}
    } catch (e) {
      error({statusCode: e.response.status, message: e.message})
    }
  },
  data() {
    return {
      record: {},
    }
  },
  computed: {
    isCategory() {
      return this.record.category_id === undefined
    },
  },
}
</script>
