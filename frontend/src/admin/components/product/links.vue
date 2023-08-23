<template>
  <vesp-table
    :url="url"
    :fields="fields"
    :table-actions="tableActions"
    :filters="filters"
    :on-load="onLoad"
    primary-key="link_id"
  >
    <template #header>
      <b-row class="align-items-center mb-3">
        <b-col md="6">
          <vesp-input-combo-box
            v-model="link"
            url="admin/products"
            sort="rank"
            :placeholder="$t('models.product.title_one')"
            :filter-props="{exclude}"
            :format-value="(item) => $translate(item.translations)"
          >
            <template #default="{item}">
              <div :class="{'text-muted': !item.active}">
                {{ $translate(item.translations) }}
                <div class="small text-muted">{{ item.uri }}</div>
              </div>
            </template>
          </vesp-input-combo-box>
        </b-col>
        <b-col md="4" offset-md="2" class="mt-2 mt-md-0">
          <b-input-group>
            <b-form-input v-model.trim="filters.query" :placeholder="$t('components.table.query')" />
            <template #append>
              <b-button :disabled="!filters.query" @click="filters.query = ''">
                <fa icon="times" />
              </b-button>
            </template>
          </b-input-group>
        </b-col>
      </b-row>
    </template>
    <template #cell(title)="{item}">
      {{ $translate(item.link.translations) }}
      <div class="small text-muted">{{ item.link.uri }}</div>
    </template>
    <template #cell(category)="{item}">
      {{ $translate(item.link.category.translations) }}
      <div class="small text-muted">{{ item.link.category.uri }}</div>
    </template>
    <template #cell(file)="{item}">
      <b-img
        v-if="item.link.file.id"
        :key="item.link.file.id"
        :src="$image(item.link.file, {w: 150, h: 75, fit: 'crop'})"
        :srcset="$image(item.link.file, {w: 300, h: 150, fit: 'crop'}) + ' 2x'"
        width="150"
        height="75"
        alt=""
        class="rounded"
      />
    </template>
  </vesp-table>
</template>

<script>
export default {
  name: 'ProductLinks',
  props: {
    productId: {
      type: [Number, String],
      required: true,
    },
  },
  data() {
    return {
      exclude: '',
      link: null,
      filters: {
        query: '',
      },
    }
  },
  computed: {
    url() {
      return `admin/product/${this.productId}/links`
    },
    fields() {
      return [
        {key: 'link_id', label: this.$t('components.table.columns.id')},
        {key: 'file', label: this.$t('models.product.file')},
        {key: 'title', label: this.$t('models.product.title')},
        {key: 'category', label: this.$t('models.category.title_one')},
      ]
    },
    tableActions() {
      return [{function: 'onDelete', icon: 'times', title: this.$t('actions.delete'), variant: 'danger'}]
    },
  },
  watch: {
    link(newValue) {
      if (newValue) {
        this.onSelect(newValue)
      }
    },
  },
  methods: {
    onLoad(data) {
      this.exclude = data.rows.map((i) => i.link_id)
      return data
    },
    async onSelect(id) {
      try {
        await this.$axios.put(this.url, {link_id: id})
      } catch (e) {}
      this.$nextTick(() => {
        this.link = null
      })
      this.$root.$emit(`app::${this.url.split('/').join('-')}::update`)
    },
  },
}
</script>
