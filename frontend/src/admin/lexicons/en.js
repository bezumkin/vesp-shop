export default {
  project: 'Vesp',
  greetings: {
    title: 'Welcome!',
    message: 'Select a menu item to get started',
  },
  actions: {
    files: 'Files',
    upload: 'Upload',
  },
  security: {
    username: 'Username',
    password: 'Password',
    login: 'Login',
    logout: 'Logout',
    greetings: 'Hello!',
    goodbye: 'Bye, bye...',
    profile: 'Profile',
    success_update_message: 'Your profile has been updated successfully!',
  },
  models: {
    user: {
      title_one: 'User',
      title_many: 'Users',
      username: 'Username',
      fullname: 'Fullname',
      password: 'Password',
      email: 'Email',
      active: 'Active',
      role: 'Role',
    },
    user_role: {
      title_one: 'User Role',
      title_many: 'Users Roles',
      title: 'Title',
      scope: 'Scope',
      add_scope: 'Add scopes',
    },
    category: {
      title_one: 'Category',
      title_many: 'Categories',
      title: 'Title',
      description: 'Description',
      alias: 'Alias',
      products: 'Products',
      active: 'Active',
    },
    product: {
      title_one: 'Product',
      title_many: 'Products',
      title: 'Title',
      description: 'Description',
      alias: 'Alias',
      sku: 'SKU',
      price: 'Price',
      category: 'Category',
      active: 'Active',
    },
  },
  errors: {
    security: {
      inactive: 'Your account is not active',
      wrong: 'Wrong username or password',
    },
    category: {
      alias_exists: 'This alias already exists, please specify another one',
    },
    product: {
      alias_exists: 'Another product in this category is already using this alias',
    },
  },
}
