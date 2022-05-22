<template>
  <div class="container h-100">
    <div class="row h-100 align-items-center">
      <div class="col-12 col-md-6 offset-md-3">
        <div class="card shadow sm">
          <div class="card-body">
            <h1 class="text-center">
              A password reset email has been sent.
            </h1>
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
}
</script>

