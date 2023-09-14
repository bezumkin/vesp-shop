<template>
  <div>
    <b-form-group :label="$t('models.payment.service')">
      <b-form-input :value="record.service" disabled />
    </b-form-group>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.payment.amount')">
          <b-form-input :value="record.amount" disabled />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.payment.paid')">
          <b-form-input :value="$t('models.payment.paid_' + String(record.paid))" disabled />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group :label="$t('models.payment.link')">
      <b-form-textarea :value="record.link" rows="2" disabled />
    </b-form-group>
    <b-form-group :label="$t('models.payment.remote_id')" disabled>
      <b-form-input :value="record.remote_id" />
    </b-form-group>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.payment.created_at')">
          <b-form-input :value="$options.filters.datetime(record.created_at)" disabled />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.payment.paid_at')">
          <b-form-input :value="$options.filters.datetime(record.paid_at)" disabled />
        </b-form-group>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  name: 'FormPayment',
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
