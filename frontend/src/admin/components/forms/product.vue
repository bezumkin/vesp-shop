<template>
  <div>
    <lang-tabs v-model="lang" />

    <hr />

    <b-row class="mt-4">
      <b-col md="7">
        <b-form-group v-lang-icon="lang" :label="$t('models.product.title')">
          <b-form-input
            v-for="(translation, idx) in record.translations"
            v-show="translation.lang === lang"
            :key="idx"
            v-model.trim="translation.title"
            autofocus
            required
          />
        </b-form-group>
      </b-col>
      <b-col md="5">
        <b-form-group :label="$t('models.product.alias')">
          <input-alias
            v-model.trim="record.alias"
            :watch="record.translations.length > 0 ? record.translations[0].title : null"
            required
          />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group v-lang-icon="lang" :label="$t('models.product.subtitle')">
      <b-form-input
        v-for="(translation, idx) in record.translations"
        v-show="translation.lang === lang"
        :key="idx"
        v-model.trim="translation.subtitle"
      />
    </b-form-group>

    <b-form-group :label="$t('models.product.category')">
      <input-category v-model="record.category_id" required />
    </b-form-group>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.product.price')">
          <b-form-input v-model="record.price" type="number" min="0" step="0.01" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.product.old_price')">
          <b-form-input v-model="record.old_price" type="number" min="0" step="0.01" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.product.article')">
          <b-form-input v-model.trim="record.article" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.product.weight')">
          <b-form-input v-model="record.old_price" type="number" step="0.01" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group v-lang-icon="lang" :label="$t('models.product.description')">
      <b-form-textarea
        v-for="(translation, idx) in record.translations"
        v-show="translation.lang === lang"
        :key="idx"
        v-model.trim="translation.description"
        rows="3"
      />
    </b-form-group>

    <b-form-group v-lang-icon="lang" :label="$t('models.product.content')">
      <b-form-textarea
        v-for="(translation, idx) in record.translations"
        v-show="translation.lang === lang"
        :key="idx"
        v-model.trim="translation.content"
        rows="5"
      />
    </b-form-group>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.product.made_in')">
          <b-form-input v-model.trim="record.made_in" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.product.flags')">
          <b-row>
            <b-col>
              <b-form-checkbox v-model.trim="record.new">
                {{ $t('models.product.flag_new') }}
              </b-form-checkbox>
            </b-col>
            <b-col>
              <b-form-checkbox v-model.trim="record.popular">
                {{ $t('models.product.flag_popular') }}
              </b-form-checkbox>
            </b-col>
            <b-col>
              <b-form-checkbox v-model.trim="record.favorite">
                {{ $t('models.product.flag_favorite') }}
              </b-form-checkbox>
            </b-col>
          </b-row>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.product.colors')">
          <b-form-tags v-model="record.colors" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.product.variants')">
          <b-form-tags v-model="record.variants" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group>
      <b-form-checkbox v-model.trim="record.active">
        {{ $t('models.product.active') }}
      </b-form-checkbox>
    </b-form-group>
  </div>
</template>

<script>
import InputAlias from '@/components/inputs/alias.vue'
import Translations from '@/mixins/translations.js'
import LangTabs from '@/components/lang-tabs.vue'
import InputCategory from '@/components/inputs/category.vue'

export default {
  name: 'FormProduct',
  components: {InputCategory, LangTabs, InputAlias},
  mixins: [Translations],
  props: {
    value: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      lang: this.$i18n.locale || 'ru',
      translationFields: {title: '', subtitle: '', description: '', content: ''},
    }
  },
  computed: {
    record: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
}
</script>
