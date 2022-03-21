<template>
  <v-app>
    <v-container  fluid>
      <v-row  align="center">
        <v-col cols="12">
          <h3>一般設定</h3>
        </v-col>
        <v-col cols="12">
          <h3>Calendar</h3>
        </v-col>
      </v-row>
      <v-col cols="3">
        <v-select
          :items="types"
          hide-details
          filled
          dense
          label="type"
          v-model="calendar_json.type"
        ></v-select>
      </v-col>
      <v-col cols="3">
        <v-select
          :items="modes"
          hide-details
          filled
          dense
          label="イベントの表示方法"
          v-model="calendar_json.mode"
        ></v-select>
      </v-col>
      <v-col cols="3">
        <v-select
          :items="weekdays"
          hide-details
          filled
          dense
          label="weekdays"
          v-model="calendar_json.weekday"
        ></v-select>
      </v-col>
    </v-container>
  </v-app>
</template>


<script>
export default{
  data(){
    return{
      types: ['month', 'week', 'day', '4day'],
      modes: ['stack', 'column'],
      weekdays: [
        { text: 'Sun - Sat', value: [0, 1, 2, 3, 4, 5, 6] },
        { text: 'Mon - Sun', value: [1, 2, 3, 4, 5, 6, 0] },
        { text: 'Mon - Fri', value: [1, 2, 3, 4, 5] },
        { text: 'Mon, Wed, Fri', value: [1, 3, 5] },
      ],

      calendar_json:{
        type: null,
        mode: null,
        weekday: null,
      },
    }
  },
  mounted(){
    axios
      .get(`/api/generalSetting/${this.$store.state.auth.user.id}`)
      .then(response => {
        this.calendar_json = response.data.calendar_json;
       });
  },

  watch:{
    calendar_json:{
      handler: function(newCalendarJson, oldCalendarJson){
      if(oldCalendarJson.type !== null && oldCalendarJson.mode !== null &&oldCalendarJson.weekday ){
        axios.patch('api/generalSetting/1', this.calendar_json);
      }},
      deep : true,

    }
  }
}
</script>
