<template>
  <div>
    <vesp-table
      :url="url"
      :fields="fields"
      :header-actions="headerActions"
      :table-actions="tableActions"
      :filters="filters"
      :sort="sort"
      :dir="dir"
      :row-class="rowClass"
    >
      <template #cell(title)="{item}">
        {{ $translate(item.translations) }}
        <div class="small text-muted">{{ item.uri }}</div>
      </template>
      <template #cell(parent)="{item}">
        <template v-if="item.parent">
          {{ $translate(item.parent.translations) }}
        </template>
      </template>
      <template #cell(file)="{value}">
        <b-img
          v-if="value.id"
          :key="value.id"
          :src="$image(value, {w: 150, h: 75, fit: 'crop'})"
          :srcset="$image(value, {w: 300, h: 150, fit: 'crop'}) + ' 2x'"
          width="150"
          height="75"
          alt=""
          class="rounded"
        />
      </template>
    </vesp-table>
    <nuxt-child />
  </div>
</template>

<script>
export const url = 'admin/categories'
export default {
  name: 'PageCategories',
  data() {
    return {
      url,
      filters: {
        query: '',
      },
      sort: 'id',
      dir: 'asc',
    }
  },
  head() {
    return {
      title: [this.$t('models.category.title_many'), this.$t('project')].join(' / '),
    }
  },
  computed: {
    headerActions() {
      return [{route: 'categories-create', icon: 'plus', title: this.$t('actions.create')}]
    },
    tableActions() {
      return [
        {route: 'categories-edit-id', icon: 'edit', title: this.$t('actions.edit')},
        {function: 'onDelete', icon: 'times', title: this.$t('actions.delete'), variant: 'danger'},
      ]
    },
    fields() {
      return [
        {key: 'id', label: this.$t('components.table.columns.id'), sortable: true},
        {key: 'file', label: this.$t('models.category.file')},
        {key: 'title', label: this.$t('models.category.title'), sortable: true},
        {key: 'parent', label: this.$t('models.category.parent')},
        {key: 'products_count', label: this.$t('models.product.title_many')},
      ]
    },
  },
  methods: {
    rowClass(item) {
      return item && !item.active ? 'text-muted' : ''
    },
  },
}
</script>
