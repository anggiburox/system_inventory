import { createStore } from "vuex"; // Pastikan Vuex diimpor dari package
import axios from "axios";

const store = createStore({
  state() {
    return {
      email: "",
      password: "",
      name: "",
      errorMessage: "",
      successMessage: "",
      userName: "",
      warehouseCount: "",
      productCount: "",
      stockCount: "",
      TransaksiCount: "",
    };
  },
  mutations: {
    setEmail(state, email) {
      state.email = email;
    },
    setPassword(state, password) {
      state.password = password;
    },
    setName(state, name) {
      state.name = name;
    },
    setErrorMessage(state, message) {
      state.errorMessage = message;
    },
    setSuccessMessage(state, message) {
      state.successMessage = message;
    },
    resetErrorMessage(state) {
      state.errorMessage = "";
    },
    resetState(state) {
      state.email = "";
      state.password = "";
      state.name = "";
      state.errorMessage = "";
      state.successMessage = "";
      state.warehouseCount = "";
      state.productCount = "";
      state.stockCount = "";
      state.TransaksiCount = "";
    },
    setDashboardData(state, data) {
      state.productCount = data.productCount;
      state.warehouseCount = data.warehouseCount;
      state.stockCount = data.stockCount;
      state.TransaksiCount = data.TransaksiCount;
    },
  },
  actions: {
    async login({ commit, state }) {
      commit("resetErrorMessage");
      try {
        const response = await axios.post(
          "http://localhost/system_inventory/public/api/auth/login",
          {
            email: state.email,
            password: state.password,
          }
        );
        const { email, name, token, user_id } = response.data.data;
        localStorage.setItem("email", email);
        localStorage.setItem("name", name);
        localStorage.setItem("token", token);
        localStorage.setItem("user_id", user_id);
        router.push({ name: "home" });
      } catch (error) {
        commit(
          "setErrorMessage",
          error.response?.data?.message || "Error occurred"
        );
      }
    },
    logout({ commit }) {
      localStorage.clear();
      commit("resetState");
    },
    fetchDashboardData({ commit }) {
      const token = localStorage.getItem("token");
      if (token) {
        axios
          .get("http://localhost/system_inventory/public/api/dashboard-data", {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          })
          .then((response) => {
            commit("setDashboardData", response.data);
          })
          .catch((error) => {
            console.error("Error fetching data: ", error);
          });
      }
    },
  },
});

export default store;
