<template>
  <vesp-table
    :url="url"
    :fields="fields"
    :table-actions="tableActions"
    :filters="filters"
    :on-load="onLoad"
    primary-key="category_id"
  >
    <template #header>
      <b-row class="align-items-center mb-3">
        <b-col md="6">
          <input-category v-model="category" :filter-props="{exclude}" />
        </b-col>
        <b-col md="4" offset-md="2" class="mt-2 mt-md-0">
          <b-input-group>
            <b-form-input v-model.trim="filters.query" :placeholder="$t('components.table.query')" />
            <template #append>
              <b-button :disabled="!filters.query" @click="filters.query = ''">
                <fa icon="times" />
              </b-button>
            </template>
          </b-input-group>
        </b-col>
      </b-row>
    </template>
    <template #cell(title)="{item}">
      {{ $translate(item.category.translations) }}
      <div class="small text-muted">{{ item.category.uri }}</div>
    </template>
    <template #cell(file)="{item}">
      <b-img
        v-if="item.category.file"
        :key="item.category.file.id"
        :src="$image(item.category.file, {w: 150, h: 75, fit: 'crop'})"
        :srcset="$image(item.category.file, {w: 300, h: 150, fit: 'crop'}) + ' 2x'"
        width="150"
        height="75"
        alt=""
        class="rounded"
      />
    </template>
  </vesp-table>
</template>

<script>
import InputCategory from '@/components/inputs/category.vue'

export default {
  name: 'ProductCategories',
  components: {InputCategory},
  props: {
    productId: {
      type: [Number, String],
      required: true,
    },
  },
  data() {
    return {
      exclude: '',
      category: null,
      filters: {
        query: '',
      },
    }
  },
  computed: {
    url() {
      return `admin/product/${this.productId}/categories`
    },
    fields() {
      return [
        {key: 'category_id', label: this.$t('components.table.columns.id')},
        {key: 'file', label: this.$t('models.category.file')},
        {key: 'title', label: this.$t('models.category.title')},
      ]
    },
    tableActions() {
      return [{function: 'onDelete', icon: 'times', title: this.$t('actions.delete'), variant: 'danger'}]
    },
  },
  watch: {
    category(newValue) {
      if (newValue) {
        this.onSelect(newValue)
      }
    },
  },
  methods: {
    onLoad(data) {
      this.exclude = data.rows.map((i) => i.category_id)
      return data
    },
    async onSelect(id) {
      try {
        await this.$axios.put(this.url, {category_id: id})
      } catch (e) {}
      this.$nextTick(() => {
        this.category = null
      })
      this.$root.$emit(`app::${this.url.split('/').join('-')}::update`)
    },
  },
}
</script>
