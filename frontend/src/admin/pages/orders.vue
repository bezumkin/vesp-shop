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
      sort: 'num',
      dir: 'desc',
    }
  },
  head() {
    return {
      title: [this.$t('models.order.title_many'), this.$t('project')].join(' / '),
    }
  },
  computed: {
    headerActions() {
      return [{route: 'orders-create', icon: 'plus', title: this.$t('actions.create')}]
    },
    tableActions() {
      return [
        {route: 'orders-edit-id', icon: 'edit', title: this.$t('actions.edit')},
        {function: 'onDelete', icon: 'times', title: this.$t('actions.delete'), variant: 'danger'},
      ]
    },
    fields() {
      return [
        {key: 'num', label: this.$t('models.order.num'), sortable: true},
        {key: 'user.fullname', label: this.$t('models.order.user')},
        {key: 'total', label: this.$t('models.order.total'), sortable: true, formatter: this.$price},
        {key: 'discount', label: this.$t('models.order.discount'), formatter: this.$price, sortable: true},
        {key: 'order_products_count', label: this.$t('models.order.products'), sortable: true},
        {
          key: 'created_at',
          label: this.$t('models.order.created_at'),
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
