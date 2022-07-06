<template>
  <div v-if="total" class="mt-4 pt-4 border-top">
    <h5>Заказ</h5>
    <b-form @submit.prevent="onSubmit">
      <b-form-group label="Ваше имя">
        <b-form-input v-model.trim="form.name" autofocus required />
      </b-form-group>

      <b-form-group label="Email">
        <b-form-input v-model.trim="form.email" type="email" required />
      </b-form-group>

      <b-row>
        <b-col md="4">
          <b-form-group label="Индекс">
            <b-form-input v-model.trim="form.post" type="number" min="0" max="999999" />
          </b-form-group>
        </b-col>
        <b-col md="8">
          <b-form-group label="Город">
            <b-form-input v-model.trim="form.city" />
          </b-form-group>
        </b-col>
      </b-row>

      <b-form-group label="Адрес">
        <b-form-input v-model.trim="form.address" />
      </b-form-group>

      <div class="text-right">
        <b-button variant="primary" type="submit" :disabled="loading">
          Отправить
          <b-spinner v-if="loading" small />
        </b-button>
      </div>
    </b-form>
  </div>
  <div v-else-if="showSuccess">
    <div class="p-4 text-center font-weight-bold">Спасибо за заказ!</div>
  </div>
</template>

<script>
export default {
  name: 'Order',
  data() {
    return {
      loading: false,
      showSuccess: false,
      url: 'web/orders',
      form: {
        name: '',
        email: '',
        post: '',
        city: '',
        address: '',
      },
    }
  },
  computed: {
    products() {
      return this.$store.getters.products
    },
    total() {
      return this.$store.getters.cartTotal
    },
  },
  methods: {
    async onSubmit() {
      this.loading = true
      try {
        const products = {}
        this.products.forEach((i) => {
          products[i.id] = i.amount
        })
        await this.$axios.put(this.url, {...this.form, products})
        this.showSuccess = true
        this.$store.commit('clearCart')
      } catch (e) {
        console.error(e)
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
