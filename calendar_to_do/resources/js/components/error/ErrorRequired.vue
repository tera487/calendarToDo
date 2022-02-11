<template>
  <div class="invalid-feedback" v-if="validation">{{attribute}}は入力が必須です</div>
</template>

<script>
export default {
  name: "errorRequired",
  props: {
    attribute: { type: String, required: true },
    validation: { type: Boolean, required: false },
  },
};
</script>
