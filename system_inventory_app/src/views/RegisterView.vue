<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

// Reactive state untuk toggle password visibility
const passwordType = ref("password");
const showPassword = ref(false);
const errorMessage = ref("");
const successMessage = ref("");

// Reactive states untuk form input
const email = ref("");
const name = ref("");
const password = ref("");
const router = useRouter();

// Fungsi toggle untuk mengubah tipe input
const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
  passwordType.value = showPassword.value ? "text" : "password";
};

// Fungsi untuk menangani registrasi
const register = async () => {
  try {
    // Mengirim request POST ke backend untuk registrasi
    const response = await axios.post(
      "http://localhost/system_inventory/public/api/auth/register",
      {
        email: email.value,
        name: name.value,
        password: password.value,
      }
    );

    // Setelah registrasi sukses, set success message
    successMessage.value = "Data berhasil disimpan. Silakan login.";
    errorMessage.value = ""; // Reset error message jika ada

    // Arahkan pengguna ke halaman login setelah registrasi sukses
    // setTimeout(() => {
    //   router.push({ name: "login" }); // Redirect ke halaman login
    // });
  } catch (error) {
    // Menangani error dari backend jika email sudah terdaftar
    if (error.response && error.response.status === 422) {
      const errors = error.response.data.errors;
      if (errors.email) {
        // Menampilkan pesan error khusus jika email sudah terdaftar
        errorMessage.value =
          "Email sudah terdaftar. Silakan gunakan email lain.";
      } else {
        errorMessage.value = "Terjadi kesalahan. Silakan coba lagi.";
      }
    } else {
      errorMessage.value = "Terjadi kesalahan. Silakan coba lagi.";
    }
  }
};
</script>

<template>
  <div
    class="container d-flex justify-content-center align-items-center vh-100"
  >
    <div class="card p-4" style="width: 400px">
      <h3 class="text-center mb-4">Register your account</h3>
      <form @submit.prevent="register">
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

        <!-- Name Input -->
        <div class="form-group mb-3">
          <label for="name">Name</label>
          <input
            type="text"
            v-model="name"
            class="form-control"
            id="name"
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

        <!-- Display Success Message if Registration Successful -->
        <div v-if="successMessage" class="alert alert-success mt-3">
          {{ successMessage }}
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100">Submit</button>
        <div class="mt-3 text-center">
          <p>
            Already have an account?
            <RouterLink to="/login" class="btn btn-link">Login</RouterLink>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped></style>
