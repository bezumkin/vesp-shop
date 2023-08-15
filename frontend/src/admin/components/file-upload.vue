<template>
  <section>
    <div
      :class="classes"
      :style="styles"
      @drop.prevent="onAddFile"
      @dragenter.prevent="onDragEnter"
      @dragleave.prevent="onDragLeave"
      @dragover.prevent
      @click="onSelect"
    >
      <img v-if="placeholder && myValue !== false" :src="placeholderUrl" alt="" />
      <img v-if="myValue && myValue.file" :src="myValue.file" alt="" />
    </div>
    <slot name="actions" v-bind="{select: onSelect, remove: onRemove, cancel: onCancel, value: myValue, placeholder}">
      <div class="text-center">
        <b-button v-if="myValue && myValue.file" variant="link" class="text-danger" @click="onCancel">
          {{ titleCancel }}
        </b-button>
        <b-button v-else-if="placeholder && myValue !== false" variant="link" class="text-danger" @click="onRemove">
          {{ titleRemove }}
        </b-button>
      </div>
    </slot>
  </section>
</template>

<script>
export default {
  name: 'FileUpload',
  props: {
    value: {
      type: [Object, Boolean],
      default() {
        return {file: null, metadata: null}
      },
    },
    height: {
      type: Number,
      default: 200,
    },
    width: {
      type: Number,
      default: null,
    },
    placeholder: {
      type: Object,
      default() {
        return null
      },
    },
    accept: {
      type: String,
      default: 'image/*',
    },
    wrapperClass: {
      type: [String, Array],
      default: null,
    },
    titleCancel: {
      type: String,
      default() {
        return this.$t('actions.cancel')
      },
    },
    titleRemove: {
      type: String,
      default() {
        return this.$t('actions.delete')
      },
    },
  },
  data() {
    return {
      dragCount: 0,
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
    classes() {
      let cls = ['wrapper']
      if (this.dragCount > 0) {
        cls.push('drag-over')
      }
      if (this.rounded) {
        cls.push('rounded-circle')
      }
      if (this.wrapperClass) {
        cls = Array.isArray(this.wrapperClass) ? [...cls, ...this.wrapperClass] : [...cls, this.wrapperClass]
      }
      return cls
    },
    styles() {
      return {
        height: this.height + 'px',
        width: this.width + 'px',
      }
    },
    placeholderUrl() {
      if (!this.placeholder) {
        return null
      }
      const params = {fit: 'crop'}
      if (this.width) {
        params.w = this.width
      }
      if (this.height) {
        params.h = this.height
      }
      return this.$image(this.placeholder, params)
    },
  },
  methods: {
    onSelect() {
      const input = document.createElement('input')
      input.type = 'file'
      input.multiple = false
      input.accept = this.accept
      input.onchange = (e) => {
        this.onAddFile({dataTransfer: {files: e.target.files}})
      }
      input.click()
    },
    onRemove() {
      this.myValue = false
    },
    onCancel() {
      this.myValue = null
    },
    onAddFile({dataTransfer}) {
      this.dragCount = 0
      const file = Array.from(dataTransfer.files).shift()
      if (this.verifyAccept(file.type)) {
        const reader = new FileReader()
        reader.onload = () => {
          this.$set(this, 'myValue', {
            file: reader.result,
            metadata: {name: file.name, size: file.size, type: file.type},
          })
        }
        reader.readAsDataURL(file)
      }
    },
    onDragEnter() {
      this.dragCount++
    },
    onDragLeave() {
      this.dragCount--
    },
    verifyAccept(type) {
      const allowed = this.accept.split(',').map((i) => i.trim())
      return allowed.includes(type) || allowed.includes(type.split('/')[0] + '/*')
    },
  },
}
</script>

<style scoped lang="scss">
$transition: 0.25s;
.wrapper {
  overflow: hidden;
  position: relative;
  background-color: $light;
  border: 1px solid darken($light, 10%);
  transition: background-color $transition;
  cursor: pointer;
  &:hover {
    background-color: darken($light, 10%);
    border-color: 1px solid $secondary;
  }
  img,
  &:after {
    position: absolute;
    width: 100%;
    height: 100%;
  }
  img {
    object-fit: cover;
    object-position: center;
  }
  &:after {
    content: '';
    background: rgba($success, 0.25);
    opacity: 0;
    transition: opacity $transition;
    pointer-events: none;
  }
  &.drag-over {
    background-color: transparent;
    &:after {
      opacity: 1;
    }
  }
}
</style>
