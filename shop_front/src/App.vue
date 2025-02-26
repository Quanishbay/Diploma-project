<template>
  <div class="container mx-auto px-4 py-2">
    <nav class="flex items-center justify-between py-4 border-b border-gray-300 bg-white shadow-md px-6 rounded-lg">
      <!-- Слева: ссылки Home, About us, Contacts -->
      <div class="flex space-x-6">
        <router-link to="/home" class="nav-link">Home</router-link>
        <router-link to="/about" class="nav-link">About us</router-link>
        <router-link to="/contacts" class="nav-link">Contacts</router-link>
      </div>

      <!-- Справа: Login, Register, Logout -->
      <div class="flex space-x-6">
        <router-link v-if="!isAuthenticated" to="/login" class="nav-link">Login</router-link>
        <router-link v-if="!isAuthenticated" to="/register" class="nav-link">Register</router-link>
        <router-link v-if="isAuthenticated" to="/cart" class="nav-link">Cart</router-link>
        <router-link v-if="isAdmin" to="/edit/categories" class="nav-link">Edit Categories</router-link>
        <router-link v-if="isAuthenticated" to="/categories" class="nav-link">Categories</router-link>
        <button v-if="isAuthenticated" @click="logout" class="nav-link">Logout</button>
        <button v-if="userData" @click="profilePage(userData.id)" class="nav-link">
          {{ userData.name }}
        </button>
      </div>
    </nav>
    <router-view></router-view>
  </div>

  <AboutUs></AboutUs>

</template>

<script>
import axios from "axios";
import AboutUs from "@/components/Navigation/AboutUs.vue";

export default {
  components:{
    AboutUs
  },
  data() {
    return {
      userData: null
    };
  },
  computed: {
    isAuthenticated() {
      return !!localStorage.getItem("access_token");
    },
    isAdmin() {
      return localStorage.getItem("role") === "admin";
    }
  },
  methods: {
    async getProfile() {
      try {
        const response = await axios.post(
            "http://localhost:8000/api/auth/me",
            {},
            {
              headers: {
                Authorization: `Bearer ${localStorage.getItem("access_token")}`
              }
            }
        );
        this.userData = response.data;
      } catch (error) {
        console.error("Ошибка загрузки профиля:", error);
      }
    },

    logout() {
      localStorage.removeItem("access_token");
      localStorage.removeItem("role");
      this.userData = null;
      this.$router.push("/login");
    },

    profilePage(id) {
      this.$router.push(`/profile/${id}`);
    }
  },
  watch: {
    isAuthenticated(newVal) {
      if (newVal) {
        this.getProfile();
      } else {
        this.userData = null;
      }
    }
  },
  mounted() {
    if (this.isAuthenticated) {
      this.getProfile();
    }
  }
};
</script>

<style scoped>
.nav-link {
  font-size: 1rem;
  font-weight: 500;
  color: #007bff;
  text-decoration: none;
  transition: color 0.3s ease-in-out;
}

.nav-link:hover {
  color: #0056b3;
}

nav {
  background: white;
  border-radius: 8px;
  padding: 10px 20px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
