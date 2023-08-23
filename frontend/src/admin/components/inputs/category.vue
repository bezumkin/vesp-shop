<template>
  <vesp-input-combo-box v-model="myValue" url="admin/categories" v-bind="$props" v-on="$listeners">
    <template #default="{item}">
      <div :class="{'text-muted': !item.active}">
        {{ $translate(item.translations) }}
        <div class="small text-muted">{{ item.uri }}</div>
      </div>
    </template>
  </vesp-input-combo-box>
</template>

<script>
import {BFormInput} from 'bootstrap-vue'

export default {
  name: 'InputCategory',
  props: {
    ...BFormInput.extendOptions.props,
    value: {
      type: [String, Number],
      default: null,
      required: false,
    },
    placeholder: {
      type: String,
      default() {
        return this.$t('models.category.title_one')
      },
    },
    sort: {
      type: String,
      default: 'rank',
    },
    dir: {
      type: String,
      default: 'asc',
    },
    emptyText: {
      type: String,
      default: 'No results',
    },
    filterProps: {
      type: Object,
      default() {
        return {}
      },
    },
    formatValue: {
      type: Function,
      default(item) {
        return this.$translate(item.translations)
      },
    },
  },
  computed: {
    myValue: {
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
