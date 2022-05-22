<template>
  <div class="container h-100">
    <div class="row h-100 align-items-center">
      <div class="col-12 col-md-6 offset-md-3">
        <div class="card shadow sm">
          <div class="card-body">
            <h1 class="text-center">
              Resetting a password
            </h1>
            <hr>
            <form
              method="POST"
              class="row"
              @submit.prevent="sendMail()"
            >
              <div class="form-group col-12">
                <label
                  for="email"
                  class="font-weight-bold"
                >Email</label>
                <input
                  id="email"
                  v-model="email"
                  type="text"
                  name="email"
                  class="form-control"
                >
              </div>
              <div class="col-12 mb-2">
                <button class="btn btn-primary btn-block">
                  Send
                </button>
              </div>
            </form>
          </div>
          <div class="col-12 text-center">
            <label>Already have an account?
              <router-link :to="{ name: 'login' }">Login Now!</router-link></label>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return{
      email:null,
      processing:false,
    }
  },
  methods:{
  sendMail(){
    if (this.processing) return;
    this.processing = true;
    axios.post('/api/passwordReset/email',{email:this.email})
    .then(() => this.$router.push({ name: 'sendComplete' }))
    .catch(()=> this.processing = false)
        
    }
  }
}
</script>

