<template>
  <div>
    <b-form-group :label="$t('models.user.username')">
      <b-form-input v-model="record.username" required autofocus />
    </b-form-group>

    <b-form-group :label="$t('models.user.password')">
      <vesp-input-password v-model="record.password" required />
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormLogin',
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
