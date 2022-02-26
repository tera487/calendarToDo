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
                ></v-avatar>

                <div>{{user.email}} </div>
            </v-sheet>

            <v-divider></v-divider>

            <v-list>
                <router-link :to="{name: path}" v-for="[path, icon, text] in links"
                    :key="icon">
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
            </v-navigation-drawer>

            <v-main>
                <main class="mt-3" style="max-width: 90%;     margin: 0 auto;">
                    <router-view></router-view>
                </main>
            </v-main>
        </v-app>
    </div>
</template>

<script>
import {mapActions} from 'vuex'
export default {
    name:"dashboard-layout",

    data () {
      return {
        cards: ['Today', 'Yesterday'],
        drawer: null,
        links: [
            ['indexCalendar','event', 'calendar'],
            ['indexToDo','task', 'Todo'],
            ['indexUser','account_circle', 'account'],
            ['indexGeneralSetting','settings', 'setting'],
        ],
        user:this.$store.state.auth.user
      }
    },
    methods:{
        ...mapActions({
            signOut:"auth/logout"
        }),
        async logout(){
            await axios.post('/logout').then(({data})=>{
                this.signOut()
                this.$router.push({name:"login"})
            })
        }
    }
}
</script>

<style scoped>
a{
    text-decoration: none;
}
</style>