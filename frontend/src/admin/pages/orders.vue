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
    >
      <template #cell(payment)="{value}">
        <div v-if="value.order_id" class="text-center">
          <fa v-if="value.paid === null" icon="minus" class="text-muted" :title="$t('models.payment.paid_null')" />
          <fa v-else-if="value.paid" icon="check" class="text-success" :title="$t('models.payment.paid_true')" />
          <fa v-else icon="times" class="text-danger" :title="$t('models.payment.paid_false')" />
        </div>
      </template>
    </vesp-table>
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
        {key: 'payment', label: this.$t('models.payment.title_one')},
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
