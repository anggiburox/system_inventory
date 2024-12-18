<template>
  <div class="p-3 mt-4 card">
    <h3>Edit Data Product</h3>

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

    <form @submit.prevent="editProduct">
      <div class="form-group">
        <label for="name">Name Product</label>
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
      <router-link to="/product" class="btn btn-secondary mt-3 ms-2"
        >Back</router-link
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
        name: "", // Properti untuk menyimpan data produk
      },
      message: null, // Untuk menampilkan pesan sukses atau error
    };
  },
  methods: {
    // Fungsi untuk mengupdate produk
    editProduct() {
      this.message = null;

      if (!this.product.name) {
        this.message = {
          text: "Nama produk harus diisi!",
          type: "alert-danger", // Pesan error
        };
        return;
      }

      // Mengirim data produk ke API untuk diperbarui
      axios
        .put(
          `http://localhost/system_inventory/public/api/products/${this.$route.params.product_id}`,
          this.product,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`, // Ambil token dari localStorage
              "Content-Type": "application/json", // Pastikan content type JSON
            },
          }
        )
        .then((response) => {
          console.log("Produk berhasil diupdate:", response.data);
          this.message = {
            text: "Produk berhasil diupdate!",
            type: "alert-success", // Pesan sukses
          };
        })
        .catch((error) => {
          console.error("Terjadi kesalahan:", error);
          this.message = {
            text: "Terjadi kesalahan saat mengupdate produk.",
            type: "alert-danger", // Pesan error
          };
        });
    },
  },
  mounted() {
    // Mengambil data produk berdasarkan product_id yang ada di URL
    const productId = this.$route.params.product_id; // Ambil product_id dari URL
    if (productId) {
      axios
        .get(
          `http://localhost/system_inventory/public/api/products/${productId}`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        )
        .then((response) => {
          this.product = response.data.data;
        })
        .catch((error) => {
          console.error("Terjadi kesalahan:", error);
        });
    } else {
      console.error("Product ID tidak ditemukan!");
    }
  },
};
</script>

<style scoped></style>
