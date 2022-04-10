<template>
  <div class="container h-100">
    <div class="row h-100 align-items-center">
      <div class="col-12 col-md-6 offset-md-3">
        <div class="card shadow sm">
          <div class="card-body">
            <h1 class="text-center">
              Login
            </h1>
            <hr>
            <form
              action="javascript:void(0)"
              class="row"
              method="post"
            >
              <div class="form-group col-12">
                <label
                  for="email"
                  class="font-weight-bold"
                >Email</label>
                <input
                  id="email"
                  v-model="auth.email"
                  type="text"
                  name="email"
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
                  v-model="auth.password"
                  type="password"
                  name="password"
                  class="form-control"
                >
              </div>
              <div class="col-12 mb-2">
                <button
                  type="submit"
                  :disabled="processing"
                  class="btn btn-primary btn-block"
                  @click="login"
                >
                  {{ processing ? "Please wait" : "Login" }}
                </button>
              </div>
              <div class="col-12 text-center">
                <label>Did you forget your password?<router-link :to="{name:'passwordReset'}">Password reset!</router-link></label>
              </div>
              <div class="col-12 text-center">
                <label>Don't have an account? <router-link :to="{name:'register'}">Register Now!</router-link></label>
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
    name:"Login",
    data(){
        return {
            auth:{
                email:"",
                password:""
            },
            processing:false
        }
    },
    methods:{
        ...mapActions({
            signIn:'auth/login'
        }),
        async login(){
            this.processing = true
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/login',this.auth).then(({data})=>{
                this.signIn()
            }).catch(({response:{data}})=>{
                alert(data.message)
            }).finally(()=>{
                this.processing = false
            })
        },
    }
}
</script>