<template>
  <div class="p-3">
    <div class="row">
      <!-- Card 1: Product -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div
            class="card-header text-white"
            style="background: linear-gradient(135deg, #007bff, #66b2ff)"
          >
            Jumlah Products
          </div>
          <div class="card-body">
            <h5 class="card-title">{{ productCount }}</h5>
          </div>
        </div>
      </div>

      <!-- Card 2: Warehouse -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div
            class="card-header text-white"
            style="background: linear-gradient(135deg, #28a745, #90ee90)"
          >
            Jumlah Warehouses
          </div>
          <div class="card-body">
            <h5 class="card-title">{{ warehouseCount }}</h5>
          </div>
        </div>
      </div>

      <!-- Card 3: Stock -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div
            class="card-header text-white"
            style="background: linear-gradient(135deg, #ffc107, #ffd966)"
          >
            Jumlah Stock
          </div>
          <div class="card-body">
            <h5 class="card-title">{{ stockCount }}</h5>
          </div>
        </div>
      </div>

      <!-- Card 4: Transaksi -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div
            class="card-header text-white"
            style="background: linear-gradient(135deg, #dc3545, #ff7f7f)"
          >
            Jumlah Transaksi
          </div>
          <div class="card-body">
            <h5 class="card-title">{{ TransaksiCount }}</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Home",
  computed: {
    productCount() {
      return this.$store.state.productCount;
    },
    warehouseCount() {
      return this.$store.state.warehouseCount;
    },
    stockCount() {
      return this.$store.state.stockCount;
    },
    TransaksiCount() {
      return this.$store.state.TransaksiCount;
    },
  },
  mounted() {
    this.userName = localStorage.getItem("name");
    if (!this.userName) {
      this.$router.push({ name: "login" });
    } else {
      this.$store.dispatch("fetchDashboardData");
    }
  },
  // data() {
  //   return {
  //     userName: "",
  //     warehouseCount: "",
  //     productCount: "",
  //     stockCount: "",
  //     TransaksiCount: "",
  //   };
  // },
  // mounted() {
  //   this.userName = localStorage.getItem("name");
  //   if (!this.userName) {
  //     this.$router.push({ name: "login" });
  //   } else {
  //     // Mengambil token dari localStorage
  //     const token = localStorage.getItem("token"); // Pastikan token disimpan setelah login

  //     if (token) {
  //       // Ambil data dari API
  //       axios
  //         .get(`http://localhost/system_inventory/public/api/dashboard-data`, {
  //           headers: {
  //             Authorization: `Bearer ${localStorage.getItem("token")}`, // Pastikan token di header
  //           },
  //         })
  //         .then((response) => {
  //           this.productCount = response.data.productCount;
  //           this.warehouseCount = response.data.warehouseCount;
  //           this.stockCount = response.data.stockCount;
  //           this.TransaksiCount = response.data.TransaksiCount;
  //         })
  //         .catch((error) => {
  //           console.error("There was an error fetching the data: ", error);
  //         });
  //     } else {
  //       console.error("Token tidak ditemukan");
  //     }
  //   }
  // },
};
</script>
