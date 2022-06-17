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
export const url = 'admin/categories'
export default {
  name: 'CategoriesPage',
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
      title: [this.$t('models.category.title_many'), this.$t('project')].join(' / '),
    }
  },
  computed: {
    headerActions() {
      return [{route: 'categories-create', icon: 'plus', title: this.$t('actions.create')}]
    },
    tableActions() {
      return [
        {route: 'categories-edit-id', icon: 'edit', title: this.$t('actions.edit')},
        {function: 'onDelete', icon: 'times', title: this.$t('actions.delete'), variant: 'danger'},
      ]
    },
    fields() {
      return [
        {key: 'id', label: this.$t('components.table.columns.id'), sortable: true},
        {key: 'title', label: this.$t('models.category.title'), sortable: true},
        {key: 'products_count', label: this.$t('models.category.products'), sortable: true},
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
