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
          <b-form-select v-model="record.paid" :options="paidOptions" />
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
          <vesp-input-date-picker v-model="record.paid_at" type="datetime" />
        </b-form-group>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  name: 'OrderPayment',
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
    paidOptions() {
      return [
        {text: this.$t('models.payment.paid_null'), value: null},
        {text: this.$t('models.payment.paid_true'), value: true},
        {text: this.$t('models.payment.paid_false'), value: false},
      ]
    },
  },
}
</script>
