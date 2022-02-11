<template>
  <div>
    <p>test</p>
    <router-link to="/todos/create" class="nav-item nav-link">新規作成</router-link>

    <table class="table">
      <thead>
      <tr>
          <th>タイトル</th>
          <th>内容</th>
          <th>開始日</th>
          <th>期限</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="todo in todos" :key="todo.id">
          <td>{{ todo.title }}</td>
          <td>{{ todo.content }}</td>
          <td>{{ todo.start_date }}</td>
          <td>{{ todo.end_date }}</td>
          <td>
              <div class="btn-group" role="group">
                  <router-link :to="{name: 'editToDo', params: { id: todo.id }}" class="btn btn-success">更新</router-link>
                  <button class="btn btn-danger" @click="deleteProduct(todo.id)">削除</button>
              </div>
          </td>
      </tr>
      </tbody>
    </table>

  </div>
</template>

<script >
  export default{
    data() {
      return{
        todos:[]
      }
    },
    created() {
      axios
      .get('/api/todos/')
      .then(response => {
          this.todos = response.data;
      });
    },
    methods:{
      deleteProduct(id) { 
          axios
              .delete(`api/todos/${id}`)
              .then(response => {
                  let i = this.todos.map(data => data.id).indexOf(id);
                  this.todos.splice(i, 1)
              });
      }
    }
  }
</script>
