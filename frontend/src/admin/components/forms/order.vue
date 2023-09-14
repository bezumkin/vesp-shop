<template>
  <div>
    <b-row>
      <b-col md="5">
        <b-form-group :label="$t('models.order.num')">
          <b-input-group prepend="#">
            <b-form-input v-model.trim="record.num" :disabled="record.id > 0" autofocus />
          </b-input-group>
        </b-form-group>
      </b-col>
      <b-col md="7">
        <b-form-group :label="$t('models.order.created_at')">
          <vesp-input-date-picker v-model="record.created_at" type="datetime" :disabled="record.id > 0" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group :label="$t('models.order.user')">
      <vesp-input-combo-box v-model="record.user_id" url="admin/users" text-field="fullname" required>
        <template #default="{item}">
          {{ item.fullname }}
          <div class="small text-muted">{{ item.username }}</div>
        </template>
      </vesp-input-combo-box>
    </b-form-group>

    <b-form-group :label="$t('models.order.address')">
      <vesp-input-combo-box
        :key="'address-' + record.user_id"
        v-model="record.address_id"
        :url="record.user_id ? 'admin/users/' + record.user_id + '/addresses' : null"
        :disabled="!record.user_id"
        text-field="full_address"
        sort="id"
        @select="onSelectAddress"
      >
        <template #default="{item}">
          {{ item.full_address }}
          <div class="small text-muted">{{ item.receiver }}</div>
        </template>
      </vesp-input-combo-box>
    </b-form-group>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.order.cart')">
          <b-form-input :value="$price(record.cart)" disabled />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.order.discount')">
          <b-input-group prepend="-" :append="$t('shop.currency')">
            <b-form-input v-model="record.discount" type="number" min="0" step="0.01" />
          </b-input-group>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.order.total')">
          <b-input-group prepend="=">
            <b-form-input :value="$price(total)" disabled />
          </b-input-group>
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.order.weight')">
          <b-form-input :value="$weight(record.weight)" disabled />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group :label="$t('models.order.comment')">
      <b-form-textarea v-model.trim="record.comment" rows="5" />
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormOrder',
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
    total() {
      const total = Number(this.record.cart) - Number(this.record.discount)
      return Number(total < 0 ? 0 : total).toFixed(2)
    },
  },
  watch: {
    'record.user_id'() {
      this.record.address_id = null
    },
  },
  methods: {
    onSelectAddress(item) {
      this.record.address = {...item}
    },
  },
}
</script>
