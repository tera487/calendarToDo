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

        <detail-calendar-dialog
          :selected-event="selectedEvent"
          :is-selected-open="selectedOpen"
          :selected-element="selectedElement"
          @openDeleteDialog="deleteDialog = true"
          @closeDetailDialog="selectedOpen = false"
          @editEvent="editEvent()"
        />

        <!-- スケジュール追加・編集のダイアログ -->
        <the-add-calendar
          :is-dialog="isDialog"
          :create-event="createEvent"
          @resetCreateEvent="resetCreateEvent()"
          @optional-name="createEvent.name = $event"
          @optional-date-start="createEvent.start = $event"
          @optional-date-end="createEvent.end = $event"
          @changeDescription="createEvent.description = $event"
          @changeisSend="createEvent.is_send = $event"
          @changeisAllDay="createEvent.is_all_day = $event"
          @validationEventform="validationEventform()"
        />

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
import DetailCalendarDialog from './DetailCalendarDialog.vue';
import DeleteCalendarDialog from './DeleteCalendarDialog.vue';
import TheHerdarCalendar from './TheHerdarCalendar.vue';
import TheAddCalendar from './TheAddCalendar.vue';

export default {
  components: {
    TheHerdarCalendar,
    DeleteCalendarDialog,
    DetailCalendarDialog,
    TheAddCalendar,
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
      description: null,
      is_send: false,
      is_all_day: false,
    },

    createStart: null,
    extendOriginal: null,

    numberId: 0,
    isDialog: false,

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
        response.data[i].start = this.adjustDate(response.data[i].state, response.data[i].is_all_day);
        response.data[i].end = this.adjustDate(response.data[i].end, response.data[i].is_all_day);
        response.data[i].timed = true;
        response.data[i].color = this.rndElement(this.colors);
        response.data[i].id = response.data[i].id;
      }
      this.events = response.data;
    });
  },
  methods: {
    adjustDate(date, isAllDay) {
      return isAllDay ? this.setAllDay(date):new Date(date);
    },
    setAllDay(data) {
      const setDate = data.split(' ');
      return `${setDate[0]}`;
    },
    colseDeleteDialog(id) {
      if (id !== undefined) {
        this.events = this.events.filter((v) => v.id !== id);
        this.selectedOpen = false;
      }
      this.deleteDialog = false;
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
          description: null,
          is_send: false,
          is_all_day: false,
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
      this.isDialog = true;
      this.createEvent = this.selectedEvent;
      this.createEvent.start = this.dateFormat(this.selectedEvent.start);
      this.createEvent.end = this.dateFormat(this.selectedEvent.end);
      this.createEvent.description = this.selectedEvent.description;
    },
    validationEventform() {
      this.isDialog = false;
      if (this.createEvent.id !== this.selectedEvent.id) {
        if (this.createEvent.is_all_day) {
          this.createEvent.start = this.setAllDay(this.createEvent.start);
          this.createEvent.end = this.setAllDay(this.createEvent.end);
        }

        axios
            .post('/api/calendar', this.createEvent)
            .then((res) => {
              this.createEvent.start = this.adjustDate(this.createEvent.start, this.createEvent.is_all_day);
              this.createEvent.end = this.adjustDate(this.createEvent.end, this.createEvent.is_all_day);
              this.createEvent.id = res.data;

              this.events.push(this.createEvent);
              this.resetCreateEvent();
            })
            .catch((err) => console.log(err))
            .finally(() => (this.loading = false));
      } else {
        axios
            .patch(`/api/calendar/${this.createEvent.id}`, this.createEvent)
            .then((res) => {
              this.createEvent.start = this.adjustDate(this.createEvent.start, this.createEvent.is_all_day);
              this.createEvent.end = this.adjustDate(this.createEvent.end, this.createEvent.is_all_day);
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
        description: null,
        is_send: false,
        is_all_day: false,
      };
      this.isDialog = false;
    },

    // マウスボタンが離されたとき
    endDrag() {
      if (this.createEvent.name !== null) {
        this.isDialog = true;
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
