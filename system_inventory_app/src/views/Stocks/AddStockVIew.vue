<template lang="">
  <div class="p-3 mt-4 card">
    <h3>Add Data Stock</h3>

    <!-- Pesan Sukses atau Error -->
    <div
      v-if="message"
      :class="message.type"
      class="alert alert-dismissible fade show"
      role="alert"
    >
      {{ message.text }}
      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close"
        @click="message = null"
      ></button>
    </div>

    <form @submit.prevent="addData()">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="warehouse_id">Warehouse</label>
            <select class="form-control" v-model="stock.warehouse_id" required>
              <option
                v-for="warehouse in warehouses"
                :key="warehouse.warehouse_id"
                :value="warehouse.warehouse_id"
              >
                {{ warehouse.name }}
              </option>
            </select>
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="product_id">Product</label>
            <select class="form-control" v-model="stock.product_id" required>
              <option
                v-for="product in products"
                :key="product.product_id"
                :value="product.product_id"
              >
                {{ product.name }}
              </option>
            </select>
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="">QTY</label>
            <input
              type="number"
              min="1"
              class="form-control"
              v-model="stock.qty"
              required
            />
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-3">Simpan</button>
      <RouterLink to="/stock" class="btn btn-secondary mt-3 ms-2"
        >Back</RouterLink
      >
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      stock: {
        name: "",
        warehouse_id: "",
        product_id: "",
        qty: "",
      },
      warehouses: [],
      products: [],
      message: null, // Untuk menampilkan pesan sukses/error
    };
  },
  mounted() {
    this.fetchWarehouses();
    this.fetchProducts();
  },
  methods: {
    fetchWarehouses() {
      axios
        .get("http://localhost/system_inventory/public/api/warehouses", {
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
        })
        .then((response) => {
          this.warehouses = response.data.data;
        });
    },
    fetchProducts() {
      axios
        .get("http://localhost/system_inventory/public/api/products", {
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
        })
        .then((response) => {
          this.products = response.data.data;
        });
    },

    addData() {
      this.message = null;
      // Mengirim data ke API
      axios
        .post(
          "http://localhost/system_inventory/public/api/stock",
          this.stock,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`, // Ambil token dari localStorage
              "Content-Type": "application/json", // Pastikan content type JSON
            },
          }
        )
        .then((response) => {
          console.log("Data berhasil ditambahkan:", response.data);
          this.message = {
            text: "Data berhasil ditambahkan!",
            type: "alert-success", // Warna hijau untuk sukses
          };
          this.stock.warehouse_id = ""; // Reset form setelah berhasil
          this.stock.product_id = ""; // Reset form setelah berhasil
          this.stock.qty = ""; // Reset form setelah berhasil
        })
        .catch((error) => {
          console.error("Terjadi kesalahan:", error);
          // Menampilkan detail error
          if (error.response) {
            console.error("Error response:", error.response.data);
            console.error("Error status:", error.response.status);
            this.message = {
              text: `Terjadi kesalahan: ${
                error.response.data.message || "Unknown error"
              }`,
              type: "alert-danger", // Warna merah untuk error
            };
          } else if (error.request) {
            console.error("Error request:", error.request);
            this.message = {
              text: "Permintaan tidak sampai ke server.",
              type: "alert-danger",
            };
          } else {
            console.error("General error:", error.message);
            this.message = {
              text: "Kesalahan tidak terduga.",
              type: "alert-danger",
            };
          }
        });
    },
  },
};
</script>

<style lang=""></style>
