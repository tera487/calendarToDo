<template>
  <div>
    <form action="" @submit.prevent="addToDo()">
      <div>
        <label>タイトル</label>
        <input type="text" v-model="todo.title">
      </div>
      <div>
        <label>内容</label>
        <textarea name="" id="" cols="30" rows="10" v-model="todo.content"></textarea>
      </div>
      <div>
        <label>開始日</label>
        <input type="date" v-model="todo.start_date">
      </div>
      <div>
        <label>期限</label>
        <input type="date" v-model="todo.end_date">
      </div>

      <button type="submit">新規作成</button>
    </form>

  </div>
</template>

<script>
export default{
  data(){
    return{
      todo:{
        start_date:null, //今日の日付を入れたい
      }
    }  
  },
  methods:{
    addToDo(){
      this.axios
      .post('/api/todos', this.todo)
      .then(response => (
          this.$router.push({ name: 'indexToDo' })
      ))
      .catch(err => console.log(err))
      .finally(() => this.loading = false)
    }
  },

}
</script>
