<template>
  <v-app>
    <form action="" @submit.prevent="addToDo()">
        <v-text-field
          v-model="todo.title"
          :error-messages="titleErrors"
          :counter="10"
          label="タイトル"
          @input="$v.todo.title.$touch()"
          @blur="$v.todo.title.$touch()"
        ></v-text-field>
        
       <v-textarea
        name="input-7-1"
        label="内容"
        v-model="todo.content"
        :error-messages="contentErrors"
        @input="$v.todo.content.$touch()"
        @blur="$v.todo.content.$touch()"
      ></v-textarea>

      <v-menu
        v-model="start_date_form"
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        min-width="auto"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="todo.start_date"
            label="開始日"

            readonly
            v-bind="attrs"
            v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="todo.start_date"
          locale="ja-jp"
          @input="start_date_form = false"
        ></v-date-picker>
      </v-menu>
      <v-menu
        v-model="end_date_form"
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        min-width="auto"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="todo.end_date"
            label="期限"

            readonly
            v-bind="attrs"
            v-on="on"
            :error-messages="endDateErrors"
            @input="$v.todo.end_date.$touch()"
            @blur="$v.todo.end_date.$touch()"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="todo.end_date"
          locale="ja-jp"
          @input="end_date_form = false"
        ></v-date-picker>
      </v-menu>

      <v-btn
      class="mr-4"
      type="submit"
      >
        新規作成
      </v-btn>
    </form>

  </v-app>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'

export default{
  mixins: [validationMixin],
  data(){
    return{
      start_date_form:null,
      end_date_form:null,
      todo:{
      },
      errors: null,
    }  
  },
  validations: {
      todo: {
          title: {
              required,
          },
          content: {
              required,
          },
          // start_date: {
          //     required,
          // },
          end_date: {
              required,
          },
      }
  },
  methods:{
    addToDo(){
      this.$v.$touch();
      if (!this.$v.$invalid) {
        if(this.$route.params.id){
          axios
          .post(`/api/todo/${this.$route.params.id}`, this.todo)
              .then((res) => {
                  this.$router.push({ name: 'indexToDo' });
              });
        }else{
          axios.post('/api/todo', this.todo)
          .then(response => (
            this.$router.push({ name: 'indexToDo' })
          ))
          .catch(err => console.log(err))
          .finally(() => this.loading = false)
        }  
      }
    },
    // submit () {
    //   this.$v.$touch()
    // },
  },
  computed: {
    titleErrors () {
      const errors = []
      if (!this.$v.todo.title.$dirty) return errors
      !this.$v.todo.title.required && errors.push('タイトルは必須です。')
      return errors
    },
    contentErrors () {
      const errors = []
      if (!this.$v.todo.content.$dirty) return errors
      !this.$v.todo.content.required && errors.push('内容は必須です。')
      return errors
    },
    endDateErrors () {
      const errors = []
      if (!this.$v.todo.end_date.$dirty) return errors
      !this.$v.todo.end_date.required && errors.push('期限は必須です。')
      return errors
    },
  },

  created() {
    if(this.$route.params.id){
      axios
          .get(`/api/todos/${this.$route.params.id}`)
          .then((res) => {
              this.todo = res.data;
          });
    }
  },

}
</script>
