<template>
  <div>
    <form action="" @submit.prevent="updateToDo()">
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

      <button type="submit">更新</button>
    </form>

  </div>
</template>


<script>
export default{
  data(){
    return{
      todo:{}
    }  
  },
  methods:{
    updateToDo() {
        this.axios
            .patch(`/api/todos/${this.$route.params.id}`, this.todo)
            .then((res) => {
                this.$router.push({ name: 'indexToDo' });
            });
    }
  },

  created() {
      this.axios
          .get(`/api/todos/${this.$route.params.id}`)
          .then((res) => {
              this.todo = res.data;
          });
  },

}
</script>
