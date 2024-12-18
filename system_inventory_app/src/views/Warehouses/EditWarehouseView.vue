<template>
  <div class="p-3 mt-4 card">
    <h3>Edit Data Warehouse</h3>

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

    <form @submit.prevent="editData">
      <div class="form-group">
        <label for="name">Name Warehouse</label>
        <input
          type="text"
          class="form-control"
          id="name"
          v-model="warehouse.name"
          placeholder=""
          required
        />
      </div>
      <button type="submit" class="btn btn-primary mt-3">Simpan</button>
      <router-link to="/warehouse" class="btn btn-secondary mt-3 ms-2"
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
      warehouse: {
        name: "", // Properti untuk menyimpan data produk
      },
      message: null, // Untuk menampilkan pesan sukses atau error
    };
  },
  methods: {
    // Fungsi untuk mengupdate produk
    editData() {
      this.message = null;
      // Mengirim data produk ke API untuk diperbarui
      axios
        .put(
          `http://localhost/system_inventory/public/api/warehouses/${this.$route.params.warehouse_id}`,
          this.warehouse,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`, // Ambil token dari localStorage
              "Content-Type": "application/json", // Pastikan content type JSON
            },
          }
        )
        .then((response) => {
          console.log("Data berhasil diupdate:", response.data);
          this.message = {
            text: "Data berhasil diupdate!",
            type: "alert-success", // Pesan sukses
          };
        })
        .catch((error) => {
          console.error("Terjadi kesalahan:", error);
          this.message = {
            text: "Terjadi kesalahan saat mengupdate data.",
            type: "alert-danger", // Pesan error
          };
        });
    },
  },
  mounted() {
    // Mengambil data produk berdasarkan warehouse_id yang ada di URL
    const warehouseId = this.$route.params.warehouse_id; // Ambil warehouse_id dari URL
    if (warehouseId) {
      axios
        .get(
          `http://localhost/system_inventory/public/api/warehouses/${warehouseId}`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        )
        .then((response) => {
          this.warehouse = response.data.data;
        })
        .catch((error) => {
          console.error("Terjadi kesalahan:", error);
        });
    } else {
      console.error("warehouse ID tidak ditemukan!");
    }
  },
};
</script>

<style scoped></style>
