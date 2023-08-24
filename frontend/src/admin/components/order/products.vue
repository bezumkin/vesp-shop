<template>
  <div>
    <b-form-group>
      <vesp-input-combo-box
        v-model="product_id"
        url="admin/products"
        :placeholder="$t('models.product.title_one')"
        sort="rank"
        @select="onAdd"
      >
        <template #default="{item}">
          <b-row align-v="center" class="w-100">
            <b-col>
              <div>{{ $translate(item.translations) }}</div>
              <div class="small text-muted">{{ item.uri }}</div>
            </b-col>
            <b-col class="text-md-right">{{ $price(item.price) }}</b-col>
          </b-row>
        </template>
      </vesp-input-combo-box>
    </b-form-group>

    <div class="overflow-auto vesp-table">
      <table v-if="totalAmount > 0" class="table b-table">
        <thead>
          <tr>
            <th>{{ $t('models.order_product.product') }}</th>
            <!--<th>{{ $t('models.order_product.weight') }}</th>-->
            <th style="width: 125px">{{ $t('models.order_product.amount') }}</th>
            <th style="width: 100px">{{ $t('models.order_product.price') }}</th>
            <th style="width: 125px">{{ $t('models.order_product.discount') }}</th>
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
                :options="{colors: item.product.colors, variants: item.product.variants}"
                class="small"
              />
            </td>
            <!--<td>{{ $weight(item.weight) }}</td>-->
            <td>
              <b-form-spinbutton v-model="item.amount" min="1" />
            </td>
            <td>{{ $price(item.price) }}</td>
            <td>
              <b-form-input v-model.number="item.discount" min="0" type="number" :step="0.01" :max="item.price" />
            </td>
            <td>{{ $price(getProductCost(item) || '0') }}</td>
            <td class="p-0 actions">
              <b-button variant="danger" size="sm" @click="onDelete(idx)">
                <fa icon="times" class="fa-fw" />
              </b-button>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th></th>
            <!--<th>{{ $weight(totalWeight) }}</th>-->
            <th class="text-center">{{ totalAmount }}</th>
            <th>{{ $price(totalCart) }}</th>
            <th>- {{ $price(totalDiscount) }}</th>
            <th colspan="2">{{ $price(total) }}</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</template>

<script>
import ProductOptions from '@/components/product/options.vue'
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
    myValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
    products() {
      return this.myValue.order_products || []
    },
    totalAmount() {
      let res = 0
      this.products.forEach((i) => {
        res += i.amount
      })
      return res
    },
    totalWeight() {
      let res = 0
      this.products.forEach((i) => {
        res += i.amount * i.weight
      })
      return res.toFixed(2)
    },
    totalCart() {
      let res = 0
      this.products.forEach((i) => {
        res += i.amount * i.price
      })
      return res.toFixed(2)
    },
    totalDiscount() {
      let res = 0
      this.products.forEach((i) => {
        res += i.amount * i.discount
      })
      return res.toFixed(2)
    },
    total() {
      let res = 0
      this.products.forEach((i) => {
        res += this.getProductCost(i)
      })
      return res.toFixed(2)
    },
  },
  watch: {
    totalWeight(newValue) {
      this.myValue.weight = newValue
    },
    total(newValue) {
      this.myValue.cart = newValue
    },
  },
  methods: {
    onDelete(idx) {
      this.myValue.order_products.splice(idx, 1)
    },
    getProductCost(i) {
      let res = i.amount * (i.price - i.discount)
      if (res < 0) {
        res = 0
      }
      return res
    },
    onAdd(item) {
      this.myValue.order_products.push({
        id: null,
        product_id: item.id,
        title: this.$translate(item.translations),
        amount: 1,
        price: item.price,
        weight: item.weight,
        cost: item.price,
        discount: 0,
        options: null,
        product: item,
      })
      this.$nextTick(() => {
        this.product_id = null
      })
    },
  },
}
</script>
