<template>
  <b-row class="mt-3 mb-3">
    <b-col md="4" class="text-center text-md-left mb-2 mb-md-0">
      <b-button-group>
        <b-button :pressed="!listView" variant="outline-secondary" @click="listView = false">Плитка</b-button>
        <b-button :pressed="listView" variant="outline-secondary" @click="listView = true">Список</b-button>
      </b-button-group>
    </b-col>
    <b-col md="8" class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start">
      <div class="mr-2 ml-md-auto">Сортировка:</div>
      <b-button-group>
        <b-button :pressed="sort === 'title'" variant="outline-secondary" @click="onSort('title')">
          По названию
          <template v-if="sort === 'title' && dir === 'desc'">&uarr;</template>
          <template v-else>&darr;</template>
        </b-button>
        <b-button :pressed="sort === 'price'" variant="outline-secondary" @click="onSort('price')">
          По цене
          <template v-if="sort === 'price' && dir === 'desc'">&uarr;</template>
          <template v-else>&darr;</template>
        </b-button>
      </b-button-group>
    </b-col>
  </b-row>
</template>

<script>
export default {
  name: 'ListProductsActions',
  props: {
    value: {
      type: Boolean,
      required: true,
    },
    sort: {
      type: String,
      required: true,
    },
    dir: {
      type: String,
      required: true,
    },
  },
  computed: {
    listView: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
  methods: {
    onSort(key) {
      if (this.sort === key) {
        this.$emit('update:dir', this.dir === 'asc' ? 'desc' : 'asc')
      } else {
        this.$emit('update:sort', key)
        this.$emit('update:dir', 'asc')
      }
    },
  },
}
</script>
