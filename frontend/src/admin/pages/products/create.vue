<template>
  <vesp-modal v-model="record" :url="url" :title="$t('models.product.title_one')">
    <template #form-fields>
      <form-product v-model="record" />
    </template>
  </vesp-modal>
</template>

<script>
import {url} from '../products'
import FormProduct from '../../components/forms/product'

export {url}
export default {
  name: 'ProductCreatePage',
  components: {FormProduct},
  data() {
    return {
      url,
      record: {
        title: '',
        description: '',
        sku: '',
        price: null,
        category_id: null,
        active: true,
      },
    }
  },
}
</script>
