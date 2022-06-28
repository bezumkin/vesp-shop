<template>
  <div>
    <b-form-group :label="$t('models.category.title')">
      <b-form-input v-model.trim="record.title" required autofocus />
    </b-form-group>

    <b-form-group :label="$t('models.category.description')">
      <b-form-textarea v-model.trim="record.description" rows="5" />
    </b-form-group>

    <b-form-group :label="$t('models.category.alias')">
      <b-form-input v-model.trim="record.alias" required />
    </b-form-group>

    <b-form-group>
      <b-form-checkbox v-model="record.active">
        {{ $t('models.category.active') }}
      </b-form-checkbox>
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormCategory',
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
