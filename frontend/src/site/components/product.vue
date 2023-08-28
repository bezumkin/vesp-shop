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
        <h1>{{ $translate(product.translations) }}</h1>
        <div class="mt-3" v-html="$translate(product.translations, 'content')" />
        <div class="font-weight-bold mt-3">{{ $price(product.price) }}</div>
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
  name: 'Product',
  components: {Breadcrumbs},
  props: {
    product: {
      type: Object,
      required: true,
    },
    thumbWidth: {
      type: [Number, String],
      default: 540,
    },
    thumbHeight: {
      type: [Number, String],
      default: 360,
    },
  },
}
</script>
