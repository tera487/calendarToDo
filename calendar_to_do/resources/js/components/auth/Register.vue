<template>
  <div class="container h-100">
    <div class="row h-100 align-items-center">
      <div class="col-12 col-md-6 offset-md-3">
        <div class="card shadow sm">
          <div class="card-body">
            <h1 class="text-center">
              新規登録
            </h1>
            <hr>
            <form
              action="javascript:void(0)"
              class="row"
              method="post"
              @submit="register"
            >
              <div class="form-group col-12">
                <label
                  for="name"
                  class="font-weight-bold"
                >Name</label>
                <input
                  id="name"
                  v-model="user.name"
                  type="text"
                  name="name"
                  placeholder="Enter name"
                  class="form-control"
                >
              </div>
              <div class="form-group col-12">
                <label
                  for="email"
                  class="font-weight-bold"
                >Email</label>
                <input
                  id="email"
                  v-model="user.email"
                  type="text"
                  name="email"
                  placeholder="Enter Email"
                  class="form-control"
                >
              </div>
              <div class="form-group col-12">
                <label
                  for="password"
                  class="font-weight-bold"
                >Password</label>
                <input
                  id="password"
                  v-model="user.password"
                  type="password"
                  name="password"
                  placeholder="Enter Password"
                  class="form-control"
                >
              </div>
              <div class="form-group col-12">
                <label
                  for="password_confirmation"
                  class="font-weight-bold"
                >Confirm Password</label>
                <input
                  id="password_confirmation"
                  v-model="user.password_confirmation"
                  type="password_confirmation"
                  name="password_confirmation"
                  placeholder="Enter Password"
                  class="form-control"
                >
              </div>
              <div class="col-12 mb-2">
                <button
                  type="submit"
                  :disabled="processing"
                  class="btn btn-primary btn-block"
                >
                  {{ processing ? "Please wait" : "Register" }}
                </button>
              </div>
              <div class="col-12 text-center">
                <label>Already have an account? <router-link :to="{name:'login'}">Login Now!</router-link></label>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
    name:'Register',
    data(){
        return {
            user:{
                name:"",
                email:"",
                password:"",
                password_confirmation:""
            },
            processing:false
        }
    },
    methods:{
        ...mapActions({
            signIn:'auth/login'
        }),
        async register(){
            this.processing = true
            await axios.post('/register',this.user).then(response=>{
                this.signIn()
            }).catch(({response:{data}})=>{
                alert(data.message)
            }).finally(()=>{
                this.processing = false
            })
        }
    }
}
</script>