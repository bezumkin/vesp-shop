<template>
  <div>
    <b-form-group :label="$t('models.order.name')">
      <b-form-input v-model.trim="record.name" required autofocus />
    </b-form-group>

    <b-form-group :label="$t('models.order.email')">
      <b-form-input v-model.trim="record.email" required />
    </b-form-group>

    <b-row>
      <b-col md="4">
        <b-form-group :label="$t('models.order.post')">
          <b-form-input v-model.trim="record.post" type="number" min="0" max="999999" />
        </b-form-group>
      </b-col>
      <b-col md="8">
        <b-form-group :label="$t('models.order.city')">
          <b-form-input v-model.trim="record.city" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group :label="$t('models.order.address')">
      <b-form-input v-model.trim="record.address" />
    </b-form-group>

    <b-row align-v="center">
      <b-col cols="8">
        <b-form-group :label="$t('models.order.total')">
          <b-form-input v-model.trim="record.total" readonly />
        </b-form-group>
      </b-col>
      <b-col cols="4" class="text-right">
        <b-form-checkbox v-model.trim="record.paid">
          {{ $t('models.order.paid') }}
        </b-form-checkbox>
      </b-col>
    </b-row>

    <div class="mt-2 pt-2 border-top">
      <b-row v-for="product in record.order_products" :key="product.id" class="mt-3" align-v="center">
        <b-col cols="6">
          <b-link v-if="product.product_id" :to="{name: 'products-edit-id', params: {id: product.product_id}}">
            {{ product.title }}
          </b-link>
          <div v-else>{{ product.title }}</div>
          <div class="small text-muted">{{ product.price }} руб. x {{ product.amount }}</div>
        </b-col>
        <b-col cols="6" class="text-right">{{ product.total }} руб.</b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FormProduct',
  props: {
    value: {
      type: Object,
      required: true,
    },
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
  },
}
</script>
