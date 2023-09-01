<template>
  <div>
    <div ref="slider" />
    <b-row class="mt-3">
      <b-col cols="6">
        <b-form-input
          v-model="internalValue[0]"
          type="number"
          :min="myMin"
          :max="myMax"
          :step="step"
          :disabled="disabled"
          @change="onInput"
        />
      </b-col>
      <b-col cols="6">
        <b-form-input
          v-model="internalValue[1]"
          type="number"
          :min="myMin"
          :max="myMax"
          :step="step"
          :disabled="disabled"
          @change="onInput"
        />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import noUiSlider from 'nouislider'
export default {
  name: 'InputRangeSlider',
  props: {
    value: {
      type: Array,
      default: null,
    },
    min: {
      type: [String, Number],
      default: 0,
    },
    max: {
      type: [String, Number],
      default: 1000,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    precision: {
      type: [String, Number],
      default: 0,
    },
  },
  data() {
    return {
      slider: null,
      internalValue: [],
    }
  },
  computed: {
    myValue: {
      get() {
        return this.value || [this.myMin, this.myMax]
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
    myMin() {
      if (!this.precision) {
        return Math.floor(this.min)
      }
      const pow = Math.pow(10, this.precision)
      return Math.round(this.min * pow) / pow
    },
    myMax() {
      if (!this.precision) {
        return Math.ceil(this.max)
      }
      const pow = Math.pow(10, this.precision)
      return Math.round(this.max * pow) / pow
    },
    step() {
      return !this.precision ? 1 : 1 / Number('1' + '0'.repeat(this.precision))
    },
  },
  watch: {
    value(newValue) {
      if (!newValue) {
        this.internalValue = [this.myMin, this.myMax]
        this.onInput()
      }
    },
    disabled(newValue) {
      if (newValue && this.disabled) {
        this.slider.enable()
      } else if (!newValue && !this.disabled) {
        this.slider.disable()
      }
    },
    min() {
      this.slider.updateOptions({
        range: {min: this.myMin},
      })
    },
    max() {
      this.slider.updateOptions({
        range: {max: this.myMax},
      })
    },
  },
  mounted() {
    this.initSlider()
  },
  beforeDestroy() {
    this.slider.destroy()
  },
  methods: {
    initSlider() {
      this.slider = noUiSlider.create(this.$refs.slider, {
        connect: true,
        start: this.myValue,
        step: this.step,
        range: {min: this.myMin, max: this.myMax},
        format: {
          to: this.formatValue,
          from: this.formatValue,
        },
      })

      if (this.disabled) {
        this.slider.disable()
      }

      this.slider.on('set', this.onSet)
      this.slider.on('update', this.onUpdate)
    },
    onSet(newValue) {
      this.myValue = newValue
    },
    onUpdate(newValue) {
      this.internalValue = newValue
    },
    onInput() {
      this.slider.set(this.internalValue)
    },
    formatValue(val) {
      return Number(val).toFixed(this.precision)
    },
  },
}
</script>
