<script>
export default {
  async asyncData({params, redirect, app}) {
    if (!app.$auth.loggedIn && params.username && params.code) {
      try {
        const {data} = await app.$axios.post('security/activate', params)
        if (data.token) {
          await app.$auth.setUserToken(data.token)
        }
      } catch (e) {}
    }
    redirect({name: 'index'})
  },
  render() {
    return null
  },
}
</script>
