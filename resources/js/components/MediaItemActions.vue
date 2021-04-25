<template>
  <div class="my-2 px-2 flex">
    <button v-if="canEdit" type="button" class="flex text-70 hover:text-primary focus:outline-none ml-2">
      <icon type="edit" view-box="0 0 20 20" width="14" height="14" />
    </button>

  </div>
</template>

<script>

export default {
  props: {
    media: {
      type: Object,
      required: true,
    },
  },

  computed: {
    readonly() {
    },
    canEdit() {
      return true; // this.media.authorizedToUpdate && !this.readonly
    },
  },
}
</script>
