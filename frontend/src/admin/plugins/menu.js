export default [
  {
    name: 'products',
    title: 'models.product.title_many',
    scope: 'products',
  },
  {
    name: 'categories',
    title: 'models.category.title_many',
    scope: 'products',
  },
  {
    name: 'orders',
    title: 'models.order.title_many',
    scope: 'orders',
  },
  {
    name: 'users',
    title: 'models.user.title_many',
    scope: 'users',
    children: [
      {
        name: 'users-roles',
        title: 'models.user_role.title_many',
        scope: 'users',
      },
    ],
  },
]
