<template>
  <v-autocomplete
    v-if="isSearch"
    v-model="searchedText"
    autofocus
    solos
    :items="calendarEvents"
    item-text="name"
    item-value="name"
    label="Prepend inner"
    prepend-inner-icon="mdi-map-marker"
    @blur="$parent.isCalendarSearch = false"
    @input="$router.push({ name: 'searchCalendar', query: {searchItem: searchedText}})"
  />
</template>

<script>
export default {
  props: {
    isSearch: {type: Boolean, default: false},
  },
  data() {
    return {
      calendarEvents: null,
      searchedText: null,
    };
  },
  watch: {
    'isSearch': function() {
      if (this.isSearch == true) {
        axios.get('/api/calendar').then((response) => {
          this.calendarEvents = response.data;
        });
      }
    },
  },
};
</script>
