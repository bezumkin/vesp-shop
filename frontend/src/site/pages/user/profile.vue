<template>
  <div>
    <b-form @submit.prevent="onSubmit">
      <b-overlay :show="loading" opacity="0.5">
        <form-user v-model="record" :show-group="false" :show-comment="false" :show-status="false" />
      </b-overlay>
      <div class="text-center mt-3">
        <b-button variant="primary" type="submit" :disabled="loading">
          {{ $t('actions.submit') }}
        </b-button>
      </div>
    </b-form>
  </div>
</template>

<script>
import FormUser from '../../../admin/components/forms/user'

export default {
  name: 'UserProfilePage',
  components: {FormUser},
  data() {
    return {
      loading: false,
      url: 'user/profile',
      record: {},
    }
  },
  mounted() {
    this.record = {...this.$auth.user}
  },
  methods: {
    async onSubmit() {
      this.loading = true
      try {
        const {data} = await this.$axios.patch('user/profile', this.record)
        this.record = {...data.user}
        this.$auth.setUser(this.record)
      } catch (e) {
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
