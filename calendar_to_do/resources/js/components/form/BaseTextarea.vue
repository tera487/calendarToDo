<template>
  <textarea
    :name="name"
    :value="value"
    :placeholder="placeholder"
    :rows="rows"
    :cols="cols"
    @input="updateValue"
    :class="{'is-invalid': validation}" 
  ></textarea>
</template>

<script>
export default {
  name: "BaseTextarea",
  props: {
    value: { type: String, required: false },
    name: { type: String, required: true },
    placeholder: { type: String, required: false },
    rows: { type: Number, required: false },
    cols: { type: Number, required: false },
    validation: { type: Boolean, required: false , default:false},
  },
  methods: {
    updateValue: function(e) {
      this.$emit("input", e.target.value);
    }
  }
};
</script>