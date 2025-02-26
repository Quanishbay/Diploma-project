<script>
import axios from "axios";

export default {
  data(){
    return {
      email: '',
      password: '',
      role: '',
    }
  },
  methods:{
    login(){
      axios.post('http://localhost:8000/api/auth/login', {'email': this.email, 'password': this.password})
          .then((response) => {
            localStorage.setItem('access_token', response.data.access_token)
            this.$router.push('/categories')
            this.check_admin()
          })
          .catch((error) =>
      {
        console.log(error);
      })
    },
    check_admin(){
      axios.post('http://localhost:8000/api/auth/me', {}, {
        headers:{
          'Authorization': `Bearer ${localStorage.getItem('access_token')}`
        }
      }).then((response) => {
        localStorage.setItem('role', response.data.role)
      })
    },
    check_token(){
      axios.post('http://localhost:8000/api/auth/me', {}, {
        headers:{
          'Authorization': localStorage.getItem('access_token')
        }
      }).then((response =>
      {
        console.log(response);
      })
      )
    }
  },

}
</script>

<template>
  <div class="max-w-sm mx-auto bg-white p-6 rounded-lg shadow-lg mt-10">
    <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

    <div class="space-y-4">
      <!-- Поле для email -->
      <input
          v-model="email"
          type="email"
          placeholder="Email"
          class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
      />

      <!-- Поле для пароля -->
      <input
          v-model="password"
          type="password"
          placeholder="Password"
          class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
      />

      <!-- Кнопка для отправки -->
      <button
          @click="login"
          class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        Login
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>