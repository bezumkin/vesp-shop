export default ({app}, inject) => {
  inject('productLink', ({uri}) => {
    return uri ? ['', app.$config.PRODUCTS_PREFIX, uri].join('/') : ''
  })
}
