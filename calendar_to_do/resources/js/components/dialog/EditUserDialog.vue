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
              <template>
                <v-file-input
                  label="File input"
                  filled
                  prepend-icon="mdi-camera"
                  @change="handleFileUpload($event)"
                />
              </template>
              <input
                type="file"
                @change="handleFileUpload($event)"
              >
              <v-text-field
                v-model="user.name"
                label="name"
              />
              <v-text-field
                v-model="user.email"
                label="email"
              />
              <v-text-field
                v-model="user.password"
                label="password"
              />
            </v-container>
          </v-card-text>

          <v-divider />

          <v-card-actions>
            <v-spacer />
            <v-btn
              color="primary"
              text
              @click="updateUser()"
            >
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
                icon:null,
                password: null,
            },
        };
    },
    // エラーメッセージの作成
    computed: {},
    mounted() {
        this.user.name = this.$store.state.auth.user.name;
        this.user.email = this.$store.state.auth.user.email;
        this.user.icon = this.$store.state.auth.user.icon;
    },
    methods: {
        updateUser() {
          let formData = new FormData();
          if(this.user.password !== null){
            formData.append('password', this.user.password);
          } 
          formData.append('name', this.user.name);
          formData.append('email', this.user.email);
          formData.append('icon', this.user.icon);
          formData.append('_method', 'PATCH');
          axios
            .post(`/api/users/${this.$store.state.auth.user.id}`, formData, {headers: {'content-type': 'multipart/form-data'}})
            .then(() => {
                this.$emit('colseDialog');
            })
            .catch((err) => console.log(err))
            .finally(() => (this.loading = false));
        },
      handleFileUpload( event ){
        this.user.icon = event;
      },
    },
};
</script>
