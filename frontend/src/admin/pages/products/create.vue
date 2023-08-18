<template>
  <vesp-modal v-model="record" :url="url" :title="$t('models.product.title_one')" size="lg">
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
        alias: '',
        article: '',
        price: null,
        category_id: null,
        active: true,
        translations: [],
      },
    }
  },
}
</script>
