<template>
  <div :class="{nodes: true, expanded}" @dragstart="onDragStart" @dragend="onDragEnd" @dragenter="onDragEnter">
    <div :class="nodeClass">
      <b-button v-if="node.children.length" variant="link" class="toggle" @click="toggleNode">
        <transition name="fade" mode="out-in">
          <fa v-if="expanded" key="minus" icon="minus" class="fa-fw" />
          <fa v-else key="plus" icon="plus" class="fa-fw" />
        </transition>
      </b-button>
      <div class="title" @click="onSelect">{{ $translate(node.translations) }}</div>
      <div class="actions">
        <slot name="actions" v-bind="{node, expanded}" />
      </div>
    </div>

    <b-collapse v-model="expanded" class="children">
      <div ref="children" class="children-nodes" :data-id="node.id">
        <categories-node
          v-for="child in node.children"
          :key="child.id"
          v-model="myValue"
          :node="child"
          :data-id="child.id"
          :sort-options="sortOptions"
          :sort-function="sortFunction"
        >
          <template v-for="slotName in Object.keys($scopedSlots)" #[slotName]="slotProps">
            <slot :name="slotName" v-bind="slotProps" />
          </template>
        </categories-node>
      </div>
    </b-collapse>
  </div>
</template>

<script>
import {Sortable} from 'sortablejs'
export default {
  name: 'CategoriesNode',
  props: {
    value: {
      type: Number,
      default: 0,
    },
    node: {
      type: Object,
      required: true,
    },
    sortOptions: {
      type: Object,
      default() {
        return {}
      },
    },
    sortFunction: {
      type: Function,
      default() {},
    },
  },
  data() {
    return {
      dragging: false,
      expanded: this.node.id === this.value && this.node.children.length > 0,
    }
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
    nodeClass() {
      const classes = ['node']
      if (this.node.id === this.value) {
        classes.push('active')
      }
      if (!this.node.products_count) {
        classes.push('empty')
      }
      return classes
    },
  },
  mounted() {
    new Sortable(this.$refs.children, {...this.sortOptions, onEnd: this.sortFunction})
    this.$root.$on(`app::categories-tree::sort`, () => {
      this.dragging = false
      if (this.expanded && !this.node.children.length) {
        this.expanded = false
      }
    })
  },
  methods: {
    toggleNode() {
      this.expanded = !this.expanded
    },
    onSelect() {
      if (this.myValue !== this.node.id) {
        this.myValue = this.node.id
        if (this.node.children.length) {
          this.expanded = true
        }
      } else {
        this.myValue = 0
        this.expanded = false
      }
    },
    onDragStart() {
      this.dragging = true
    },
    onDragEnd() {
      this.dragging = false
    },
    onDragEnter() {
      if (!this.dragging) {
        this.expanded = true
      }
    },
  },
}
</script>
