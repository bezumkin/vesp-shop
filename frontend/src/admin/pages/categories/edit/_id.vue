<script>
import Create, {url} from '../create'

export default {
  name: 'ProductEditPage',
  extends: Create,
  validate({params}) {
    return /^\d+$/.test(params.id)
  },
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get(url + '/' + params.id)
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
}
</script>
