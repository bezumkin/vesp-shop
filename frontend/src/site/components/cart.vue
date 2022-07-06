<template>
  <div v-if="amount" class="pb-3">
    <b-row v-for="product in products" :key="product.id" align-v="center" class="mt-3">
      <b-col md="4" class="text-center text-md-left mb-2 mb-md-0">
        <div class="font-weight-bold">{{ product.title }}</div>
        <div class="small text-muted">{{ product.price }} руб.</div>
      </b-col>
      <b-col cols="6" md="4" class="d-inline-flex align-items-center justify-content-center">
        <b-button variant="light" size="sm" @click="$store.commit('removeFromCart', product)">
          <fa icon="minus" />
        </b-button>
        <div class="px-2">{{ product.amount }} шт.</div>
        <b-button variant="light" size="sm" @click="$store.commit('addToCart', product)">
          <fa icon="plus" />
        </b-button>
      </b-col>
      <b-col cols="6" md="4" class="text-right">
        <strong>{{ Number(product.price * product.amount).toFixed(2) }}</strong> руб.
      </b-col>
    </b-row>

    <div v-if="products.length > 1" class="bg-light mt-3 py-3 border-top text-center">
      Итого <strong>{{ amount }}</strong> шт., на сумму <strong>{{ total }}</strong> руб.
    </div>

    <div class="mt-4">
      <b-button variant="danger" @click="$store.commit('clearCart')">Очистить корзину</b-button>
    </div>
  </div>
  <div v-else>
    <div class="p-4 text-center font-weight-bold">Ваша корзина пуста</div>
  </div>
</template>

<script>
export default {
  name: 'Cart',
  computed: {
    products() {
      return this.$store.getters.products
    },
    total() {
      return this.$store.getters.cartTotal
    },
    amount() {
      return this.$store.getters.cartProducts
    },
  },
}
</script>
