<script>
export default {
  name: 'UserIndexPage',
  asyncData({redirect}) {
    redirect({name: 'user-orders'})
  },
  render() {
    return null
  },
}
</script>
