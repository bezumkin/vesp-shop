<template>
  <b-overlay :show="loading" opacity="0.5">
    <template v-if="!loading && !products.length">
      <div class="alert alert-info p-5 text-center">
        {{ $t('filters.no_results') }}
      </div>
    </template>
    <template v-if="listView">
      <div v-for="product in products" :key="product.id" class="d-flex mt-2 align-items-center">
        <b-link :to="$productLink(product)">
          <div class="image mr-2">
            <b-img
              v-if="product.file.id"
              :src="$image(product.file, {w: thumbWidth, h: thumbHeight, fit: 'crop'})"
              :srcset="$image(product.file, {w: thumbWidth * 2, h: thumbHeight * 2, fit: 'crop'}) + ' 2x'"
              :width="thumbWidth"
              :height="thumbHeight"
              alt=""
            />
          </div>
        </b-link>
        <b-link :to="$productLink(product)" class="font-weight-bold">{{ $translate(product.translations) }}</b-link>
        <b-button variant="light" size="sm" class="ml-auto" @click="$store.dispatch('addToCart', product)">
          <fa icon="cart-shopping" class="mr-1" />
          {{ $price(product.price) }}
        </b-button>
      </div>
    </template>
    <template v-else>
      <b-row>
        <b-col v-for="product in products" :key="product.id" md="6" lg="4">
          <b-link :to="$productLink(product)">
            <div class="image">
              <b-img
                v-if="product.file.id"
                :src="$image(product.file, {w: thumbWidth, h: thumbHeight, fit: 'crop'})"
                :srcset="$image(product.file, {w: thumbWidth * 2, h: thumbHeight * 2, fit: 'crop'}) + ' 2x'"
                :width="thumbWidth"
                :height="thumbHeight"
                alt=""
                fluid-grow
              />
            </div>
          </b-link>
          <div class="d-flex justify-content-between align-items-center mt-2">
            <b-link :to="$productLink(product)" class="font-weight-bold">{{ $translate(product.translations) }}</b-link>
            <b-button variant="light" size="sm" @click="$store.dispatch('addToCart', product)">
              <fa icon="cart-shopping" class="mr-1" />
              {{ $price(product.price) }}
            </b-button>
          </div>
        </b-col>
      </b-row>
    </template>
  </b-overlay>
</template>

<script>
export default {
  name: 'ListProducts',
  props: {
    value: {
      type: [String, Number],
      default: 1,
    },
    category: {
      type: [String, Number],
      default: null,
    },
    filters: {
      type: Object,
      default: null,
    },
    listView: {
      type: Boolean,
      default: false,
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
      loading: true,
      total: 0,
      products: [],
    }
  },
  async fetch() {
    this.loading = true
    try {
      const params = {limit: this.limit, page: this.page, sort: this.sort, dir: this.dir}
      if (Object.keys(this.filters).length) {
        params.filters = JSON.stringify(this.filters)
      }
      const {data} = await this.$axios.get(this.url, {params})
      this.products = data.rows
      this.total = data.total
      this.$emit('load', data)
    } catch (e) {
      console.error(e)
    } finally {
      this.loading = false
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
    url() {
      return this.category ? 'web/category/' + this.category + '/products' : 'web/products'
    },
    thumbWidth() {
      return this.listView ? 100 : 300
    },
    thumbHeight() {
      return this.listView ? 50 : 200
    },
  },
  watch: {
    limit() {
      if (this.page !== 1) {
        this.page = 1
      } else {
        this.$fetch()
      }
    },
    page: '$fetch',
    sort: '$fetch',
    dir: '$fetch',
    filters: {
      handler: '$fetch',
      deep: true,
    },
  },
}
</script>

<style scoped lang="scss">
.b-overlay-wrap {
  min-height: 300px;
}
.image {
  min-width: 100px;
  min-height: 50px;
  background-color: $light;
  border-radius: $border-radius;
  overflow: hidden;
}
.btn-light {
  white-space: nowrap;
}
.row {
  margin: -1rem 0 0 -1rem;
  & > div {
    padding: 1rem 0 0 1rem;
  }
}
</style>
