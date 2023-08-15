<template>
  <div>
    <b-row>
      <b-col>
        <b-form-group :label="$t('models.user.username')">
          <b-form-input v-model.trim="record.username" required autofocus />
        </b-form-group>
        <b-form-group :label="$t('models.user.password')">
          <b-input-group>
            <b-form-input v-model.trim="record.password" :required="!record.id" />
            <template #append>
              <b-button @click="generatePassword">
                <fa icon="redo" />
              </b-button>
            </template>
          </b-input-group>
        </b-form-group>
      </b-col>
      <b-col md="5" class="d-flex justify-content-md-end">
        <b-form-group>
          <file-upload
            v-model="record.new_file"
            :placeholder="record.file"
            :height="150"
            :width="150"
            wrapper-class="rounded-circle"
          />
        </b-form-group>
      </b-col>
    </b-row>
    <b-form-group :label="$t('models.user.fullname')">
      <b-form-input v-model.trim="record.fullname" required />
    </b-form-group>

    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.user.email')">
          <b-form-input v-model.trim="record.email" type="email" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.user.phone')">
          <b-form-input v-model.trim="record.phone" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group :label="$t('models.user.role')">
      <vesp-input-combo-box v-model="record.role_id" url="admin/user-roles" required />
    </b-form-group>

    <b-form-group :label="$t('models.user.gender')">
      <b-form-select v-model="record.gender" :options="genderOptions" />
    </b-form-group>

    <b-form-group :label="$t('models.user.company')">
      <b-form-input v-model.trim="record.company" />
    </b-form-group>

    <b-row>
      <b-col md="8">
        <b-form-group :label="$t('models.user.address')">
          <b-form-input v-model.trim="record.address" />
        </b-form-group>
      </b-col>
      <b-col md="4">
        <b-form-group :label="$t('models.user.zip')">
          <b-form-input v-model.trim="record.zip" />
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col md="6">
        <b-form-group :label="$t('models.user.country')">
          <b-form-input v-model.trim="record.country" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group :label="$t('models.user.city')">
          <b-form-input v-model.trim="record.city" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group :label="$t('models.user.comment')">
      <b-form-textarea v-model.trim="record.comment" rows="3" />
    </b-form-group>

    <b-row>
      <b-col md="6">
        <b-form-group>
          <b-form-checkbox v-model.trim="record.active">
            {{ $t('models.user.active') }}
          </b-form-checkbox>
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group>
          <b-form-checkbox v-model.trim="record.blocked">
            {{ $t('models.user.blocked') }}
          </b-form-checkbox>
        </b-form-group>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import FileUpload from '@/components/file-upload.vue'

export default {
  name: 'FormUser',
  components: {FileUpload},
  props: {
    value: {
      type: Object,
      required: true,
    },
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
    genderOptions() {
      return [
        {value: null, text: this.$t('models.user.gender_select')},
        {value: 1, text: this.$t('models.user.gender_1')},
        {value: 2, text: this.$t('models.user.gender_2')},
      ]
    },
  },
  mounted() {
    if (!this.record.id) {
      this.generatePassword()
    }
  },
  methods: {
    generatePassword(e, length = 12) {
      const charset = 'abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
      let password = ''
      for (let i = 0, n = charset.length; i < length; ++i) {
        password += charset.charAt(Math.floor(Math.random() * n))
      }
      this.record.password = password
    },
  },
}
</script>
