<template>
  <div>
    <b-row class="mt-3 mb-3">
      <b-col md="6" class="text-center text-md-left">
        <b-button-group>
          <b-button :pressed="!listView" variant="outline-secondary" @click="listView = false">Плитка</b-button>
          <b-button :pressed="listView" variant="outline-secondary" @click="listView = true">Список</b-button>
        </b-button-group>
      </b-col>
      <b-col md="6" class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start">
        <div class="mr-2 ml-md-auto mt-2 mt-md-0">Сортировка:</div>
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

    <list-products ref="x" v-model="page" :limit="limit" :sort="sort" :dir="dir" :list-view="listView" @load="onLoad" />

    <b-pagination v-model="page" :total-rows="total" :per-page="limit" class="mt-5" />
  </div>
</template>

<script>
import ListProducts from '../components/list-products'

export default {
  name: 'IndexPage',
  components: {ListProducts},
  data() {
    return {
      page: 1,
      total: 0,
      sort: 'title',
      dir: 'asc',
      listView: false,
    }
  },
  computed: {
    limit() {
      return this.listView ? 10 : 9
    },
  },
  methods: {
    onLoad({total}) {
      this.total = total
    },
    onSort(key) {
      if (this.sort === key) {
        this.dir = this.dir === 'asc' ? 'desc' : 'asc'
      } else {
        this.sort = key
        this.dir = 'asc'
      }
    },
  },
}
</script>
