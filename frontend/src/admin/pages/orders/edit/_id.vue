<template>
  <vesp-modal v-model="record" :url="url" :title="$t('models.order.title_one') + ' #' + record.id">
    <template #form-fields>
      <form-order v-model="record" />
    </template>
  </vesp-modal>
</template>

<script>
import {url} from '../../orders'
import FormOrder from '../../../components/forms/order'

export {url}
export default {
  name: 'ProductCreatePage',
  components: {FormOrder},
  validate({params}) {
    return /^\d+$/.test(params.id)
  },
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get(url + '/' + params.id)
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url,
      record: {
        name: '',
        email: '',
        post: '',
        city: '',
        address: '',
        total: '',
        order_products: [],
      },
    }
  },
}
</script>
