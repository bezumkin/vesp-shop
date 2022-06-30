<template>
  <div>
    <breadcrumbs :category="product.category" :product="product" />

    <b-row class="mt-3">
      <b-col v-if="product.product_files.length" md="6" class="mt-3 mt-md-0 order-2 order-md-1">
        <b-carousel controls indicators>
          <b-carousel-slide v-for="image in product.product_files" :key="image.file_id">
            <template #img>
              <b-img
                :src="$image(image.file, {w: thumbWidth, h: thumbHeight, fit: 'crop'})"
                :srcset="$image(image.file, {w: thumbWidth * 2, h: thumbHeight * 2, fit: 'crop'}) + ' 2x'"
                :width="thumbWidth"
                :height="thumbHeight"
                alt=""
              />
            </template>
          </b-carousel-slide>
        </b-carousel>
      </b-col>
      <b-col md="6" class="order-1 order-md-2">
        <h1>{{ product.title }}</h1>
        <div class="mt-3">{{ product.description }}</div>
        <div class="font-weight-bold mt-3">{{ product.price }} руб.</div>
        <div class="mt-3">
          <b-button variant="primary" @click="$store.commit('addToCart', product)">В корзину!</b-button>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Breadcrumbs from '~/components/breadcrumbs'

export default {
  name: 'ProductPage',
  components: {Breadcrumbs},
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
      thumbWidth: 540,
      thumbHeight: 360,
      product: {
        category: {},
        product_files: [],
      },
    }
  },
}
</script>
