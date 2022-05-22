<template>
  <div>
    <v-app id="inspire">
      <v-navigation-drawer
        v-model="drawer"
        app
      >
        <v-sheet
          color="grey lighten-4"
          class="pa-4"
        >
          <v-avatar
            class="mb-4"
            color="grey darken-1"
            size="64"
          >
            <img
              :src="user.icon_path"
              alt="UserIcon"
            >
          </v-avatar>

          <div>{{ user.name }}</div>
          <div>{{ user.email }}</div>
        </v-sheet>

        <v-divider />

        <v-list>
          <router-link
            v-for="[path, icon, text] in links"
            :key="icon"
            :to="{name: path,params:{id:user.id}}"
          >
            <v-list-item
              link
            >
              <v-list-item-icon>
                <v-icon>{{ icon }}</v-icon>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title>{{ text }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </router-link>
        </v-list>
        <v-container>
          <v-row justify="center">
            <v-date-picker
              v-model="picker"
              no-title
              width="inherit"
              locale="ja-jp"
            />
          </v-row>
        </v-container>
      </v-navigation-drawer>

      <v-main>
        <main
          class="mt-3"
          style="max-width: 90%; margin: 0 auto;"
        >
          <router-view />
        </main>
      </v-main>
    </v-app>
  </div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
  name: 'DashboardLayout',

  data() {
    return {
      cards: ['Today', 'Yesterday'],
      drawer: null,
      links: [
        ['indexCalendar', 'event', 'Calendar'],
        ['indexToDo', 'task', 'Todo'],
        ['indexUser', 'account_circle', 'Account'],
        ['showGeneralSetting', 'settings', 'Setting'],
      ],
      picker: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
    };
  },
  computed: {
    ...mapGetters('auth', [
      'user',
    ]),
  },
};
</script>

<style scoped>
a{
    text-decoration: none;
}
</style>
