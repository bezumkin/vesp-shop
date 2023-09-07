<template>
  <div>
    <b-form-group :label="$t('models.user.username')">
      <b-form-input v-model="record.username" required autofocus />
    </b-form-group>

    <b-form-group :label="$t('models.user.fullname')">
      <b-form-input v-model="record.fullname" required />
    </b-form-group>

    <b-form-group :label="$t('models.user.email')">
      <b-form-input v-model.trim="record.email" type="email" required />
    </b-form-group>

    <b-form-group :label="$t('models.user.password')">
      <vesp-input-password v-model="record.password" />
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormRegister',
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
