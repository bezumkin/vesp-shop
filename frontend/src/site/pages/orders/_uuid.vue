<template>
  <div>
    <div class="bg-light mb-3 py-3 border-bottom text-center">
      <span v-html="$t('order.title', {num: order.num, date: $options.filters.datetime(order.created_at)})" />
    </div>

    <div class="my-3">
      <b-row v-for="item in products" :key="item.id" align-v="center" class="mt-3">
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
              <div v-if="!item.product || !item.product.translations" class="font-weight-bold" v-text="item.title" />
              <div v-else class="font-weight-bold" v-text="$translate(item.product.translations)" />
              <div class="small text-muted">{{ $price(item.price) }}</div>
            </div>
          </div>
        </b-col>
        <b-col cols="6" md="3" class="d-inline-flex align-items-center justify-content-center">
          <div class="px-2">{{ item.amount }} шт.</div>
        </b-col>
        <b-col cols="6" md="3" class="text-right">
          <strong>{{ $price(item.price * item.amount) }}</strong>
        </b-col>
      </b-row>
    </div>

    <div class="bg-light mt-3 py-3 border-top text-center">
      {{ $t('cart.total') }}
      <span v-html="$tc('cart.total_pieces', amount, {amount})" />, <span v-html="$t('cart.total_price', {total})" />.
    </div>

    <div v-if="payment" class="mt-5">
      <div v-if="payment.paid" class="alert alert-success">
        {{ $t('order.payment_paid', {date: $options.filters.datetime(payment.paid_at)}) }}
      </div>
      <div v-else class="alert alert-info">
        {{ $t('order.payment_unpaid') }}
      </div>
    </div>

    <div class="mt-5">
      <b-form-group :label="$t('order.receiver')">
        <b-form-input v-model="address.receiver" disabled />
      </b-form-group>

      <b-row>
        <b-col md="6">
          <b-form-group :label="$t('order.email')">
            <b-form-input v-model="address.email" type="email" required disabled />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('order.phone')">
            <b-form-input v-model="address.phone" disabled />
          </b-form-group>
        </b-col>
      </b-row>

      <b-form-group :label="$t('order.company')">
        <b-form-input v-model="address.company" disabled />
      </b-form-group>

      <b-row>
        <b-col md="8">
          <b-form-group :label="$t('order.country')">
            <b-form-input v-model="address.country" disabled />
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group :label="$t('order.zip')">
            <b-form-input v-model="address.zip" type="number" min="0" max="999999" disabled />
          </b-form-group>
        </b-col>
      </b-row>

      <b-row>
        <b-col md="4">
          <b-form-group :label="$t('order.city')">
            <b-form-input v-model="address.city" disabled />
          </b-form-group>
        </b-col>
        <b-col md="8">
          <b-form-group :label="$t('order.address')">
            <b-form-input v-model="address.address" disabled />
          </b-form-group>
        </b-col>
      </b-row>

      <b-form-group :label="$t('order.comment')">
        <b-form-textarea v-model="order.comment" rows="5" disabled />
      </b-form-group>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OrdersPage',
  validate({params}) {
    return /^(\w{8})-((\w{4}-){3})(\w{12})$/i.test(params.uuid)
  },
  async asyncData({app, params, error}) {
    try {
      const {data} = await app.$axios.get('web/orders/' + params.uuid)
      return {...data}
    } catch (e) {
      error({statusCode: e.statusCode || 404, message: e.data || 'Not Found'})
    }
  },
  data() {
    return {
      thumbWidth: 100,
      thumbHeight: 50,
      order: null,
      user: null,
      address: null,
      products: null,
      payment: null,
    }
  },
  computed: {
    amount() {
      let amount = 0
      this.products.forEach((i) => {
        amount += i.amount
      })
      return amount
    },
    total() {
      return this.$price(this.order.total)
    },
  },
}
</script>
