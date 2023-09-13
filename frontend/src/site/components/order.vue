<template>
  <div>
    <b-overlay v-if="total" :show="loading" opacity="0.5" class="mt-4 pt-4 border-top">
      <h5>{{ $t('order.order') }}</h5>

      <div v-if="!$auth.loggedIn" class="text-center py-2">
        <b-button variant="primary" @click="$store.commit('showLogin')">{{ $t('order.login') }}</b-button>
      </div>
      <b-form-group v-else>
        <b-select v-model="addressId" :disabled="!addresses.length">
          <b-form-select-option :value="null">{{ $t('order.new_address') }}</b-form-select-option>
          <b-form-select-option v-for="item in addresses" :key="item.id" :value="item.id">
            {{ item.full_address }}
          </b-form-select-option>
        </b-select>
      </b-form-group>

      <b-form @submit.prevent="onSubmit">
        <b-form-group :label="$t('order.receiver')">
          <b-form-input v-model="form.receiver" autofocus required :disabled="addressId !== null" />
        </b-form-group>

        <b-row>
          <b-col md="6">
            <b-form-group :label="$t('order.email')">
              <b-form-input v-model="form.email" type="email" required :disabled="addressId !== null" />
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group :label="$t('order.phone')">
              <b-form-input v-model="form.phone" :disabled="addressId !== null" />
            </b-form-group>
          </b-col>
        </b-row>

        <b-form-group :label="$t('order.company')">
          <b-form-input v-model="form.company" :disabled="addressId !== null" />
        </b-form-group>

        <b-row>
          <b-col md="8">
            <b-form-group :label="$t('order.country')">
              <b-form-input v-model="form.country" :disabled="addressId !== null" />
            </b-form-group>
          </b-col>
          <b-col md="4">
            <b-form-group :label="$t('order.zip')">
              <b-form-input v-model="form.zip" type="number" min="0" max="999999" :disabled="addressId !== null" />
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col md="4">
            <b-form-group :label="$t('order.city')">
              <b-form-input v-model="form.city" :disabled="addressId !== null" />
            </b-form-group>
          </b-col>
          <b-col md="8">
            <b-form-group :label="$t('order.address')">
              <b-form-input v-model="form.address" :disabled="addressId !== null" />
            </b-form-group>
          </b-col>
        </b-row>

        <b-form-group :label="$t('order.comment')">
          <b-form-textarea v-model="form.comment" rows="5" />
        </b-form-group>

        <b-form-group :label="$t('order.payment')">
          <b-button-group>
            <b-button variant="light" :pressed="!payment" @click="payment = null">
              {{ $t('order.payment_null') }}
            </b-button>
            <b-button
              v-for="service in services"
              :key="service"
              :pressed="payment === service"
              variant="light"
              @click="payment = service"
            >
              <b-img v-if="service === 'yookassa'" src="~/assets/images/logo-yookassa.svg" height="50" />
              <b-img v-else-if="service === 'raiffeisen'" src="~/assets/images/logo-sbp.svg" height="50" />
              <span v-else>{{ service }}</span>
            </b-button>
          </b-button-group>
        </b-form-group>

        <div class="text-right">
          <b-button variant="primary" type="submit" :disabled="loading">
            {{ $t('actions.submit') }}
            <b-spinner v-if="loading" small />
          </b-button>
        </div>
      </b-form>
    </b-overlay>

    <b-modal v-if="qr !== null" visible hide-footer hide-header @hidden="onQR">
      <template #default="{close}">
        <div class="text-center">
          <b-img :src="qr.image" width="300" height="300" />
          <b-button variant="primary" class="mt-4" @click="close">
            {{ $t('order.payment_confirm_qr') }}
          </b-button>
        </div>
      </template>
    </b-modal>
  </div>
</template>

<script>
export default {
  name: 'Order',
  data() {
    return {
      loading: false,
      addresses: [],
      addressId: null,
      payment: null,
      form: {
        receiver: '',
        phone: '',
        email: '',
        company: '',
        address: '',
        country: '',
        city: '',
        zip: '',
        comment: '',
      },
      qr: null,
    }
  },
  async fetch() {
    if (this.$auth.loggedIn) {
      this.loading = true
      try {
        const {data} = await this.$axios.get('user/addresses')
        this.addresses = data.rows
        if (data.rows.length > 0) {
          this.addressId = data.rows[0].id
        } else if (!Object.values(this.form).join('')) {
          Object.keys(this.form).forEach((key) => {
            if (key === 'receiver' && this.$auth.user.fullname) {
              this.form.receiver = this.$auth.user.fullname
            } else if (this.$auth.user[key]) {
              this.form[key] = this.$auth.user[key]
            }
          })
        }
      } catch (e) {
      } finally {
        this.loading = false
      }
    }
  },
  computed: {
    total() {
      return this.$store.getters.cartTotal
    },
    services() {
      return this.$config.PAYMENT_SERVICES.split(',').map((i) => i.trim().toLowerCase())
    },
  },
  watch: {
    '$auth.loggedIn'(newValue) {
      if (newValue) {
        this.$fetch()
      }
    },
    addressId(newValue) {
      const keys = Object.keys(this.form)
      if (newValue) {
        const address = this.addresses.find((i) => i.id === newValue)
        if (address) {
          keys.forEach((key) => {
            if (address[key]) {
              this.form[key] = address[key]
            }
          })
        }
      } else {
        keys.forEach((key) => {
          this.form[key] = ''
        })
      }
    },
  },
  mounted() {
    if (this.services.length) {
      this.payment = this.services[0]
    }
  },
  methods: {
    async onSubmit() {
      this.loading = true
      try {
        const id = await this.$store.dispatch('getCartId', false)
        const params = {...this.form, address_id: this.addressId, payment: this.payment}
        const {data} = await this.$axios.put('web/orders/' + id, params)
        await this.$store.dispatch('deleteCart')

        if (data.payment) {
          if (data.payment.qr) {
            this.qr = {uuid: data.uuid, image: data.payment.qr}
          } else if (data.payment.redirect) {
            window.location = data.payment.redirect
          }
        } else {
          this.$store.commit('showCart', false)
          this.$router.push({name: 'orders-uuid', params: {uuid: data.uuid}})
        }
      } catch (e) {
        console.error(e)
      } finally {
        this.loading = false
      }
    },
    onQR() {
      this.$store.commit('showCart', false)
      this.$router.push({name: 'orders-uuid', params: {uuid: this.qr.uuid}})
    },
  },
}
</script>
