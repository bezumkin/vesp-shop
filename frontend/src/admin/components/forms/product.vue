<template>
  <div>
    <b-form-group :label="$t('models.product.title')">
      <b-form-input v-model.trim="record.title" required autofocus />
    </b-form-group>

    <b-form-group :label="$t('models.product.description')">
      <b-form-textarea v-model.trim="record.description" rows="5" />
    </b-form-group>

    <b-form-group :label="$t('models.product.alias')">
      <input-alias v-model="record.alias" :watch="record.title" required />
    </b-form-group>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.product.sku')">
          <b-form-input v-model.trim="record.sku" required />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.product.price')">
          <b-form-input v-model.trim="record.price" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group :label="$t('models.product.category')">
      <vesp-input-combo-box v-model="record.category_id" url="admin/categories" required />
    </b-form-group>

    <b-form-group>
      <b-form-checkbox v-model.trim="record.active">
        {{ $t('models.product.active') }}
      </b-form-checkbox>
    </b-form-group>
  </div>
</template>

<script>
import InputAlias from '../../components/inputs/alias'

export default {
  name: 'FormProduct',
  components: {InputAlias},
  props: {
    value: {
      type: Object,
      required: true,
    },
  },
  computed: {
    record: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
}
</script>
