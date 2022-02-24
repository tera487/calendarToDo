<template>
  <v-app>
    <div>
      {{events}}
      <v-sheet
        tile
        height="54"
        class="d-flex"
      >
        <v-btn
          icon
          class="ma-2"
          @click="$refs.calendar.prev()"
        >
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
        <v-select
          v-model="type"
          :items="types"
          dense
          outlined
          hide-details
          class="ma-2"
          label="type"
        ></v-select>
        <v-select
          v-model="mode"
          :items="modes"
          dense
          outlined
          hide-details
          label="event-overlap-mode"
          class="ma-2"
        ></v-select>
        <v-select
          v-model="weekday"
          :items="weekdays"
          dense
          outlined
          hide-details
          label="weekdays"
          class="ma-2"
        ></v-select>
        <v-spacer></v-spacer>
        <v-btn
          icon
          class="ma-2"
          @click="$refs.calendar.next()"
        >
          <v-icon>mdi-chevron-right</v-icon>
        </v-btn>
      </v-sheet>
      <v-sheet height="600">
        <v-calendar
          ref="calendar"
          v-model="value"
          :weekdays="weekday"
          :type="type"
          :events="events"
          :event-overlap-mode="mode"
          :event-overlap-threshold="30"
          :event-color="getEventColor"
          @click:date="viewDay"
          locale="ja-jp"
          :event-ripple="false"
          @mousedown:event="startDrag"
          @mousedown:time="startTime"
          @mousemove:time="mouseMove"
          @mouseup:time="endDrag" 
          @mouseleave.native="cancelDrag"
          @click:event="showEvent"
        ></v-calendar>
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
              
              <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn icon>
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
              <v-btn icon>
                <v-icon @click="deleteEvent(selectedEvent.id)">delete</v-icon>
              </v-btn>
              <v-btn icon>
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </v-toolbar>
            <v-card-text>
              <span v-html="dateFormat(selectedEvent.start)"></span>
              〜
              <span v-html="dateFormat(selectedEvent.end)"></span>

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
        <div class="text-center">
          <v-dialog
            v-model="dialog"
            width="500"
          >

            <v-card>
              <v-card-title class="text-h5 grey lighten-2">
                スケージュール
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-text-field
                    label="タイトル"
                    v-model="createEvent.name"
                    :error-messages="nameErrors"
                    @input="$v.createEvent.name.$touch()"
                    @blur="$v.createEvent.name.$touch()"
                  ></v-text-field>
                  <v-row>
                    <v-col>
                      <v-menu
                        v-model="start_date_form"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="createEvent.start"
                            label="タイムゾーン"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                            :error-messages="startErrors"
                            @input="$v.createEvent.start.$touch()"
                            @blur="$v.createEvent.start.$touch()"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          locale="ja-jp"
                          v-model="createEvent.start"
                          @input="start_date_form = false"
                        ></v-date-picker>
                      </v-menu>
                    </v-col>
                    <v-col>
                      <v-menu
                        v-model="end_date_form"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="createEvent.end"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                            :error-messages="endErrors"
                            @input="$v.createEvent.end.$touch()"
                            @blur="$v.createEvent.end.$touch()"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          locale="ja-jp"
                          v-model="createEvent.end"
                          @input="end_date_form = false"
                        ></v-date-picker>
                      </v-menu>
                    </v-col>

                  </v-row>
                </v-container>
              </v-card-text>

              <v-divider></v-divider>

              <v-card-actions>
                <v-spacer></v-spacer>
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
      </v-sheet>
    </div>
  </v-app>
</template>

<script>
  import { required } from 'vuelidate/lib/validators'

  export default {
    validations: {
      createEvent: {
        name:{required},
        start:{required},
        end:{required}
      },
    },
    data: () => ({
      type: 'week',
      types: ['month', 'week', 'day', '4day'],
      mode: 'column',
      modes: ['stack', 'column'],
      weekday: [0, 1, 2, 3, 4, 5, 6],
      weekdays: [
        { text: 'Sun - Sat', value: [0, 1, 2, 3, 4, 5, 6] },
        { text: 'Mon - Sun', value: [1, 2, 3, 4, 5, 6, 0] },
        { text: 'Mon - Fri', value: [1, 2, 3, 4, 5] },
        { text: 'Mon, Wed, Fri', value: [1, 3, 5] },
      ],
      value: '',
      events: [],
      colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
      names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],

      dragEvent: null,
      dragStart: null,
      createEvent: {
        name:null,
        start:null,
        end:null
      },
      createStart: null,
      extendOriginal: null,

      numberId:0,
      dialog: false,

      start_date_form:null,
      end_date_form:null,

      selectedEvent: {},
      selectedElement: null,
      selectedOpen: false,
    }),
    methods: {
      showEvent ({ nativeEvent, event }) {
        const open = () => {
          this.selectedEvent = event
          this.selectedElement = nativeEvent.target
          requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
        }

        if (this.selectedOpen) {
          this.selectedOpen = false
          requestAnimationFrame(() => requestAnimationFrame(() => open()))
        } else {
          open()
        }

        nativeEvent.stopPropagation()
      },
      // 日付をclickした際にその日付に遷移
      viewDay ({ date }) {
        this.focus = date
        this.type = 'day'
      },

      startDrag ({ event, timed }) {
        if (event && timed) {
          this.dragEvent = event
          this.dragTime = null
          this.extendOriginal = null
        }
      },

      startTime (tms) {
        const mouse = this.toTime(tms)

        if (this.dragEvent && this.dragTime === null) {
          const start = this.dragEvent.start

          this.dragTime = mouse - start
        } else {
          this.createStart = this.roundTime(mouse)
          this.createEvent = {
            name: `Event #${this.events.length}`,
            color: this.rndElement(this.colors),
            start: this.createStart,
            end: this.createStart,
            timed: true,
            id: 0,
          }

          this.events.push(this.createEvent)
        }
      },
      extendBottom (event) {
        this.createEvent = event
        this.createStart = event.start
        this.extendOriginal = event.end
      },

      // マウスポインタが要素の上を移動したとき	
      mouseMove (tms) {
        const mouse = this.toTime(tms)

        if (this.dragEvent && this.dragTime !== null) {
          const start = this.dragEvent.start
          const end = this.dragEvent.end
          const duration = end - start
          const newStartTime = mouse - this.dragTime
          const newStart = this.roundTime(newStartTime)
          const newEnd = newStart + duration

          this.dragEvent.start = newStart
          this.dragEvent.end = newEnd
        } else if (this.createEvent && this.createStart !== null) {
          const mouseRounded = this.roundTime(mouse, false)
          const min = Math.min(mouseRounded, this.createStart)
          const max = Math.max(mouseRounded, this.createStart)

          this.createEvent.start = min
          this.createEvent.end = max
        }
      },

      validationEventform(){
        this.$v.$touch();
        if (!this.$v.$invalid) {
          this.dialog = false
          axios.post('/api/calendar', this.createEvent)
            .then((res) => {
              this.createEvent.start = new Date(this.createEvent.start)
              this.createEvent.end = new Date(this.createEvent.end)
              this.createEvent.id =  res.data;

              this.events.push(this.createEvent)
              this.resetCreateEvent()
            })
            .catch(err => console.log(err))
            .finally(() => this.loading = false)
        }
      },

      resetCreateEvent(){
        this.createEvent = {
          name:null,
          start:null,
          end:null
        }
      },

      //	マウスボタンが離されたとき
      endDrag () {
        if(this.createEvent.name !== null){
          this.dialog = true
          this.createEvent.start = this.dateFormat(this.createEvent.start);
          this.createEvent.end = this.dateFormat(this.createEvent.end);
        }
        
        if(this.dragEvent !== null){
          this.dragEvent.start = this.dateFormat(this.dragEvent.start);
          this.dragEvent.end = this.dateFormat(this.dragEvent.end);
          axios.patch(`/api/calendar/${this.dragEvent.id}`, this.dragEvent)
            .then(() => {
              this.dragEvent.start = new Date(this.dragEvent.start)
              this.dragEvent.end = new Date(this.dragEvent.end)
              this.dragEvent = null
            })
            .catch(err => console.log(err))
            .finally(() => this.loading = false)
        }


        this.dragTime = null
        this.createStart = null
        this.extendOriginal = null
      },


      dateFormat(date){
        const formatedDate =  new Date(date) ;
        return formatedDate.getFullYear() + "-" + (formatedDate.getMonth() + 1) + "-" + formatedDate.getDate() + " " + formatedDate.getHours() + ":" + formatedDate.getMinutes() + ":" + formatedDate.getSeconds();
      },

      cancelDrag () {
        if (this.createEvent) {
          if (this.extendOriginal) {
            this.createEvent.end = this.extendOriginal
          } else {
            const i = this.events.indexOf(this.createEvent)
            if (i !== -1) {
              this.events.splice(i, 1)
            }
          }
        }

        // モーダルを追加したため
        // this.createEvent = null
        this.createStart = null
        this.dragTime = null
        this.dragEvent = null
      },
      roundTime (time, down = true) {
        const roundTo = 15 // minutes
        const roundDownTime = roundTo * 60 * 1000

        return down
          ? time - time % roundDownTime
          : time + (roundDownTime - (time % roundDownTime))
      },
      toTime (tms) {
        return new Date(tms.year, tms.month - 1, tms.day, tms.hour, tms.minute).getTime()
      },
      getEventColor (event) {
        const rgb = parseInt(event.color.substring(1), 16)
        const r = (rgb >> 16) & 0xFF
        const g = (rgb >> 8) & 0xFF
        const b = (rgb >> 0) & 0xFF

        return event === this.dragEvent
          ? `rgba(${r}, ${g}, ${b}, 0.7)`
          : event === this.createEvent
            ? `rgba(${r}, ${g}, ${b}, 0.7)`
            : event.color
      },
      rndElement (arr) {
        return arr[this.rnd(0, arr.length - 1)]
      },


      // イベントの色について
      getEventColor (event) {
        return event.color
      },
      rnd (a, b) {
        return Math.floor((b - a + 1) * Math.random()) + a
      },

      deleteEvent(id){
        axios.delete(`/api/calendar/${id}`)
            .then(() => {
              this.events = (this.events).filter((v) => v.id !== id)
              this.selectedOpen = false
            })
            .catch(err => console.log(err))
            .finally(() => this.loading = false)
      }
    },

    mounted(){
      axios
      .get('/api/calendar')
      .then(response => {
          const data = response.data
          for (let i = 0; i < response.data.length; i++) {
            response.data[i].start = new Date(response.data[i].start)
            response.data[i].end = new Date(response.data[i].end)
            response.data[i].timed = true
            response.data[i].color = this.rndElement(this.colors)
            response.data[i].id = response.data[i].id
          }
          this.events= response.data;
      });
    },
    computed: {
    nameErrors () {
      const errors = []
      if (!this.$v.createEvent.name.$dirty) return errors
      !this.$v.createEvent.name.required && errors.push('タイトルは必須です。')
      return errors
    },
    startErrors () {
      const errors = []
      if (!this.$v.createEvent.start.$dirty) return errors
      !this.$v.createEvent.start.required && errors.push('タイムゾーンは必須です。')
      return errors
    },
    endErrors () {
      const errors = []
      if (!this.$v.createEvent.end.$dirty) return errors
      !this.$v.createEvent.end.required && errors.push('タイムゾーンは必須です。')
      return errors
    },
  },
  }
</script>