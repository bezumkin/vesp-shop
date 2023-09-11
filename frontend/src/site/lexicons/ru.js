import Ru from '../../admin/lexicons/ru'

Ru.filters = {
  category: 'Категория',
  price: 'Цена',
  weight: 'Вес',
  made_in: 'Страна',
  new: 'Новый',
  favorite: 'Особенный',
  popular: 'Популярный',
  boolean: {
    true: 'Да',
    false: 'Нет',
  },
  actions: {
    reset: 'Сбросить',
  },
  no_results: 'Ничего не найдено',
}

Ru.cart = {
  cart: 'Корзина',
  clear: 'Очистить корзину',
  empty: 'Ваша корзина пуста',
  total: 'Итого',
  total_pieces: `| <strong>{amount}</strong> товар | <strong>{amount}</strong> товара | <strong>{amount}</strong> товаров`,
  total_price: 'на сумму <strong>{total}</strong>',
}

Ru.security = {
  login: 'Вход',
  logout: 'Выход',
  profile: 'Профиль',
  register: 'Регистрация',
  reset: 'Забыл пароль',
}
Ru.order = {
  order: 'Заказ',
  login: 'Вход или регистрация',
  new_address: 'Создать новый адрес',
  receiver: 'Получатель',
  company: 'Компания',
  phone: 'Телефон',
  email: 'Email',
  country: 'Страна',
  zip: 'Индекс',
  city: 'Город',
  address: 'Адрес',
  comment: 'Комментарий',
  title: 'Заказ № <strong>{num}</strong> от <strong>{date}</strong>',
}

export default Ru
