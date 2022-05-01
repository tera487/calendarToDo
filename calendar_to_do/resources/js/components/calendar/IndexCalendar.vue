<template>
  <v-app>
    <div>
      <the-herdar-calendar
        :calender-type="calendar_json.type"
        @changeCalendarType="calendar_json.type= $event"
        @prevCalendar="$refs.calendar.prev();"
        @nextCalendar="$refs.calendar.next()"
      />

      <v-sheet height="600">
        <v-calendar
          ref="calendar"
          v-model="value"
          :weekdays="calendar_json.weekday"
          :type="calendar_json.type"
          :events="events"
          :event-overlap-mode="calendar_json.mode"
          :event-overlap-threshold="30"
          :event-color="getEventColor"
          locale="ja-jp"
          :event-ripple="false"
          @click:date="viewDay"
          @mousedown:event="startDrag"
          @mousedown:time="startTime"
          @mousemove:time="mouseMove"
          @mouseup:time="endDrag"
          @mouseleave.native="cancelDrag"
          @click:event="showEvent"
        />
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

        <!-- スケジュール追加・編集のダイアログ -->
        <div class="text-center">
          <v-dialog
            v-model="dialog"
            width="500"
            @click:outside="resetCreateEvent"
          >
            <v-card>
              <v-card-title class="text-h5 grey lighten-2">
                スケージュール
              </v-card-title>

              <v-card-text>
                <v-container>
                  <form-calendar-name
                    :schedule-name="createEvent.name"
                    @optional-name="createEvent.name = $event"
                  />
                  <v-row>
                    <form-calendar-date
                      :selected-date="createEvent.start"
                      @optional-date="createEvent.start = $event"
                    />
                    <form-calendar-date
                      :selected-date="createEvent.end"
                      @optional-date="createEvent.end = $event"
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
                  @click="validationEventform"
                >
                  保存
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>

        <!-- 削除確認ダイアログ-->
        <delete-calendar-dialog
          v-if="deleteDialog"
          :selected-event-id="selectedEvent ? selectedEvent.id : ''"
          :selected-event-name="
            selectedEvent ? selectedEvent.name : ''
          "
          @colseDialog="colseDeleteDialog"
        />
      </v-sheet>
    </div>
  </v-app>
</template>

<script>
import DeleteCalendarDialog from './DeleteCalendarDialog.vue';
import TheHerdarCalendar from './TheHerdarCalendar.vue';
import FormCalendarDate from './form/FormCalendarDate.vue';
import FormCalendarName from './form/FormCalendarName.vue';

export default {
  components: {
    TheHerdarCalendar,
    DeleteCalendarDialog,
    FormCalendarDate,
    FormCalendarName,
  },
  data: () => ({
    calendar_json: {
      type: 'week',
      mode: 'column',
      weekday: [0, 1, 2, 3, 4, 5, 6],
    },
    value: '',
    events: [],
    colors: [
      'blue',
      'indigo',
      'deep-purple',
      'cyan',
      'green',
      'orange',
      'grey darken-1',
    ],
    names: [
      'Meeting',
      'Holiday',
      'PTO',
      'Travel',
      'Event',
      'Birthday',
      'Conference',
      'Party',
    ],

    dragEvent: null,
    dragStart: null,
    createEvent: {
      name: null,
      start: null,
      end: null,
    },

    createStart: null,
    extendOriginal: null,

    numberId: 0,
    dialog: false,

    selectedEvent: {},
    selectedElement: null,
    selectedOpen: false,

    deleteDialog: false,
  }),
  mounted() {
    axios
        .get(`/api/generalSetting/${this.$store.state.auth.user.id}`)
        .then((response) => {
          this.calendar_json = response.data.calendar_json;
        });

    axios.get('/api/calendar').then((response) => {
      for (let i = 0; i < response.data.length; i++) {
        response.data[i].start = new Date(response.data[i].start);
        response.data[i].end = new Date(response.data[i].end);
        response.data[i].timed = true;
        response.data[i].color = this.rndElement(this.colors);
        response.data[i].id = response.data[i].id;
      }
      this.events = response.data;
    });
  },
  methods: {
    colseDeleteDialog(id) {
      if (id !== undefined) {
        this.events = this.events.filter((v) => v.id !== id);
        this.selectedOpen = false;
      }
      this.deleteDialog = false;
    },
    deleteConfirm() {
      this.deleteDialog = true;
    },
    showEvent({nativeEvent, event}) {
      const open = () => {
        this.selectedEvent = event;
        this.selectedElement = nativeEvent.target;
        requestAnimationFrame(() =>
          requestAnimationFrame(() => (this.selectedOpen = true)),
        );
      };

      if (this.selectedOpen) {
        this.selectedOpen = false;
        requestAnimationFrame(() =>
          requestAnimationFrame(() => open()),
        );
      } else {
        open();
      }

      nativeEvent.stopPropagation();
    },
    // 日付をclickした際にその日付に遷移
    viewDay({date}) {
      this.focus = date;
      this.calendar_json.type = 'day';
    },

    startDrag({event, timed}) {
      if (event && timed) {
        this.dragEvent = event;
        this.dragTime = null;
        this.extendOriginal = null;
      }
    },
    startTime(tms) {
      const mouse = this.toTime(tms);

      if (this.dragEvent && this.dragTime === null) {
        const start = this.dragEvent.start;

        this.dragTime = mouse - start;
      } else {
        this.createStart = this.roundTime(mouse);
        this.createEvent = {
          name: `Event #${this.events.length}`,
          color: this.rndElement(this.colors),
          start: this.createStart,
          end: this.createStart,
          timed: true,
          id: 0,
        };

        this.events.push(this.createEvent);
      }
    },
    extendBottom(event) {
      this.createEvent = event;
      this.createStart = event.start;
      this.extendOriginal = event.end;
    },

    // マウスポインタが要素の上を移動したとき
    mouseMove(tms) {
      const mouse = this.toTime(tms);

      if (this.dragEvent && this.dragTime !== null) {
        const start = this.dragEvent.start;
        const end = this.dragEvent.end;
        const duration = end - start;
        const newStartTime = mouse - this.dragTime;
        const newStart = this.roundTime(newStartTime);
        const newEnd = newStart + duration;

        this.dragEvent.start = newStart;
        this.dragEvent.end = newEnd;
      } else if (this.createEvent && this.createStart !== null) {
        const mouseRounded = this.roundTime(mouse, false);
        const min = Math.min(mouseRounded, this.createStart);
        const max = Math.max(mouseRounded, this.createStart);

        this.createEvent.start = min;
        this.createEvent.end = max;
      }
    },

    editEvent() {
      this.dialog = true;
      this.createEvent = this.selectedEvent;
      this.createEvent.start = this.dateFormat(this.selectedEvent.start);
      this.createEvent.end = this.dateFormat(this.selectedEvent.end);
    },
    validationEventform() {
      this.dialog = false;
      if (this.createEvent.id !== this.selectedEvent.id) {
        axios
            .post('/api/calendar', this.createEvent)
            .then((res) => {
              this.createEvent.start = new Date(
                  this.createEvent.start,
              );
              this.createEvent.end = new Date(
                  this.createEvent.end,
              );
              this.createEvent.id = res.data;

              this.events.push(this.createEvent);
              this.resetCreateEvent();
            })
            .catch((err) => console.log(err))
            .finally(() => (this.loading = false));
      } else {
        axios
            .patch(
                `/api/calendar/${this.createEvent.id}`,
                this.createEvent,
            )
            .then((res) => {
              this.createEvent.start = new Date(
                  this.createEvent.start,
              );
              this.createEvent.end = new Date(
                  this.createEvent.end,
              );
              this.resetCreateEvent();
            })
            .catch((err) => console.log(err))
            .finally(() => (this.loading = false));
      }
    },

    resetCreateEvent() {
      this.createEvent = {
        name: null,
        start: null,
        end: null,
      };
    },

    //	マウスボタンが離されたとき
    endDrag() {
      if (this.createEvent.name !== null) {
        this.dialog = true;
        this.createEvent.start = this.dateFormat(
            this.createEvent.start,
        );
        this.createEvent.end = this.dateFormat(this.createEvent.end);
      }

      if (this.dragEvent !== null) {
        this.dragEvent.start = this.dateFormat(this.dragEvent.start);
        this.dragEvent.end = this.dateFormat(this.dragEvent.end);
        axios
            .patch(`/api/calendar/${this.dragEvent.id}`, this.dragEvent)
            .then(() => {
              this.dragEvent.start = new Date(this.dragEvent.start);
              this.dragEvent.end = new Date(this.dragEvent.end);
              this.dragEvent = null;
            })
            .catch((err) => console.log(err))
            .finally(() => (this.loading = false));
      }

      this.dragTime = null;
      this.createStart = null;
      this.extendOriginal = null;
    },

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

    cancelDrag() {
      if (this.createEvent) {
        if (this.extendOriginal) {
          this.createEvent.end = this.extendOriginal;
        } else {
          const i = this.events.indexOf(this.createEvent);
          if (i !== -1) {
            this.events.splice(i, 1);
          }
        }
      }

      // モーダルを追加したため
      // this.createEvent = null
      this.createStart = null;
      this.dragTime = null;
      this.dragEvent = null;
    },
    roundTime(time, down = true) {
      const roundTo = 15; // minutes
      const roundDownTime = roundTo * 60 * 1000;

      return down ?
                time - (time % roundDownTime) :
                time + (roundDownTime - (time % roundDownTime));
    },
    toTime(tms) {
      return new Date(
          tms.year,
          tms.month - 1,
          tms.day,
          tms.hour,
          tms.minute,
      ).getTime();
    },

    rndElement(arr) {
      return arr[this.rnd(0, arr.length - 1)];
    },

    // イベントの色について
    getEventColor(event) {
      return event.color;
    },
    rnd(a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a;
    },
  },
};
</script>
