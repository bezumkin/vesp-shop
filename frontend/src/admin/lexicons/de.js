export default {
  project: 'Vesp',
  greetings: {
    title: 'Herzlich willkommen!',
    message: 'Wählen Sie einen Menüpunkt, um loszulegen',
  },
  actions: {
    files: 'Dateien',
    upload: 'Hochladen',
  },
  security: {
    username: 'Nutzername',
    password: 'Passwort',
    login: 'Anmeldung',
    logout: 'Ausloggen',
    greetings: 'Hello!',
    goodbye: 'Auf Wiedersehen...',
    profile: 'Profil',
    success_update_message: 'Dein Profil wurde erfolgreich aktualisiert!',
  },
  models: {
    user: {
      title_one: 'Nutzer',
      title_many: 'Benutzer',
      username: 'Nutzername',
      fullname: 'Vollständiger Name',
      password: 'Passwort',
      email: 'Email',
      active: 'Aktiv',
      role: 'Rolle',
    },
    user_role: {
      title_one: 'Benutzer-Rolle',
      title_many: 'Benutzerrollen',
      title: 'Titel',
      scope: 'Umfang',
      add_scope: 'Bereiche hinzufügen',
    },
    category: {
      title_one: 'Kategorie',
      title_many: 'Kategorien',
      title: 'Titel',
      description: 'Beschreibung',
      products: 'Produkte',
      active: 'Aktiv',
    },
    product: {
      title_one: 'Produkt',
      title_many: 'Produkte',
      title: 'Titel',
      description: 'Beschreibung',
      sku: 'Artikelnummer',
      price: 'Preis',
      category: 'Kategorie',
      active: 'Aktiv',
    },
  },
  errors: {
    security: {
      inactive: 'Ihr Konto ist nicht aktiv',
      wrong: 'Benutzername oder Passwort falsch',
    },
  },
}
