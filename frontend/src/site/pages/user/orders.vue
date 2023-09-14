<template>
  <div>
    <vesp-table :url="url" :fields="fields" :filters="filters" :table-actions="tableActions" :sort="sort" :dir="dir">
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
export const url = 'user/orders'
export default {
  name: 'UserOrdersPage',
  data() {
    return {
      url,
      sort: 'num',
      dir: 'desc',
      filters: {
        query: '',
      },
    }
  },
  computed: {
    tableActions() {
      return [{route: 'user-orders-view-uuid', icon: 'eye', title: this.$t('actions.view')}]
    },
    fields() {
      return [
        {key: 'num', label: this.$t('models.order.num'), sortable: true},
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
}
</script>
