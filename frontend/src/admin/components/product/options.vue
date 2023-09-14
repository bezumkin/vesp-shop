<template>
  <section>
    <div v-for="(variants, option) in preparedOptions" :key="option" class="wrapper">
      <slot name="default" v-bind="{variants, option, select: onSelect, isActive}">
        <template v-if="option !== 'discount'">
          <slot name="option" v-bind="{option, variants}"> </slot>
          <b-badge
            v-for="(variant, idx) in variants"
            :key="idx"
            :variant="getVariant(option, variant)"
            @click="onSelect(option, variant)"
          >
            <slot name="variant" v-bind="{option, variant}">{{ variant.split(' ').map(ucFirst).join(' ') }}</slot>
          </b-badge>
        </template>
      </slot>
    </div>
  </section>
</template>

<script>
export default {
  name: 'ProductOptions',
  props: {
    value: {
      type: [Object, Array],
      default() {
        return null
      },
    },
    options: {
      type: Object,
      required: true,
    },
    variantInactive: {
      type: String,
      default: 'secondary',
    },
    variantActive: {
      type: String,
      default: 'primary',
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      preparedOptions: {},
    }
  },
  computed: {
    record: {
      get() {
        return !this.value || Array.isArray(this.value) ? {} : this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
  created() {
    const options = {}
    Object.keys(this.options).forEach((key) => {
      if (this.options[key] && this.options[key].length > 0) {
        options[key] = this.options[key]
      }
    })
    // Check already selected values
    Object.keys(this.record).forEach((key) => {
      if (this.record[key] && !options[key]) {
        options[key] = [this.record[key]]
      }
    })
    this.preparedOptions = options
  },
  methods: {
    isActive(option, variant) {
      return this.record[option] === variant
    },
    getVariant(option, variant) {
      return this.isActive(option, variant) ? this.variantActive : this.variantInactive
    },
    onSelect(option, variant) {
      if (this.disabled) {
        return
      }
      const record = {...this.record}
      if (record[option] === variant) {
        delete record[option]
      } else {
        record[option] = variant
      }
      this.record = record
    },
    ucFirst(string) {
      return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase()
    },
  },
}
</script>

<style scoped lang="scss">
section {
  .wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
    margin-top: 0.25rem;
    align-items: center;
  }
  .badge {
    cursor: pointer;
  }
}
</style>
