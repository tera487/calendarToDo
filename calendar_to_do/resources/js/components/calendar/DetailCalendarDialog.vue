<template>
  <v-menu
    :value="isSelectedOpen"
    :close-on-content-click="false"
    :activator="selectedElement"
    offset-x
  >
    <v-card
      color="grey lighten-4"
      min-width="350px"
      flat
    >
      <v-toolbar
        :color="selectedEvent.color"
        dark
      >
        <v-toolbar-title>
          {{ selectedEvent.name }}
        </v-toolbar-title>
        <v-spacer />
        <v-btn icon>
          <v-icon @click="$emit('editEvent')">
            mdi-pencil
          </v-icon>
        </v-btn>
        <v-btn icon>
          <v-icon @click="$emit('openDeleteDialog')">
            delete
          </v-icon>
        </v-btn>
        <v-btn icon>
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <v-row>
          <v-col cols="12">
            <v-icon class="mr-2">
              schedule
            </v-icon>
            <span>
              {{ dateFormat(selectedEvent.start) }}
            </span>
            〜
            <span>
              {{ dateFormat(selectedEvent.end) }}
            </span>
          </v-col>
          <v-col cols="12">
            <v-icon class="mr-2">
              description
            </v-icon>
            <span>{{ selectedEvent.description }}</span>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions>
        <v-btn
          text
          color="secondary"
          @click="$emit('closeDetailDialog')"
        >
          Cancel
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-menu>
</template>
<script>
export default {
  props: {
    selectedEvent: {type: Object, default: null},
    isSelectedOpen: {type: Boolean, default: false},
    selectedElement: {},
  },
  methods: {
    dateFormat(date) {
      const formatedDate = new Date(date);
      return `${formatedDate.getFullYear()}-${(
        formatedDate.getMonth() + 1
      )
          .toString()
          .padStart(2, '0')}-${formatedDate
          .getDate()
          .toString()
          .padStart(2, '0')} ${formatedDate
          .getHours()
          .toString()
          .padStart(2, '0')}:${formatedDate
          .getMinutes()
          .toString()
          .padStart(2, '0')}`;
    },
  },
};
</script>
