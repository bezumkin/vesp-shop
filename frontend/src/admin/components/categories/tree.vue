<template>
  <div class="categories-tree">
    <div class="pb-3 mb-3 border-bottom">
      <b-button @click="createNode"> <fa icon="plus" class="fa-fw" /> {{ $t('actions.create') }} </b-button>
    </div>

    <div ref="roots" class="root-nodes" :data-id="0">
      <categories-node
        v-for="category in categories"
        :key="category.id"
        v-model="myValue"
        :node="category"
        :data-id="category.id"
        :sort-options="sortOptions"
        :sort-function="sortNodes"
      >
        <template #actions="{node}">
          <b-button variant="link" class="p-0" @click="editNode(node)">
            <b-spinner v-if="loading === node.id" small />
            <fa v-else icon="edit" class="fa-fw" />
          </b-button>
          <b-button variant="link" class="p-0 ml-1 text-danger" @click="deleteNode(node)">
            <fa icon="times" class="fa-fw" />
          </b-button>
        </template>
      </categories-node>
    </div>

    <vesp-modal
      v-model="record"
      :url="url"
      :update-key="updateKey"
      :visible="modalVisible"
      :title="$t('models.category.title_one')"
      @hidden="modalVisible = false"
    >
      <template #form-fields>
        <form-category v-model="record" />
      </template>
    </vesp-modal>
  </div>
</template>

<script>
import {Sortable} from 'sortablejs'
import CategoriesNode from './node'
import FormCategory from '@/components/forms/category.vue'

export default {
  name: 'CategoriesTree',
  components: {FormCategory, CategoriesNode},
  props: {
    value: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      url: 'admin/categories',
      categories: [],
      record: {},
      modalVisible: false,
      loading: null,
      sortOptions: {
        group: 'tree',
        fallbackOnBody: true,
        invertSwap: true,
        animation: 150,
      },
    }
  },
  async fetch() {
    try {
      const params = {parent: 0, limit: 0, sort: 'rank', dir: 'asc'}
      const {data} = await this.$axios.get(this.url, {params})
      this.total = data.total
      this.categories = this.buildTree(data.rows)
    } catch (e) {}
  },
  computed: {
    myValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
    updateKey() {
      return this.url.split('/').join('-')
    },
  },
  mounted() {
    this.$root.$on(`app::${this.updateKey}::update`, this.$fetch)
    new Sortable(this.$refs.roots, {...this.sortOptions, onEnd: this.sortNodes})
  },
  methods: {
    buildTree(dataset) {
      const hashTable = {}
      dataset.forEach((item) => {
        hashTable[item.id] = {...item, children: []}
      })

      const dataTree = []
      dataset.forEach((item) => {
        if (item.parent_id && hashTable[item.parent_id]) {
          hashTable[item.parent_id].children.push(hashTable[item.id])
        } else {
          dataTree.push(hashTable[item.id])
        }
      })

      return dataTree
    },
    createNode() {
      this.record = {
        parent_id: this.myValue,
        alias: '',
        active: true,
        translations: [],
      }
      this.modalVisible = true
    },
    async editNode(node) {
      this.loading = node.id
      try {
        const {data} = await this.$axios.get(this.url + '/' + node.id)
        this.record = data
        this.modalVisible = true
      } catch (e) {
      } finally {
        this.loading = null
      }
    },
    async deleteNode(node) {
      const properties = {
        title: this.$t('components.confirm_delete_title'),
        okVariant: 'danger',
        okTitle: this.$t('components.confirm_yes'),
        cancelTitle: this.$t('components.confirm_no'),
        footerClass: 'justify-content-between',
        hideHeaderClose: false,
        autoFocusButton: 'ok',
        centered: true,
      }
      const res = await this.$bvModal.msgBoxConfirm(this.$t('components.confirm_delete_message'), properties)
      if (res) {
        try {
          await this.$axios.delete(this.url + '/' + node.id)
          if (this.myValue === node.id) {
            this.myValue = 0
          }
          this.$root.$emit(`app::${this.updateKey}::update`)
        } catch (e) {}
      }
    },
    async sortNodes({to, from, item, oldIndex, newIndex}) {
      const id = Number(item.dataset.id)
      const oldParent = Number(from.dataset.id)
      const newParent = Number(to.dataset.id)

      if (oldParent !== newParent || oldIndex !== newIndex) {
        await this.$axios.post(this.url + '/' + id, {parent: newParent, rank: newIndex})
        await this.$fetch()
        this.$root.$emit(`app::admin-products::update`)
      }
      this.$root.$emit(`app::categories-tree::sort`)
    },
  },
}
</script>
