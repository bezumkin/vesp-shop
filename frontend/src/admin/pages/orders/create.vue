<template>
  <vesp-modal v-model="record" :url="url" :title="$t('models.order.title_one')" size="lg">
    <template #form-fields>
      <b-tabs content-class="pt-3">
        <b-tab :title="$t('models.order.tab_main')">
          <form-order v-model="record" />
        </b-tab>
        <b-tab :title="$t('models.order.tab_products')">
          <order-products v-model="record" />
        </b-tab>
        <b-tab :title="$t('models.order.tab_address')" :disabled="!record.user_id">
          <order-address v-model="record" />
        </b-tab>
        <b-tab :title="$t('models.order.tab_payment')" :disabled="!record.payment">
          <order-payment v-if="Boolean(record.payment)" v-model="record.payment" />
        </b-tab>
      </b-tabs>
    </template>
  </vesp-modal>
</template>

<script>
import {url} from '../orders'
import FormOrder from '@/components/forms/order'
import OrderProducts from '@/components/order/products.vue'
import OrderAddress from '@/components/order/address.vue'
import OrderPayment from '@/components/order/payment.vue'

export {url}
export default {
  name: 'OrderCreatePage',
  components: {OrderPayment, OrderAddress, OrderProducts, FormOrder},
  data() {
    return {
      url,
      record: {
        num: '',
        user_id: null,
        address_id: null,
        comment: null,
        cart: 0,
        discount: 0,
        deliver: 0,
        weight: 0,
        total: 0,
        created_at: null,
        order_products: [],
        address: {},
        payment: null,
      },
    }
  },
}
</script>
