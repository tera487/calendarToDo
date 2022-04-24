<template>
  <v-app class="my-10">
    <v-card
      elevation="2"
      class="mb-5"
      style="max-width: 500px"
    >
      <v-list-item three-line>
        <v-list-item-content>
          <v-list-item-title
            class="text-h5 mb-1 d-flex justify-space-between"
          >
            <p>アカウント情報</p>
            <v-btn
              elevation="2"
              style="max-width: 100px"
              @click="openEditDialog"
            >
              編集
            </v-btn>
          </v-list-item-title>

          <div class="mb-2">
            name: {{ user.name }}
          </div>
          <div class="mb-2">
            email: {{ user.email }}
          </div>
          <div class="mb-2">
            アカウント作成日: {{ moment(user.created_at) }}
          </div>
        </v-list-item-content>
      </v-list-item>
    </v-card>
    <v-btn
      elevation="2"
      style="max-width: 150px"
      @click="logout"
    >
      ログアウト
    </v-btn>
    <edit-user-dialog
      v-if="openDialog"
      @colseDialog="colseEditDialog"
    />
  </v-app>
</template>

<script>
import { mapActions } from 'vuex';
import moment from 'moment';
import EditUserDialog from '../dialog/EditUserDialog.vue';

export default {
    components: {
        EditUserDialog,
    },
    data() {
        return {
            openDialog: false,
            user: this.$store.state.auth.user,
        };
    },
    methods: {
        openEditDialog() {
            this.openDialog = true;
        },
        colseEditDialog() {
          this.user = this.$store.state.auth.user,
          this.openDialog = false;
        },
        ...mapActions({
            signOut: 'auth/logout',
        }),
        async logout() {
            await axios.post('/logout').then(({ data }) => {
                this.signOut();
                this.$router.push({ name: 'login' });
            });
        },
        moment(date) {
            return moment(date).format('MM/DD/YYYY hh:mm');
        },
    },
};
</script>
