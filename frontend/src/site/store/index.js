export const state = () => ({
  cartId: null,
  cartProducts: [],
  showLogin: false,
  showCart: false,
})

export const mutations = {
  cartId(state, payload) {
    state.cartId = payload
  },
  cartProducts(state, payload) {
    state.cartProducts = payload
  },
  showLogin(state, payload = null) {
    state.showLogin = payload === null ? !state.showLogin : Boolean(payload)
  },
  showCart(state, payload = null) {
    state.showCart = payload === null ? !state.showCart : Boolean(payload)
  },
}

export const getters = {
  cartProducts(state) {
    let amount = 0
    state.cartProducts.forEach((i) => {
      amount += i.amount
    })
    return amount
  },
  cartTotal(state) {
    let total = 0
    state.cartProducts.forEach((i) => {
      total += i.product.price * i.amount
    })
    return Number(total.toFixed(2))
  },
}

export const actions = {
  async getCartId({state, commit, dispatch}, create = true) {
    if (!state.cartId) {
      if (this.$auth.loggedIn && this.$auth.user.cart) {
        commit('cartId', this.$auth.user.cart)
      } else if (localStorage.getItem('cartId')) {
        commit('cartId', localStorage.getItem('cartId'))
      } else if (create) {
        try {
          const {data} = await this.$axios.put('web/cart')
          commit('cartId', data.id)
          dispatch('saveCartId')
        } catch (e) {}
      }
    }
    return state.cartId
  },
  async loadCart({dispatch, commit}) {
    const cartId = await dispatch('getCartId', false)
    if (cartId) {
      try {
        const {data} = await this.$axios.get('web/cart/' + cartId + '/products')
        commit('cartProducts', data.rows)
      } catch (e) {
        // Delete a non-existent cartId
        if (e.status === 404) {
          commit('cartId', null)
          dispatch('saveCartId')
        }
      }
    }
  },
  async addToCart({commit, dispatch}, item) {
    const cartId = await dispatch('getCartId')
    try {
      const params = {id: item.id, amount: item.amount || 1, options: item.options || null}
      const {data} = await this.$axios.put('web/cart/' + cartId + '/products', params)
      commit('cartProducts', data.rows)
    } catch (e) {}
  },
  async removeFromCart({state, commit, dispatch}, product) {
    const cartId = await dispatch('getCartId')
    try {
      const {data} = await this.$axios.delete('web/cart/' + cartId + '/products/' + product.product_key)
      commit('cartProducts', data.rows)
    } catch (e) {}
  },
  async changeAmount({state, commit, dispatch}, product) {
    const cartId = await dispatch('getCartId')
    if (product.amount <= 0) {
      return dispatch('removeFromCart', product)
    }
    try {
      const params = {amount: product.amount}
      const {data} = await this.$axios.patch('web/cart/' + cartId + '/products/' + product.product_key, params)
      commit('cartProducts', data.rows)
    } catch (e) {}
  },
  async deleteCart({state, commit, dispatch}) {
    if (!state.cartId) {
      return
    }
    try {
      await this.$axios.delete('web/cart/' + state.cartId)
      commit('cartId', null)
      commit('cartProducts', [])
      dispatch('saveCartId')

      if (this.$auth.loggedIn) {
        this.$auth.setUser({...this.$auth.user, cart: null})
      }
    } catch (e) {}
  },
  saveCartId({state}) {
    if (state.cartId) {
      localStorage.setItem('cartId', state.cartId)
    } else {
      localStorage.removeItem('cartId')
    }
  },
}
