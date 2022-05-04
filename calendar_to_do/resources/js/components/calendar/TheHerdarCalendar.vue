<template>
  <v-row align="center">
    <v-col>
      <v-list-item-icon>
        <v-icon>event</v-icon>
        <h3 class="m-0">
          Calendar
        </h3>
      </v-list-item-icon>
    </v-col>
    <v-col class="text-right d-flex">
      <v-select
        :value="calenderType"
        :items="types"
        dense
        outlined
        hide-details
        class="ma-2"
        label="type"
        @change="$emit('changeCalendarType',$event)"
      />
      <v-btn
        icon
        class="ma-2"
        @click="$emit('prevCalendar')"
      >
        <v-icon>mdi-chevron-left</v-icon>
      </v-btn>

      <v-btn
        icon
        class="ma-2"
        @click="$emit('nextCalendar')"
      >
        <v-icon>mdi-chevron-right</v-icon>
      </v-btn>
    </v-col>
  </v-row>
</template>

<script>
export default {
  props: {
    calenderType: {type: String},
  },
  data: () => ({
    types: ['month', 'week', 'day', '4day'],
  }),
};
</script>
