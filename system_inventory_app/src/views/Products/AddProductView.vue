<template lang="">
  <div class="p-3 mt-4 card">
    <h3>Add Data Product</h3>

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

    <form @submit.prevent="addProduct()">
      <div class="form-group">
        <label for="name">Product Name</label>
        <input
          type="text"
          class="form-control"
          id="name"
          v-model="product.name"
          placeholder=""
          required
        />
      </div>
      <button type="submit" class="btn btn-primary mt-3">Simpan</button>
      <RouterLink to="/product" class="btn btn-secondary mt-3 ms-2"
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
      product: {
        name: "",
      },
      message: null, // Untuk menampilkan pesan sukses/error
    };
  },
  methods: {
    addProduct() {
      // Reset pesan notifikasi
      this.message = null;

      // Pastikan nama produk tidak kosong
      if (!this.product.name) {
        this.message = {
          text: "Nama produk harus diisi!",
          type: "alert-danger", // Warna merah untuk error
        };
        return;
      }

      // Mengirim data produk ke API
      axios
        .post(
          "http://localhost/system_inventory/public/api/products",
          this.product,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`, // Ambil token dari localStorage
              "Content-Type": "application/json", // Pastikan content type JSON
            },
          }
        )
        .then((response) => {
          console.log("Produk berhasil ditambahkan:", response.data);
          this.message = {
            text: "Produk berhasil ditambahkan!",
            type: "alert-success", // Warna hijau untuk sukses
          };
          this.product.name = ""; // Reset form setelah berhasil
        })
        .catch((error) => {
          console.error("Terjadi kesalahan:", error);
          this.message = {
            text: "Terjadi kesalahan saat menyimpan produk.",
            type: "alert-danger", // Warna merah untuk error
          };
        });
    },
  },
};
</script>

<style lang=""></style>
