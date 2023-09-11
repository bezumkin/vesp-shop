<template>
  <b-overlay v-if="amount" :show="loading" opacity="0.5" class="pb-3">
    <b-row v-for="item in products" :key="item.product_key" align-v="center" class="mt-3">
      <b-col md="6" class="text-center text-md-left mb-2 mb-md-0">
        <div class="d-flex align-items-center">
          <b-img
            v-if="item.product.file.id"
            :src="$image(item.product.file, {w: thumbWidth, h: thumbHeight, fit: 'crop'})"
            :srcset="$image(item.product.file, {w: thumbWidth * 2, h: thumbHeight * 2, fit: 'crop'}) + ' 2x'"
            :width="thumbWidth"
            :height="thumbHeight"
            class="rounded mr-2"
            alt=""
          />
          <div>
            <div class="font-weight-bold">{{ $translate(item.product.translations) }}</div>
            <div class="small text-muted">{{ $price(item.product.price) }}</div>
          </div>
        </div>
      </b-col>
      <b-col cols="6" md="3" class="d-inline-flex align-items-center justify-content-center">
        <b-button variant="light" size="sm" @click="productMinus(item)">
          <fa icon="minus" />
        </b-button>
        <div class="px-2">{{ item.amount }} шт.</div>
        <b-button variant="light" size="sm" @click="productPlus(item)">
          <fa icon="plus" />
        </b-button>
      </b-col>
      <b-col cols="6" md="3" class="text-right">
        <strong>{{ $price(item.product.price * item.amount) }}</strong>
      </b-col>
    </b-row>

    <div class="bg-light mt-3 py-3 border-top text-center">
      {{ $t('cart.total') }}
      <span v-html="$tc('cart.total_pieces', amount, {amount})" />, <span v-html="$t('cart.total_price', {total})" />.
    </div>

    <div class="mt-4">
      <b-button variant="danger" @click="cartDelete">{{ $t('cart.clear') }}</b-button>
    </div>
  </b-overlay>
  <div v-else>
    <div class="p-4 text-center font-weight-bold">{{ $t('cart.empty') }}</div>
  </div>
</template>

<script>
export default {
  name: 'Cart',
  props: {
    thumbWidth: {
      type: [String, Number],
      default: 100,
    },
    thumbHeight: {
      type: [String, Number],
      default: 50,
    },
  },
  data() {
    return {
      loading: false,
    }
  },
  computed: {
    products() {
      return this.$store.state.cartProducts
    },
    total() {
      return this.$price(this.$store.getters.cartTotal)
    },
    amount() {
      return this.$store.getters.cartProducts
    },
  },
  methods: {
    async productMinus(product) {
      this.loading = true
      try {
        await this.$store.dispatch('changeAmount', {...product, amount: product.amount - 1})
      } catch (e) {
      } finally {
        this.loading = false
      }
    },
    async productPlus(product) {
      this.loading = true
      try {
        await this.$store.dispatch('changeAmount', {...product, amount: product.amount + 1})
      } catch (e) {
      } finally {
        this.loading = false
      }
    },
    async cartDelete() {
      this.loading = true
      try {
        await this.$store.dispatch('deleteCart')
      } catch (e) {
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
