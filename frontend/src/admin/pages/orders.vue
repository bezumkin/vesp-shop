<template>
  <div>
    <vesp-table
      :url="url"
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
export const url = 'admin/orders'
export default {
  name: 'OrdersPage',
  validate({app}) {
    return app.$hasScope('orders')
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
      title: [this.$t('models.order.title_many'), this.$t('project')].join(' / '),
    }
  },
  computed: {
    tableActions() {
      return [
        {route: 'orders-edit-id', icon: 'edit', title: this.$t('actions.edit')},
        {function: 'onDelete', icon: 'times', title: this.$t('actions.delete'), variant: 'danger'},
      ]
    },
    fields() {
      return [
        {key: 'id', label: this.$t('components.table.columns.id')},
        {key: 'name', label: this.$t('models.order.name')},
        {key: 'total', label: this.$t('models.order.total')},
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
      return item && item.paid ? 'text-success' : ''
    },
  },
}
</script>
