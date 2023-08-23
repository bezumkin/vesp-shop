<template>
  <vesp-modal v-model="record" :url="url" :title="$t('models.product.title_one')" :hide-footer="tab > 0" size="lg">
    <template #default="{loading, submit}">
      <b-tabs v-model="tab" content-class="pt-3">
        <b-tab :title="$t('models.product.tab_main')">
          <b-overlay :opacity="0.5" :show="loading">
            <b-form ref="form" @submit.prevent="submit">
              <form-product v-model="record" />
              <input type="submit" class="d-none" />
            </b-form>
          </b-overlay>
        </b-tab>
        <template v-if="record.id">
          <b-tab :title="$t('models.product.tab_files')" lazy>
            <product-files :product-id="record.id" />
          </b-tab>
          <b-tab :title="$t('models.product.tab_categories')" lazy>
            <product-categories :product-id="record.id" />
          </b-tab>
          <b-tab :title="$t('models.product.tab_links')" lazy>
            <product-links :product-id="record.id" />
          </b-tab>
        </template>
      </b-tabs>
    </template>
  </vesp-modal>
</template>

<script>
import {url} from '../products'
import FormProduct from '@/components/forms/product'
import ProductFiles from '@/components/product/gallery.vue'
import ProductCategories from '@/components/product/categories.vue'
import ProductLinks from '@/components/product/links.vue'

export {url}
export default {
  name: 'ProductCreatePage',
  components: {ProductLinks, ProductCategories, ProductFiles, FormProduct},
  data() {
    return {
      url,
      tab: 0,
      record: {
        title: '',
        description: '',
        alias: '',
        article: '',
        price: null,
        category_id: null,
        active: true,
        translations: [],
      },
    }
  },
}
</script>
