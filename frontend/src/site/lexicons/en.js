import En from '../../admin/lexicons/en'

En.filters = {
  category: 'Category',
  price: 'Price',
  weight: 'Weight',
  made_in: 'Country',
  new: 'New',
  favorite: 'Favorite',
  popular: 'Popular',
  boolean: {
    true: 'Yes',
    false: 'No',
  },
  actions: {
    reset: 'Reset',
  },
  no_results: 'Nothing found',
}

En.cart = {
  cart: 'Cart',
  clear: 'Clear the cart',
  empty: 'Your shopping cart is empty',
  total: 'Total of',
  total_pieces: '| <strong>{amount}</strong> product | <strong>{amount}</strong> products',
  total_price: 'in the amount of <strong>{total}</strong>',
}

En.security = {
  login: 'Login',
  logout: 'Logout',
  profile: 'Profile',
  register: 'Register',
  reset: 'Forget password',
}

En.order = {
  order: 'Order',
  login: 'Login or registration',
  new_address: 'Create new address',
  receiver: 'Receiver',
  company: 'Company',
  phone: 'Phone',
  email: 'Email',
  country: 'Country',
  zip: 'Zip',
  city: 'City',
  address: 'Address',
  comment: 'Comment',
  title: 'Order #<strong>{num}</strong> from <strong>{date}</strong>',
}
export default En
