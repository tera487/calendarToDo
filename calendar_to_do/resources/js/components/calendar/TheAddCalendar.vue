<template>
  <div class="text-center">
    <v-dialog
      :value="isDialog"
      width="500"
      @click:outside="$emit('resetCreateEvent')"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          スケージュール
        </v-card-title>

        <v-card-text>
          <v-container>
            <form-calendar-name
              :schedule-name="createEvent.name"
              @optional-name="$emit('optional-name',$event)"
            />
            <v-row>
              <form-calendar-date
                :selected-date="createEvent.start"
                @optional-date="$emit('optional-date-start',$event)"
              />
              <form-calendar-date
                :selected-date="createEvent.end"
                @optional-date="$emit('optional-date-end',$event)"
              />

              <v-textarea
                :value="createEvent.description"
                name="input-7-1"
                label="説明"
                rows="3"
                @input="$emit('changeDescription',$event)"
              />
            </v-row>
          </v-container>
        </v-card-text>

        <v-divider />

        <v-card-actions>
          <v-spacer />
          <v-btn
            color="primary"
            text
            @click="$emit('validationEventform')"
          >
            保存
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import FormCalendarDate from './form/FormCalendarDate.vue';
import FormCalendarName from './form/FormCalendarName.vue';

export default {
  components: {
    FormCalendarDate,
    FormCalendarName,
  },
  props: {
    isDialog: {
      type: Boolean,
      required: true,
    },
    createEvent: {
    },
  },
};
</script>
