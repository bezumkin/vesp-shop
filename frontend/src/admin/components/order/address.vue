<template>
  <div>
    <b-form-group>
      <div class="d-flex align-items-center">
        <vesp-input-combo-box
          :key="'address-' + myValue.user_id"
          v-model="myValue.address_id"
          :url="myValue.user_id ? 'admin/users/' + myValue.user_id + '/addresses' : null"
          :disabled="!myValue.user_id"
          :placeholder="$t('models.user_address.title_one')"
          text-field="full_address"
          sort="id"
          class="mr-3 flex-grow-1"
          @select="onSelect"
        >
          <template #default="{item}">
            {{ item.full_address }}
            <div class="small text-muted">{{ item.receiver }}</div>
          </template>
        </vesp-input-combo-box>
        <b-button :disabled="!myValue.address_id" class="ml-auto" @click="onCreate">
          {{ $t('actions.create') }}
        </b-button>
      </div>
    </b-form-group>

    <b-form-group :label="$t('models.user_address.receiver')">
      <b-form-input v-model.trim="myValue.address.receiver" :disabled="myValue.address_id > 0" />
    </b-form-group>

    <b-form-group :label="$t('models.user_address.company')">
      <b-form-input v-model.trim="myValue.address.company" :disabled="myValue.address_id > 0" />
    </b-form-group>

    <b-form-group :label="$t('models.user_address.address')">
      <b-form-input v-model.trim="myValue.address.address" :disabled="myValue.address_id > 0" />
    </b-form-group>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.user_address.zip')">
          <b-form-input v-model.trim="myValue.address.zip" :disabled="myValue.address_id > 0" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.user_address.city')">
          <b-form-input v-model.trim="myValue.address.city" :disabled="myValue.address_id > 0" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.user_address.email')">
          <b-form-input v-model.trim="myValue.address.email" :disabled="myValue.address_id > 0" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.user_address.phone')">
          <b-form-input v-model.trim="myValue.address.phone" :disabled="myValue.address_id > 0" />
        </b-form-group>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  name: 'OrderAddress',
  props: {
    value: {
      type: Object,
      required: true,
    },
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
  },
  methods: {
    onCreate() {
      this.myValue.address_id = null
      this.myValue.address = {}
    },
    onSelect(item) {
      this.myValue.address = {...item}
    },
  },
}
</script>
