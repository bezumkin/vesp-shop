<template>
  <b-overlay :show="loading" opacity="0.5" spinner-type="none">
    <b-form-group v-for="item in filters" :key="item.filter" :label="$t('filters.' + item.filter)">
      <template v-if="item.type === 'range'">
        <input-range-slider
          v-model="selected[item.filter]"
          :min="item.values.min"
          :max="item.values.max"
          :precision="item.filter === 'weight' ? 0 : 1"
        />
      </template>
      <template v-else>
        <b-form-checkbox-group v-model="selected[item.filter]" stacked>
          <b-form-checkbox v-for="(opt, idx) in item.values" :key="idx" :value="opt.value" :disabled="!opt.amount">
            <template v-if="item.type === 'boolean'">
              {{ $t('filters.boolean.' + String(opt.value)) }}
            </template>
            <template v-else-if="opt.extra && opt.extra.translations">
              {{ $translate(opt.extra.translations) }}
            </template>
            <template v-else>
              {{ opt.value }}
            </template>
            <sup>{{ opt.amount }}</sup>
          </b-form-checkbox>
        </b-form-checkbox-group>
      </template>
    </b-form-group>
    <b-button v-if="hasSelected" @click="onReset">
      {{ $t('filters.actions.reset') }}
    </b-button>
  </b-overlay>
</template>

<script>
import InputRangeSlider from '~/components/inputs/range-slider.vue'

export default {
  name: 'ListProductsFilters',
  components: {InputRangeSlider},
  props: {
    value: {
      type: Object,
      default: null,
    },
    category: {
      type: [String, Number],
      default: null,
    },
  },
  data() {
    return {
      loading: false,
      filters: {},
      selected: this.value || {},
      ranges: {},
      state: '{}',
    }
  },
  async fetch() {
    try {
      const {data} = await this.$axios.get(this.url)
      this.setFilters(data.rows)
      this.setRanges(data.rows)
    } catch (e) {}
  },
  computed: {
    url() {
      return this.category ? 'web/category/' + this.category + '/filters' : 'web/filters'
    },
    hasSelected() {
      if (!Object.keys(this.selected).length) {
        return false
      }
      let items = 0

      Object.keys(this.selected).forEach((key) => {
        if (this.ranges[key]) {
          const values = this.selected[key].map((i) => Number(i))
          if (JSON.stringify(values) !== JSON.stringify(this.ranges[key])) {
            items++
          }
        } else if (this.selected[key].length > 0) {
          items++
        }
      })

      return items > 0
    },
  },
  watch: {
    selected: {
      handler(newValue) {
        Object.keys(newValue).forEach((key) => {
          if (this.ranges[key]) {
            const values = this.selected[key].map((i) => Number(i))
            if (JSON.stringify(values) === JSON.stringify(this.ranges[key])) {
              delete newValue[key]
            }
          } else if (!this.selected[key].length) {
            delete newValue[key]
          }
        })

        const state = JSON.stringify(newValue)
        if (this.state !== state) {
          this.state = state
          this.$emit('input', newValue)
          this.onFilter(newValue)
        }
      },
      deep: true,
    },
  },
  methods: {
    async onFilter(selected) {
      this.loading = true
      try {
        const {data} = await this.$axios.post(this.url, selected)
        this.setAmount(data.rows)
      } catch (e) {
      } finally {
        this.loading = false
      }
    },
    onReset() {
      this.selected = {}
    },
    setAmount(items) {
      this.filters.forEach((filter) => {
        if (filter.type === 'range') {
          return
        }
        const item = items.find((i) => i.filter === filter.filter)
        if (item) {
          const values = {}
          item.values.forEach((i) => {
            values[i.value] = i.amount
          })
          filter.values.forEach((i) => {
            i.amount = values[i.value] || 0
          })
        } else {
          filter.values.forEach((i) => {
            i.amount = 0
          })
        }
      })
    },
    setFilters(items) {
      // Remove filters with only 1 option
      this.filters = items.filter((i) => i.type === 'range' || i.values.length > 1)
    },
    setRanges(items) {
      this.ranges = {}
      const ranges = items.filter((i) => i.type === 'range')
      if (ranges.length) {
        ranges.forEach((i) => {
          this.ranges[i.filter] = Object.values(i.values)
        })
      }
    },
  },
}
</script>
