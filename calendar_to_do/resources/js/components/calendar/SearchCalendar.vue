<template>
  <v-app>
    <v-data-table
      :headers="headers"
      :items="events"
      :items-per-page="15"
      class="elevation-1"
    />
  </v-app>
</template>

<script>

export default {
  data() {
    return {
      headers: [
        {
          text: 'タイトル',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        {text: '日付', value: 'start'},
        {text: '', value: 'end'},
        {text: '作成/編集日時', value: 'updated_at'},

      ],
      events: [],
    };
  },
  mounted() {
    axios
        .get('/api/calendars/search', {params: {searchItem: decodeURI(this.$route.query.searchItem)}})
        .then((res) => {
          this.events = res.data;
        })
        .catch((err) => console.log(err));
  },
};
</script>
