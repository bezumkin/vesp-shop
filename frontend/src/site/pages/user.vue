<template>
  <div>
    <b-nav tabs class="mt-4">
      <b-nav-item :to="{name: 'user-orders'}">{{ $t('models.order.title_many') }}</b-nav-item>
      <b-nav-item :to="{name: 'user-profile'}">{{ $t('security.profile') }}</b-nav-item>
    </b-nav>
    <div class="mt-4">
      <nuxt-child />
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserPage',
  validate({app}) {
    return app.$auth.loggedIn
  },
  watch: {
    '$auth.loggedIn'(newValue) {
      if (!newValue) {
        this.$router.replace({name: 'index'})
      }
    },
  },
}
</script>
