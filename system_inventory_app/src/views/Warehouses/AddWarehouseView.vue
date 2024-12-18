<template lang="">
  <div class="p-3 mt-4 card">
    <h3>Add Data Warehouse</h3>

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
      <div class="form-group">
        <label for="name">Warehouse Name</label>
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
      <RouterLink to="/warehouse" class="btn btn-secondary mt-3 ms-2"
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
      warehouse: {
        name: "",
      },
      message: null, // Untuk menampilkan pesan
    };
  },
  methods: {
    addData() {
      this.message = null;
      axios
        .post(
          "http://localhost/system_inventory/public/api/warehouses",
          this.warehouse,
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
          this.warehouse.name = ""; // Reset form setelah berhasil
        })
        .catch((error) => {
          console.error("Terjadi kesalahan:", error);
          this.message = {
            text: "Terjadi kesalahan saat menyimpan data.",
            type: "alert-danger", // Warna merah untuk error
          };
        });
    },
  },
};
</script>

<style lang=""></style>
