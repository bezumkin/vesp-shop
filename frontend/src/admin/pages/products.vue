<template>
  <div>
    <vesp-table
      :url="url"
      :header-actions="headerActions"
      :table-actions="tableActions"
      :fields="fields"
      :filters="filters"
      :sort="sort"
      :dir="dir"
      :row-class="rowClass"
    />
    <nuxt-child />
  </div>
</template>

<script>
export const url = 'admin/products'
export default {
  name: 'ProductsPage',
  validate({app}) {
    return app.$hasScope('products')
  },
  data() {
    return {
      url,
      filters: {
        query: '',
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
        {key: 'sku', label: this.$t('models.product.sku'), sortable: true},
        {key: 'category.title', label: this.$t('models.product.category')},
        {key: 'title', label: this.$t('models.product.title'), sortable: true},
        {key: 'price', label: this.$t('models.product.price'), sortable: true},
        {
          key: 'updated_at',
          label: this.$t('components.table.columns.updated_at'),
          formatter: this.$options.filters.datetime,
          sortable: true,
        },
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
  },
}
</script>
