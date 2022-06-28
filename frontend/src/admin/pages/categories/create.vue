<template>
  <vesp-modal v-model="record" :url="url" :title="$t('models.category.title_one')">
    <template #form-fields>
      <form-category v-model="record" />
    </template>
  </vesp-modal>
</template>

<script>
import {url} from '../categories'
import FormCategory from '../../components/forms/category'

export {url}
export default {
  name: 'CategoryCreatePage',
  components: {FormCategory},
  data() {
    return {
      url,
      record: {
        title: '',
        description: '',
        alias: '',
        active: true,
      },
    }
  },
}
</script>
