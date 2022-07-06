<template>
  <b-navbar variant="light" toggleable="md">
    <b-container>
      <b-navbar-brand :to="{name: 'index'}">
        <b-img src="~/assets/images/logo-vesp.svg" width="152" height="20" alt="Vesp" />
      </b-navbar-brand>
      <b-button variant="primary" :disabled="!products" class="ml-auto" @click="showCart = true">
        <fa icon="cart-shopping" />
        <template v-if="total">{{ total }} руб.</template>
      </b-button>
    </b-container>

    <b-modal v-model="showCart" title="Корзина" hide-footer>
      <cart />
      <order />
    </b-modal>
  </b-navbar>
</template>

<script>
import Cart from '../../components/cart'
import Order from '../../components/order'

export default {
  name: 'AppNavbar',
  components: {Order, Cart},
  data() {
    return {
      showCart: false,
    }
  },
  computed: {
    total() {
      return this.$store.getters.cartTotal
    },
    products() {
      return this.$store.getters.cartProducts
    },
  },
}
</script>
