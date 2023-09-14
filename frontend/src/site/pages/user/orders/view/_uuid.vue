<template>
  <vesp-modal v-model="record" :title="title" size="lg" :cancel-title="$t('actions.close')">
    <template #form-fields>
      <b-tabs content-class="mt-3" pills justified>
        <b-tab :title="$t('models.order.tab_main')">
          <form-order v-model="record" />
        </b-tab>
        <b-tab :title="$t('models.order.tab_products')">
          <form-products v-model="record" />
        </b-tab>
        <b-tab :title="$t('models.order.tab_address')" :disabled="!record.address">
          <form-address v-if="Boolean(record.address)" v-model="record.address" />
        </b-tab>
        <b-tab :title="$t('models.order.tab_payment')" :disabled="!record.payment">
          <form-payment v-if="Boolean(record.payment)" v-model="record.payment" />
        </b-tab>
      </b-tabs>
    </template>
  </vesp-modal>
</template>

<script>
import {url} from '../../orders'
import FormOrder from '~/components/forms/order'
import FormProducts from '~/components/forms/products'
import FormAddress from '~/components/forms/address'
import FormPayment from '~/components/forms/payment'

export default {
  name: 'UserOrdersView',
  components: {FormOrder, FormProducts, FormAddress, FormPayment},
  validate({params}) {
    return /^(\w{8})-((\w{4}-){3})(\w{12})$/i.test(params.uuid)
  },
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get(url + '/' + params.uuid)
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url,
      record: {},
    }
  },
  computed: {
    title() {
      return this.$t('models.order.title_one') + ' #' + this.record.num
    },
  },
}
</script>
