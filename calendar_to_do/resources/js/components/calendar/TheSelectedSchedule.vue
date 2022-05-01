<template>
  <v-menu
    v-model="selectedOpen"
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
        <v-toolbar-title
          v-html="selectedEvent.name"
        />
        <v-spacer />
        <v-btn icon>
          <v-icon @click="editEvent">
            mdi-pencil
          </v-icon>
        </v-btn>
        <v-btn icon>
          <v-icon @click="deleteConfirm()">
            delete
          </v-icon>
        </v-btn>
        <v-btn icon>
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <span
          v-html="dateFormat(selectedEvent.start)"
        />
        〜
        <span v-html="dateFormat(selectedEvent.end)" />
      </v-card-text>
      <v-card-actions>
        <v-btn
          text
          color="secondary"
          @click="selectedOpen = false"
        >
          Cancel
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-menu>
</template>

<script>

export default {
  components: {
    DeleteCalendarDialog,
  },
  props: {
    deleteDialog: {
      type: Boolean,
      required: false,
    },
  },
  data: () => ({

  }),
};
</script>
