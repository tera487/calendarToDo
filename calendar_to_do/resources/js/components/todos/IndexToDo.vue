<template>
  <v-app>
    <router-link
      :to="{name: 'createToDo'}"
      class="nav-item nav-link"
    >
      <v-btn
        elevation="2"
        color="green accent-3"
      >
        新規作成
      </v-btn>
    </router-link>

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
        <tr
          v-for="todo in todos"
          :key="todo.id"
        >
          <td>{{ todo.title }}</td>
          <td>{{ todo.content }}</td>
          <td>{{ todo.start_date }}</td>
          <td>{{ todo.end_date }}</td>
          <td>
            <div
              class="btn-group"
              role="group"
            >
              <router-link
                :to="{name: 'editToDo', params: { id: todo.id }}"
                class="btn btn-success"
              >
                更新
              </router-link>
              <button
                class="btn btn-danger"
                @click="deleteProduct(todo.id)"
              >
                削除
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </v-app>
</template>

<script >
export default {
  data() {
    return {
      todos: [],
    };
  },
  created() {
    axios
        .get('/api/todo/')
        .then((response) => {
          this.todos = response.data;
        });
  },
  methods: {
    deleteProduct(id) {
      axios
          .delete(`api/todo/${id}`)
          .then(() => {
            const i = this.todos.map((data) => data.id).indexOf(id);
            this.todos.splice(i, 1);
          })
          .catch((err) => console.log(err));
    },
  },
};
</script>
