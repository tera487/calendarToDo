<template>
    <v-app class="my-10">
        <v-card elevation="2" class="mb-5">
            <v-list-item three-line>
                <v-list-item-content>
                    <v-list-item-title class="text-h5 mb-1">
                        <p>アカウント情報</p>
                    </v-list-item-title>
                    <div class="mb-2">name: {{ user.name }}</div>
                    <div class="mb-2">email: {{ user.email }}</div>
                    <div class="mb-2">
                        アカウント作成日: {{ moment(user.created_at) }}
                    </div>
                </v-list-item-content>
            </v-list-item>
        </v-card>
        <v-btn elevation="2" @click="logout" style="max-width: 200px"
            >ログアウト</v-btn
        >
    </v-app>
</template>

<script>
import { mapActions } from 'vuex';
import moment from 'moment';
export default {
    name: 'dashboard-layout',
    data() {
        return {
            user: this.$store.state.auth.user,
        };
    },
    methods: {
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
