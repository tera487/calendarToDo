<template>
    <v-app>
        <div class="text-center">
            <v-dialog
                value="true"
                width="500"
                @click:outside="$emit('colseDialog')"
            >
                <v-card>
                    <v-card-title class="text-h5 grey lighten-2">
                        アカウント編集
                    </v-card-title>

                    <v-card-text>
                        <v-container>
                            <v-text-field
                                label="name"
                                v-model="user.name"
                            ></v-text-field>
                            <v-text-field
                                label="email"
                                v-model="user.email"
                            ></v-text-field>
                            <v-text-field
                                label="password"
                                v-model="user.password"
                            ></v-text-field>
                        </v-container>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" text @click="updateUser()">
                            更新
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>
    </v-app>
</template>

<script>
import { required } from 'vuelidate/lib/validators';

export default {
    validations: {},
    data() {
        return {
            user: {
                name: null,
                email: null,
                password: null,
            },
            user: this.$store.state.auth.user,
        };
    },
    // エラーメッセージの作成
    computed: {},
    methods: {
        updateUser() {
            axios
                .patch(`/api/users/${this.user.id}`, this.user)
                .then(() => {
                    this.$emit('colseDialog');
                })
                .catch((err) => console.log(err))
                .finally(() => (this.loading = false));
        },
    },
    mounted() {
        this.name = this.$store.state.auth.user.name;
        this.email = this.$store.state.auth.user.email;
    },
};
</script>
