<template>
  <div>
    <form action="" @submit.prevent="addToDo()">
      <div>
        <label>タイトル</label>
        <BaseInput v-model="todo.title" name="title"  type="text" :validation = '$v.todo.title.$error'></BaseInput>
        <ErrorRequired :validation = '$v.todo.title.$error' attribute="タイトル"></ErrorRequired>
      </div>        
      <div>
        <label>内容</label>
        <BaseTextarea v-model="todo.content" name="content" :rows="10" :cols="50" :validation = '$v.todo.content.$error'></BaseTextarea>
        <ErrorRequired :validation = '$v.todo.content.$error' attribute="内容"></ErrorRequired>
      </div>
      <div>
        <label>開始日</label>
        <BaseInput v-model="todo.start_date" name="start_date"  type="date" :validation = '$v.todo.start_date.$error'></BaseInput>
        <ErrorRequired :validation = '$v.todo.start_date.$error' attribute="開始日"></ErrorRequired>
      </div>
      <div>
        <label>期限</label>
        <BaseInput v-model="todo.end_date" name="end_date"  type="date" :validation = '$v.todo.end_date.$error'></BaseInput>
        <ErrorRequired :validation = '$v.todo.end_date.$error' attribute="開始日"></ErrorRequired>
      </div>

      <button type="submit">新規作成</button>
    </form>

  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators'

export default{
  data(){
    return{
      todo:{
        title:null,
        start_date:null, //今日の日付を入れたい
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
          start_date: {
              required,
          },
          end_date: {
              required,
          },
      }
  },
  methods:{
    addToDo(){
      this.$v.$touch();
      if (!this.$v.$invalid) {
        axios
        .post('/api/todos', this.todo)
        .then(response => (
            this.$router.push({ name: 'indexToDo' })
        ))
        .catch(err => console.log(err))
        .finally(() => this.loading = false)
        
      }
    }
  },

}
</script>
