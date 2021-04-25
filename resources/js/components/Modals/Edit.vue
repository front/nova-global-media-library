<template>
  <modal class="select-text" @modal-close="handleClose">
    <card class="w-action-fields overflow-hidden">
      <h4 class="text-90 font-normal text-2xl flex-no-shrink px-8 pt-6">
        {{ __('Edit Media') }}
      </h4>

      <form v-if="fields" autocomplete="off" @submit.prevent="handleSubmit">
        <validation-errors :errors="errors" />

        <div v-for="field in fields" :key="field.attribute" class="action">
          <component
              :is="'form-' + field.component"
              :errors="errors"
              :resource-id="resourceId"
              :resource-name="resourceName"
              :field="field"
              :show-help-text="field.helpText != null"
          />
        </div>

        <div class="bg-30 flex px-8 py-4">
          <button type="button" class="btn text-80 font-normal h-9 px-3 ml-auto mr-3 btn-link" @click="handleClose">
            {{ __('Cancel') }}
          </button>

          <progress-button type="submit" :disabled="updating" :processing="updating">
            {{ __('Update Media') }}
          </progress-button>
        </div>
      </form>
    </card>
  </modal>
</template>

<script>
// import debounce from './../../debounce';
import { InteractsWithResourceInformation } from 'laravel-nova'
import { Errors } from 'laravel-nova'

export default {
  mixins: [InteractsWithResourceInformation],
  props: {
    isModalOpen: {
      type: Boolean,
      default: false,
      required: true,
    },
    file: {},
    fields: {
      type: Object,
      required: true,
    },

    resourceName: {
      type: String,
      required: true,
    },
    resourceId: {
      type: [Number, String],
      required: true,
    },
    errors: {
      type: Object,
      required: true,
    },
    updating: {
      type: Boolean,
      default: false,
      required: true,
    },
  },

  methods: {

    openModal() {
      this.$emit('update:isModalOpen', true);
    },

    withUpdating(promise) {
      this.updating = true

      return promise.finally(() => this.updating = false)
    },

    async handleSubmit() {
      const formData = new FormData()

      this.fields.forEach(field => field.fill(formData))

      formData.append('_method', 'PUT')

      // for (let key in this.__requestParams) {
      //   formData.append(key, this.__requestParams[key])
      // }

      try {
        await this.withUpdating(
            Nova
                .request()
                .post('/nova-api/media/' + this.resourceId, formData),
        )

        Nova.success(Nova.app.__('Media was updated!'))

        this.$emit('refresh')
      } catch (error) {
        if (!error.response) {
          throw error
        } else if (error.response.status === 422) {
          this.errors = new Errors(error.response.data.errors)
        }

        Nova.error(Nova.app.__('There was a problem submitting the form.'))
      }
    },

    handleClose() {
      this.$emit('update:isModalOpen', false);
      this.$emit('close');
    },

    closeModal() {
      this.$emit('update:isModalOpen', false);
      this.listenUploadArea = false;
      this.$emit('close');
    },

    closeModalAndSave() {
      this.$emit('update:isModalOpen', false);
      this.$toasted.show('Update successful', { type: 'success' });
    },
  },
};
</script>
