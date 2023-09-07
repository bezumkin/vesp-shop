<template>
  <vesp-modal :url="url" :value="model" hide-header action-create="post" v-on="listeners">
    <template #form-fields>
      <b-tabs v-model="tab" pills justified content-class="pt-4">
        <b-tab :title="$t('security.login')" lazy>
          <form-login v-model="login" />
        </b-tab>
        <b-tab :title="$t('security.register')" lazy>
          <form-register v-model="register" />
        </b-tab>
        <b-tab :title="$t('security.reset')" lazy>
          <form-reset v-model="reset" />
        </b-tab>
      </b-tabs>
    </template>
  </vesp-modal>
</template>

<script>
import FormLogin from '~/components/forms/login'
import FormRegister from '~/components/forms/register'
import FormReset from '~/components/forms/reset'

export default {
  name: 'AppLogin',
  components: {FormReset, FormRegister, FormLogin},
  data() {
    return {
      tab: 0,
      login: {},
      register: {},
      reset: {},
    }
  },
  computed: {
    url() {
      return '/security/' + (!this.tab ? 'login' : this.tab === 1 ? 'register' : 'reset')
    },
    model() {
      return !this.tab ? this.login : this.tab === 1 ? this.register : this.reset
    },
    listeners() {
      return {...this.$listeners, 'after-submit': this.afterSubmit}
    },
  },
  methods: {
    afterSubmit(data) {
      if (!this.tab && data.token) {
        this.$auth.setUserToken(data.token)
      }
    },
  },
}
</script>
