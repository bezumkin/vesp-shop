<template>
  <div>
    <b-form-group :label="$t('models.user.username') + ' / ' + $t('models.user.email')">
      <b-form-input v-model.trim="record.username" required autofocus />
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormReset',
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
