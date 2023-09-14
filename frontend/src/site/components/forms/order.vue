<template>
  <div>
    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.order.num')">
          <b-form-input :value="'#' + record.num" disabled />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.order.created_at')">
          <b-form-input :value="$options.filters.datetime(record.created_at)" disabled />
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.order.cart')">
          <b-form-input :value="$price(record.cart)" disabled />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.order.discount')">
          <b-form-input :value="record.discount ? $price(record.discount) : '-'" disabled />
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.order.total')">
          <b-form-input :value="$price(record.total)" disabled />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.order.weight')">
          <b-form-input :value="$weight(record.weight)" disabled />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group :label="$t('models.order.comment')">
      <b-form-textarea :value="record.comment" rows="5" disabled />
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormOrder',
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
