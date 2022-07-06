export const state = () => ({
  cart: [],
})

export const mutations = {
  addToCart(state, product) {
    state.cart.push({id: product.id, title: product.title, price: product.price})
    this.dispatch('saveCart')
  },
  removeFromCart(state, product) {
    const idx = state.cart.findIndex((i) => i.id === product.id)
    if (idx > -1) {
      state.cart.splice(idx, 1)
    }
    this.dispatch('saveCart')
  },
  clearCart(state) {
    state.cart = []
    this.dispatch('saveCart')
  },
}

export const getters = {
  cartProducts(state) {
    return state.cart.length
  },
  cartTotal(state) {
    let total = 0
    state.cart.forEach((i) => {
      total += i.price
    })
    return total ? total.toFixed(2) : 0
  },
  products(state) {
    const products = {}
    state.cart.forEach((i) => {
      if (!products[i.id]) {
        products[i.id] = {...i, amount: 1}
      } else {
        products[i.id].amount += 1
      }
    })
    return Object.values(products)
  },
}

export const actions = {
  saveCart({state}) {
    localStorage.setItem('cart', JSON.stringify(state.cart))
  },
  loadCart({state, commit}) {
    const products = JSON.parse(localStorage.getItem('cart')) || []
    products.forEach((product) => {
      commit('addToCart', product)
    })
  },
}
