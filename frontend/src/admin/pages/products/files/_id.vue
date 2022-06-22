<template>
  <vesp-modal :title="record.title" hide-footer size="xl">
    <product-files :product-id="record.id" />
  </vesp-modal>
</template>

<script>
import Create, {url} from '../create'
import ProductFiles from '../../../components/product/gallery'

export default {
  name: 'ProductFilesPage',
  components: {ProductFiles},
  extends: Create,
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
}
</script>
