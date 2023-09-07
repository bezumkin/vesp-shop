<template>
  <vesp-modal v-model="record" :url="url" :title="$t('security.profile')" size="lg" v-on="listeners">
    <template #form-fields>
      <form-user v-model="record" :show-group="false" :show-comment="false" :show-status="false" />
    </template>
  </vesp-modal>
</template>

<script>
import FormUser from '../../admin/components/forms/user'

export default {
  name: 'UserProfile',
  components: {FormUser},
  data() {
    return {
      url: 'user/profile',
      record: {},
    }
  },
  computed: {
    listeners() {
      return {...this.$listeners, 'after-submit': this.afterSubmit}
    },
  },
  mounted() {
    this.record = {...this.$auth.user}
  },
  methods: {
    afterSubmit({user}) {
      this.record = user
      this.$auth.setUser(user)
    },
  },
}
</script>
