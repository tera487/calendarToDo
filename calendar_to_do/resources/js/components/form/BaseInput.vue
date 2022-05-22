<template>
  <input
    :type="type"
    :name="name"
    :value="value"
    :placeholder="placeholder"
    @input="updateValue"
    :class="{'is-invalid': validation}" 
  />
</template>

<script>
export default {
  name: "baseInput",
  props: {
    value: { type: String, required: false },
    type: { type: String, required: true },
    name: { type: String, required: true },
    placeholder: { type: String, required: false },
    validation: { type: Boolean, required: false , default:false},
  },
  methods: {
    updateValue: function(e) {
      this.$emit("input", e.target.value);
    }
  }
};
</script>