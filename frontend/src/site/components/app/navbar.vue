<template>
  <b-navbar variant="light" toggleable="md">
    <b-container class="flex-nowrap">
      <b-navbar-brand :to="{name: 'index'}">
        <b-img src="~/assets/images/logo-vesp.svg" width="152" height="20" alt="Vesp" class="mw-100" />
      </b-navbar-brand>

      <div class="ml-auto">
        <b-button v-if="!$auth.loggedIn" @click="showLogin = true">
          <fa icon="right-to-bracket" />
        </b-button>
        <b-dropdown v-else>
          <template #button-content>
            <fa icon="user" />
          </template>
          <b-dropdown-text>{{ $auth.user.fullname }}</b-dropdown-text>
          <b-dropdown-divider></b-dropdown-divider>
          <b-dropdown-item @click="showProfile = true">{{ $t('security.profile') }}</b-dropdown-item>
          <b-dropdown-item link-class="d-flex justify-content-between align-items-center" @click="onLogout">
            {{ $t('security.logout') }}
            <fa icon="right-from-bracket" />
          </b-dropdown-item>
        </b-dropdown>
      </div>

      <b-button variant="primary" :disabled="!products" class="ml-2" @click="showCart = true">
        <fa icon="cart-shopping" />
        <span v-if="total" style="white-space: nowrap">{{ $price(total) }}</span>
      </b-button>
    </b-container>

    <b-modal v-if="showCart" visible :title="$t('cart.cart')" hide-footer size="lg" @hidden="showCart = false">
      <cart />
      <order />
    </b-modal>

    <app-login v-if="!$auth.loggedIn && showLogin" @hidden="showLogin = false" />
    <user-profile v-else-if="$auth.loggedIn && showProfile" @hidden="showProfile = false" />
  </b-navbar>
</template>

<script>
import Cart from '~/components/cart'
import Order from '~/components/order'
import UserProfile from '~/components/user-profile'
import AppLogin from '~/components/app/login'

export default {
  name: 'AppNavbar',
  components: {AppLogin, Order, Cart, UserProfile},
  data() {
    return {
      showCart: false,
      showLogin: false,
      showProfile: false,
    }
  },
  computed: {
    total() {
      return this.$store.getters.cartTotal
    },
    products() {
      return this.$store.getters.cartProducts
    },
  },
  methods: {
    async onLogout() {
      await this.$auth.logout()
    },
  },
}
</script>
