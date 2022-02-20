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
        ></v-calendar>
      </v-sheet>
    </div>
  </v-app>
</template>

<script>
  export default {
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
      createEvent: null,
      createStart: null,
      extendOriginal: null,
      numberId:0,
    }),
    methods: {
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
            id: this.addEventId()
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

      //	マウスボタンが離されたとき
      endDrag () {
        if(this.createEvent !== null){
          this.createEvent.start = this.dateFormat(this.createEvent.start);
          this.createEvent.end = this.dateFormat(this.createEvent.end);
          axios.post('/api/calendar', this.createEvent)
            .then((res) => {
              this.createEvent.start = new Date(this.createEvent.start)
              this.createEvent.end = new Date(this.createEvent.end)
              this.createEvent.id =  res.data;
              this.createEvent = null
            })
            .catch(err => console.log(err))
            .finally(() => this.loading = false)
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

        this.createEvent = null
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
      addEventId(){
        return this.numberId +=1
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
    }
  }
</script>