<template>
  <v-col>
    <v-row>
      <v-col>
        <v-menu
          v-model="isOpenHourPicker"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="auto"
        >
          <template
            #activator="{
              on,
              attrs,
            }"
          >
            <v-text-field
              v-model="
                scheduleHour
              "
              label="タイムゾーン"
              readonly
              v-bind="attrs"
              v-on="on"
            />
          </template>
          <v-date-picker
            v-model="scheduleHour"
            locale="ja-jp"
            @change="
              integrationSchedule()
            "
            @input="
              isOpenHourPicker = false
            "
          />
        </v-menu>
      </v-col>
      <v-col>
        <v-menu
          ref="isOpenMinutePicker"
          v-model="isOpenMinutePicker"
          :close-on-content-click="false"
          :nudge-right="40"
          :return-value.sync="
            scheduleMinute
          "
          transition="scale-transition"
          offset-y
          max-width="290px"
          min-width="290px"
        >
          <template
            #activator="{
              on,
              attrs,
            }"
          >
            <v-text-field
              v-model="
                scheduleMinute
              "
              readonly
              v-bind="attrs"
              v-on="on"
            />
          </template>
          <v-time-picker
            v-if="isOpenMinutePicker"
            v-model="
              scheduleMinute
            "
            full-width
            @change="
              integrationSchedule()
            "
            @click:minute="
              $refs.isOpenMinutePicker.save(
                scheduleMinute
              )
            "
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
      isOpenMinutePicker: false,
      isOpenHourPicker: false,
      scheduleHour: null,
      scheduleMinute: null,
    };
  },
  watch: {
    'selectedDate': {
      handler() {
        if (typeof(this.selectedDate) == 'string') {
          const timezoneStart = this.selectedDate.split(' ');
          this.scheduleHour = timezoneStart[0];
          this.scheduleMinute = timezoneStart[1];
        }
      },
      immediate: true,
    },
  },
  methods: {
    integrationSchedule() {
      const date = `${this.scheduleHour} ${this.scheduleMinute}`;
      this.$emit('optional-date', date );
    },
  },
};
</script>
