<script setup>
import { computed, ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

// Mengakses Vuex store
const store = useStore();
const router = useRouter();

// Mengakses state dari store
const email = computed({
  get: () => store.state.email,
  set: (value) => store.commit("setEmail", value),
});
const password = computed({
  get: () => store.state.password,
  set: (value) => store.commit("setPassword", value),
});
const errorMessage = computed(() => store.state.errorMessage);

// Mengatur state untuk password visibility
const showPassword = ref(false);
const passwordType = ref("password"); // default 'password' type

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
  passwordType.value = showPassword.value ? "text" : "password";
};

// Fungsi login untuk mengirim request ke backend
const login = async () => {
  try {
    await store.dispatch("login");
    router.push({ name: "home" }); // Mengarahkan ke rute 'home'
  } catch (error) {
    console.error("Login error:", error);
  }
};
</script>

<template>
  <div
    class="container d-flex justify-content-center align-items-center vh-100"
  >
    <div class="card p-4" style="width: 400px">
      <h3 class="text-center mb-4">Login your account</h3>
      <form @submit.prevent="login">
        <!-- Email Input -->
        <div class="form-group mb-3">
          <label for="email">Email</label>
          <input
            type="email"
            v-model="email"
            class="form-control"
            id="email"
            placeholder=""
            required
          />
        </div>

        <!-- Password Input with Show/Hide -->
        <div class="form-group mb-3 position-relative">
          <label for="password">Password</label>
          <div class="input-group">
            <input
              :type="passwordType"
              v-model="password"
              class="form-control"
              id="password"
              placeholder=""
              required
            />
            <span
              class="input-group-text bg-white"
              @click="togglePasswordVisibility"
              style="cursor: pointer"
            >
              <i :class="showPassword ? 'bi bi-eye' : 'bi bi-eye-slash'"></i>
            </span>
          </div>
        </div>

        <!-- Display Error Message if Exists -->
        <div v-if="errorMessage" class="alert alert-danger mt-3">
          {{ errorMessage }}
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <div class="mt-3 text-center">
          <p>
            Don't have an account?
            <RouterLink to="/register" class="btn btn-link"
              >Register</RouterLink
            >
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
/* CSS khusus jika diperlukan */
</style>
