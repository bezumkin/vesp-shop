<template>
  <div class="mt-5">
    <b-link :to="{name: 'index'}">&larr; Вернуться назад</b-link>
    <pre class="mt-3">{{ product }}</pre>
  </div>
</template>

<script>
export default {
  name: 'ProductPage',
  validate({params}) {
    return params.category && params.product
  },
  async asyncData({app, params, error}) {
    try {
      const {data} = await app.$axios.get('web/categories/' + params.category + '/products/' + params.product)
      return {product: data}
    } catch (e) {
      error({statusCode: e.response.status, message: e.message})
    }
  },
  data() {
    return {
      product: {},
    }
  },
}
</script>
