<template>
  <div>
    <lang-tabs v-model="lang" />

    <hr />

    <b-row class="mt-4">
      <b-col md="7">
        <b-form-group v-lang-icon="lang" :label="$t('models.category.title')">
          <b-form-input
            v-for="(translation, idx) in record.translations"
            v-show="translation.lang === lang"
            :key="idx"
            v-model.trim="translation.title"
            autofocus
            required
          />
        </b-form-group>
        <b-form-group :label="$t('models.category.alias')">
          <input-alias
            v-model.trim="record.alias"
            :watch="record.translations.length > 0 ? record.translations[0].title : null"
            required
          />
        </b-form-group>
      </b-col>
      <b-col md="5" class="d-flex justify-content-md-end">
        <file-upload
          v-model="record.new_file"
          :placeholder="record.file"
          :height="150"
          :width="150"
          wrapper-class="rounded"
        />
      </b-col>
    </b-row>

    <b-form-group :label="$t('models.category.parent')">
      <vesp-input-combo-box
        v-model="record.parent_id"
        url="admin/categories"
        sort="rank"
        :format-value="(item) => $translate(item.translations)"
        :filter-props="{exclude: record.id}"
      >
        <template #default="{item}">
          <div :class="{'text-muted': !item.active}">
            {{ $translate(item.translations) }}
            <div class="small text-muted">{{ item.uri }}</div>
          </div>
        </template>
      </vesp-input-combo-box>
    </b-form-group>

    <b-form-group v-lang-icon="lang" :label="$t('models.category.description')">
      <b-form-textarea
        v-for="(translation, idx) in record.translations"
        v-show="translation.lang === lang"
        :key="idx"
        v-model.trim="translation.description"
        rows="5"
      />
    </b-form-group>

    <b-form-group>
      <b-form-checkbox v-model.trim="record.active">
        {{ $t('models.category.active') }}
      </b-form-checkbox>
    </b-form-group>
  </div>
</template>

<script>
import InputAlias from '@/components/inputs/alias.vue'
import FileUpload from '@/components/file-upload.vue'
import Translations from '@/mixins/translations.js'
import LangTabs from '@/components/lang-tabs.vue'

export default {
  name: 'FormCategory',
  components: {LangTabs, FileUpload, InputAlias},
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
      translationFields: {title: '', description: ''},
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
