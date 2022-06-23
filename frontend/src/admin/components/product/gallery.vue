<template>
  <div>
    <b-button class="col-12 col-md-auto" @click="onFileSelect">
      <fa icon="upload" /> {{ $t('actions.upload') }}
    </b-button>
    <b-overlay :show="loading" opacity="0.5" class="mt-3">
      <div
        :class="{files: true, 'drag-over': dragCount > 0}"
        @drop.prevent="onAddFiles"
        @dragenter.prevent="onDragEnter"
        @dragleave.prevent="onDragLeave"
        @dragover.prevent
      >
        <draggable v-model="files" class="files-container" animation="200" @change="onSort">
          <div v-for="image in files" :key="image.file_id" class="image-wrapper">
            <b-link :href="$image(image.file)" target="_blank">
              <b-img
                :src="$image(image.file, {fit: 'crop', w: thumbWidth, h: thumbHeight})"
                :width="thumbWidth"
                :height="thumbHeight"
                :class="{image: true, disabled: !image.active}"
                alt=""
              />
            </b-link>
            <div class="d-flex justify-content-between mt-1">
              <b-button v-if="image.active" variant="warning" size="sm" @click="onDisable(image)">
                <fa icon="power-off" class="fa-fw" />
              </b-button>
              <b-button v-else variant="success" size="sm" @click="onEnable(image)">
                <fa icon="check" class="fa-fw" />
              </b-button>
              <b-button variant="danger" size="sm" @click="onDelete(image)">
                <fa icon="times" class="fa-fw" />
              </b-button>
            </div>
          </div>
        </draggable>
      </div>
    </b-overlay>
  </div>
</template>

<script>
import draggable from 'vuedraggable'

export default {
  name: 'ProductFiles',
  components: {draggable},
  props: {
    productId: {
      type: [Number, String],
      required: true,
    },
    thumbWidth: {
      type: [Number, String],
      default: 250,
    },
    thumbHeight: {
      type: [Number, String],
      default: 250,
    },
  },
  data() {
    return {
      loading: false,
      files: [],
      dragCount: 0,
    }
  },
  async fetch() {
    this.loading = true
    try {
      const {data} = await this.$axios.get(this.url)
      this.files = data.rows
    } catch (e) {
    } finally {
      this.loading = false
    }
  },
  computed: {
    url() {
      return `admin/product/${this.productId}/files`
    },
  },
  methods: {
    emitUpdate() {
      this.$root.$emit('app::admin-products::update')
    },
    onFileSelect() {
      const el = document.createElement('input')
      el.type = 'file'
      el.multiple = true
      el.accept = 'image/*'
      el.click()
      el.addEventListener('change', (e) => {
        this.onAddFiles({dataTransfer: e.target})
      })
    },
    onDragEnter() {
      this.dragCount++
    },
    onDragLeave() {
      this.dragCount--
    },
    async onAddFiles({dataTransfer}) {
      this.dragCount = 0
      const files = Array.from(dataTransfer.files)
      if (!files.length) {
        return
      }

      const asyncLoad = (file) => {
        const reader = new FileReader()
        return new Promise((resolve) => {
          reader.onload = async ({target}) => {
            const {data} = await this.$axios.put(this.url, {
              file: target.result,
              metadata: {name: file.name, size: file.size, type: file.type},
            })
            resolve(data)
          }
          reader.readAsDataURL(file)
        })
      }

      this.loading = true
      try {
        for (const file of files) {
          if (file.type.includes('image/')) {
            const res = await asyncLoad(file)
            this.files.push(res)
          }
        }
        this.emitUpdate()
      } catch (e) {
      } finally {
        this.loading = false
      }
    },
    async onDisable(image) {
      image.active = false
      await this.$axios.patch(this.url + '/' + image.file_id, {active: false})
      this.emitUpdate()
    },
    async onEnable(image) {
      image.active = true
      await this.$axios.patch(this.url + '/' + image.file_id, {active: true})
      this.emitUpdate()
    },
    async onDelete(image) {
      this.loading = true
      await this.$axios.delete(this.url + '/' + image.file_id)
      this.files = this.files.filter((i) => i.file_id !== image.file_id)
      this.loading = false
      this.emitUpdate()
    },
    async onSort() {
      const files = {}
      this.files.forEach((i, idx) => {
        files[i.file_id] = idx
      })
      await this.$axios.post(this.url, {files})
      this.emitUpdate()
    },
  },
}
</script>

<style scoped lang="scss">
.files {
  border: 1px solid $light;
  border-radius: $border-radius;
  transition: background-color 0.25s, border-color 0.25s;
  &-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    min-height: 200px;
    padding-bottom: 1rem;
  }
  &.drag-over {
    border-color: $success;
    background-color: rgba($success, 0.25);
  }
  .image-wrapper {
    margin: 1rem 0 0 1rem;
    .image {
      border-radius: $border-radius;
      cursor: pointer;
      transition: opacity 0.25s;
      &.disabled {
        opacity: 0.5;
        filter: grayscale(1);
      }
    }
  }
}
</style>
