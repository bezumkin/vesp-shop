<template>
  <div>
    <div class="overflow-auto vesp-table">
      <table v-if="totalAmount > 0" class="table b-table">
        <thead>
          <tr>
            <th>{{ $t('models.order_product.product') }}</th>
            <th style="width: 125px">{{ $t('models.order_product.weight') }}</th>
            <th style="width: 125px">{{ $t('models.order_product.amount') }}</th>
            <th style="width: 100px">{{ $t('models.order_product.price') }}</th>
            <!--<th style="width: 125px">{{ $t('models.order_product.discount') }}</th>-->
            <th style="width: 125px">{{ $t('models.order_product.total') }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, idx) in products" :key="idx">
            <td class="pl-0">
              {{ item.title }}
              <product-options
                v-model="item.options"
                disabled
                :options="{colors: item.product.colors, variants: item.product.variants}"
                class="small"
              />
            </td>
            <td>{{ $weight(item.weight) }}</td>
            <td>{{ item.amount }}</td>
            <td>{{ $price(item.price) }}</td>
            <!--<td>{{ $price(item.discount) }}</td>-->
            <td>{{ $price(getProductCost(item) || '0') }}</td>
            <td class="p-0 actions"></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th></th>
            <th>{{ $weight(record.weight) }}</th>
            <th>{{ totalAmount }}</th>
            <th>{{ $price(record.cart) }}</th>
            <!--<th>{{ $price(record.discount) }}</th>-->
            <th colspan="2">{{ $price(record.total) }}</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</template>

<script>
import ProductOptions from '../../../admin/components/product/options'

export default {
  name: 'OrderProducts',
  components: {ProductOptions},
  props: {
    value: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      product_id: null,
    }
  },
  computed: {
    record: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
    products() {
      return this.record.order_products || []
    },
    totalAmount() {
      let res = 0
      this.products.forEach((i) => {
        res += i.amount
      })
      return res
    },
  },
  watch: {
    totalWeight(newValue) {
      this.record.weight = newValue
    },
    total(newValue) {
      this.record.cart = newValue
    },
  },
  methods: {
    getProductCost(i) {
      let res = i.amount * (i.price - i.discount)
      if (res < 0) {
        res = 0
      }
      return res
    },
  },
}
</script>
