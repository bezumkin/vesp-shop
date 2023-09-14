<template>
  <div>
    <b-form-group :label="$t('models.user_address.receiver')">
      <b-form-input :value="record.receiver" disabled />
    </b-form-group>

    <b-form-group :label="$t('models.user_address.company')">
      <b-form-input :value="record.company" disabled />
    </b-form-group>

    <b-form-group :label="$t('models.user_address.address')">
      <b-form-input :value="record.address" disabled />
    </b-form-group>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.user_address.zip')">
          <b-form-input :value="record.zip" disabled />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.user_address.city')">
          <b-form-input :value="record.city" disabled />
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.user_address.email')">
          <b-form-input :value="record.email" disabled />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.user_address.phone')">
          <b-form-input :value="record.phone" disabled />
        </b-form-group>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  name: 'FormAddress',
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
