<template>
  <b-navbar variant="light" toggleable="md">
    <b-container class="flex-nowrap">
      <b-navbar-brand :to="{name: 'index'}">
        <b-img src="~/assets/images/logo-vesp.svg" width="152" height="20" alt="Vesp" class="mw-100" />
      </b-navbar-brand>

      <div class="ml-auto">
        <b-button v-if="!$auth.loggedIn" @click="$store.commit('showLogin')">
          <fa icon="right-to-bracket" />
        </b-button>
        <b-dropdown v-else variant="light">
          <template #button-content>
            <b-img
              v-if="$auth.user.file"
              :src="$image($auth.user.file, {w: 50, h: 50})"
              class="rounded-circle"
              width="25"
              height="25"
            />
            <fa v-else icon="user" />
          </template>
          <b-dropdown-text>{{ $auth.user.fullname }}</b-dropdown-text>
          <b-dropdown-divider />
          <b-dropdown-item :to="{name: 'user-orders'}">{{ $t('models.order.title_many') }}</b-dropdown-item>
          <b-dropdown-item :to="{name: 'user-profile'}">{{ $t('security.profile') }}</b-dropdown-item>
          <b-dropdown-divider />
          <b-dropdown-item link-class="d-flex justify-content-between align-items-center" @click="onLogout">
            {{ $t('security.logout') }}
            <fa icon="right-from-bracket" />
          </b-dropdown-item>
        </b-dropdown>
      </div>

      <b-button variant="primary" :disabled="!products" class="ml-2" @click="$store.commit('showCart')">
        <fa icon="cart-shopping" />
        <span v-if="total" style="white-space: nowrap">{{ $price(total) }}</span>
      </b-button>
    </b-container>

    <b-modal
      v-if="$store.state.showCart"
      visible
      :title="$t('cart.cart')"
      hide-footer
      size="lg"
      @hidden="$store.commit('showCart')"
    >
      <cart />
      <order />
    </b-modal>

    <app-login v-if="!$auth.loggedIn && $store.state.showLogin" @hidden="$store.commit('showLogin')" />
  </b-navbar>
</template>

<script>
import Cart from '~/components/cart'
import Order from '~/components/order'
import AppLogin from '~/components/app/login'

export default {
  name: 'AppNavbar',
  components: {AppLogin, Order, Cart},
  computed: {
    total() {
      return this.$store.getters.cartTotal
    },
    products() {
      return this.$store.getters.cartProducts
    },
  },
  watch: {
    '$auth.loggedIn'(newValue) {
      if (newValue && this.$route.name.startsWith('user')) {
        window.location.reload()
      }
    },
  },
  methods: {
    async onLogout() {
      await this.$auth.logout()
    },
  },
}
</script>
