<template>
  <b-row no-gutters>
    <b-col lg="3" class="pr-lg-4">
      <categories-tree v-model="filters.category" />
    </b-col>
    <b-col lg="9">
      <vesp-table
        :url="url"
        :fields="fields"
        :header-actions="headerActions"
        :table-actions="tableActions"
        :filters="filters"
        :row-class="rowClass"
      >
        <template #cell(title)="{item}">
          {{ $translate(item.translations) }}
          <div class="small">
            <b-link :href="getProductUrl(item)" target="_blank">
              {{ item.uri }}
            </b-link>
          </div>
        </template>
        <template #cell(category)="{item}">
          <b-link :to="getCategoryUrl(item)">
            {{ $translate(item.category.translations) }}
          </b-link>
        </template>
        <template #cell(file)="{value}">
          <b-img
            v-if="value.id"
            :key="value.id"
            :src="$image(value, {w: 100, h: 75, fit: 'crop'})"
            :srcset="$image(value, {w: 200, h: 150, fit: 'crop'}) + ' 2x'"
            width="100"
            height="75"
            alt=""
            rounded
          />
        </template>
      </vesp-table>
      <nuxt-child />
    </b-col>
  </b-row>
</template>

<script>
import CategoriesTree from '@/components/categories/tree.vue'

export const url = 'admin/products'
export default {
  name: 'ProductsPage',
  components: {CategoriesTree},
  validate({app}) {
    return app.$hasScope('products')
  },
  data() {
    return {
      url,
      filters: {
        query: '',
        category: 0,
      },
      sort: 'id',
      dir: 'asc',
    }
  },
  head() {
    return {
      title: [this.$t('models.product.title_many'), this.$t('project')].join(' / '),
    }
  },
  computed: {
    headerActions() {
      return [{route: 'products-create', icon: 'plus', title: this.$t('actions.create')}]
    },
    tableActions() {
      return [
        {route: 'products-edit-id', icon: 'edit', title: this.$t('actions.edit')},
        {function: 'onDelete', icon: 'times', title: this.$t('actions.delete'), variant: 'danger'},
      ]
    },
    fields() {
      return [
        {key: 'id', label: this.$t('components.table.columns.id'), sortable: true},
        {key: 'file', label: ''},
        {key: 'article', label: this.$t('models.product.article'), sortable: true},
        {key: 'category', label: this.$t('models.product.category')},
        {key: 'title', label: this.$t('models.product.title'), sortable: true},
        {key: 'price', label: this.$t('models.product.price'), sortable: true},
        /* {
          key: 'updated_at',
          label: this.$t('components.table.columns.updated_at'),
          formatter: this.$options.filters.datetime,
          sortable: true,
        }, */
        {
          key: 'created_at',
          label: this.$t('components.table.columns.created_at'),
          formatter: this.$options.filters.datetime,
          sortable: true,
        },
      ]
    },
  },
  methods: {
    rowClass(item) {
      return item && !item.active ? 'text-muted' : ''
    },
    getProductUrl(item) {
      return this.$config.SITE_URL + item.uri
    },
    getCategoryUrl(item) {
      return {name: 'categories-edit-id', params: {id: item.id}}
    },
  },
}
</script>
