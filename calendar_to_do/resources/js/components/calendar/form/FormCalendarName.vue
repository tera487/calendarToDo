<template>
  <v-text-field
    :value="scheduleName"
    label="タイトル"
    @change="$emit('optional-name',$event)"
  />
  <!-- <v-text-field
    :value="scheduleName"
    label="タイトル"
    :error-messages="scheduleNameErrors"
    @input="$v.eventName.$touch()"
    @blur="$v.eventName.$touch()"
  /> -->
</template>

<script>
// import {required} from 'vuelidate/lib/validators';
export default {
  // validations: {
  //   eventName: {required},
  // },
  props: {
    scheduleName: {type: String},
  },
  // computed: {
  //   scheduleNameErrors() {
  //     const errors = [];
  //     if (!this.$v.eventName.$dirty) return errors;
  //     !this.$v.eventName.required && errors.push('タイトルは必須です。');
  //     return errors;
  //   },
  // },
};
</script>
