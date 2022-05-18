<template>
  <v-col>
    <v-row>
      <v-col>
        <v-menu
          v-model="isOpenDatePicker"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="auto"
        >
          <template
            #activator="{on,attrs,}"
          >
            <v-text-field
              v-model="
                scheduleDate
              "
              label="タイムゾーン"
              readonly
              v-bind="attrs"
              v-on="on"
            />
          </template>
          <v-date-picker
            v-model="scheduleDate"
            locale="ja-jp"
            @change="$emit('optional-date',$event)"
            @input="isOpenDatePicker = false"
          />
        </v-menu>
      </v-col>
    </v-row>
  </v-col>
</template>

<script>
export default {
  props: {
    selectedDate: {},
  },
  data() {
    return {
      isOpenDatePicker: false,
      scheduleDate: null,
    };
  },
  watch: {
    'selectedDate': {
      handler() {
        if (typeof(this.selectedDate) == 'string') {
          const timezoneStart = this.selectedDate.split(' ');
          this.scheduleDate = timezoneStart[0];
        }
      },
      immediate: true,
    },
  },
};
</script>
