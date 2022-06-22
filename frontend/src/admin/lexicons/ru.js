export default {
  project: 'Vesp',
  greetings: {
    title: 'Добро пожаловать!',
    message: 'Выберите пункт меню для начала работы',
  },
  actions: {
    files: 'Файлы',
    upload: 'Загрузка',
  },
  security: {
    username: 'Логин',
    password: 'Пароль',
    login: 'Вход',
    logout: 'Выход',
    greetings: 'Привет!',
    goodbye: 'Пока, пока...',
    profile: 'Профиль',
    success_update_message: 'Ваш профиль был успешно обновлён!',
  },
  models: {
    user: {
      title_one: 'Пользователь',
      title_many: 'Пользователи',
      username: 'Логин',
      fullname: 'Полное имя',
      password: 'Пароль',
      email: 'Email',
      active: 'Активно',
      role: 'Группа',
    },
    user_role: {
      title_one: 'Группа',
      title_many: 'Группы',
      title: 'Название',
      scope: 'Разрешения',
      add_scope: 'Добавить',
    },
    category: {
      title_one: 'Категория',
      title_many: 'Категории',
      title: 'Название',
      description: 'Описание',
      products: 'Товары',
      active: 'Включено',
    },
    product: {
      title_one: 'Товар',
      title_many: 'Товары',
      title: 'Название',
      description: 'Описание',
      sku: 'Артикул',
      price: 'Цена',
      category: 'Категория',
      active: 'Включено',
    },
  },
  errors: {
    security: {
      inactive: 'Ваш аккаунт неактивен',
      wrong: 'Неправильное имя или пароль',
    },
  },
}
