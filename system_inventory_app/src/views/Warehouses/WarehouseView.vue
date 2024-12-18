<template>
  <div class="p-3 mt-4 card">
    <h1>Warehouses</h1>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                <!-- Ganti @submit.prevent dengan @input untuk pencarian otomatis -->
                <input
                  v-model="search"
                  @input="searchData"
                  class="form-control me-2"
                  type="search"
                  placeholder="Search"
                  aria-label="Search"
                />
              </div>
              <div class="col-md-6 text-end">
                <router-link to="/addwarehouse" class="btn btn-primary"
                  >Add Data</router-link
                >
              </div>
            </div>
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama Warehouse</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <!-- Menampilkan data -->
                <tr
                  v-for="(warehouse, index) in warehouses"
                  :key="warehouse.warehouse_id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ warehouse.name }}</td>
                  <td>
                    <button
                      class="btn btn-warning btn-sm me-2"
                      @click="editData(warehouse.warehouse_id)"
                    >
                      Edit
                    </button>
                    <button
                      class="btn btn-danger btn-sm me-2"
                      @click="deleteData(warehouse.warehouse_id)"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Pagination UI -->
            <div class="d-flex justify-content-center">
              <nav aria-label="Page navigation example">
                <ul class="pagination">
                  <li
                    class="page-item"
                    :class="{ disabled: pagination.current_page == 1 }"
                  >
                    <button
                      class="page-link"
                      @click="fetchWarehouses(1)"
                      :disabled="pagination.current_page == 1"
                    >
                      First
                    </button>
                  </li>
                  <li
                    class="page-item"
                    :class="{ disabled: pagination.current_page == 1 }"
                  >
                    <button
                      class="page-link"
                      @click="fetchWarehouses(pagination.current_page - 1)"
                      :disabled="pagination.current_page == 1"
                    >
                      Previous
                    </button>
                  </li>
                  <li
                    class="page-item"
                    :class="{
                      disabled: pagination.current_page == pagination.last_page,
                    }"
                  >
                    <button
                      class="page-link"
                      @click="fetchWarehouses(pagination.current_page + 1)"
                      :disabled="
                        pagination.current_page == pagination.last_page
                      "
                    >
                      Next
                    </button>
                  </li>
                  <li
                    class="page-item"
                    :class="{
                      disabled: pagination.current_page == pagination.last_page,
                    }"
                  >
                    <button
                      class="page-link"
                      @click="fetchWarehouses(pagination.last_page)"
                      :disabled="
                        pagination.current_page == pagination.last_page
                      "
                    >
                      Last
                    </button>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Warehouse",
  data() {
    return {
      warehouses: [],
      search: "",
      pagination: {
        total: 0,
        current_page: 1,
        last_page: 1,
      },
    };
  },
  mounted() {
    this.fetchWarehouses(); // Ambil warehouse saat komponen dimuat
  },
  methods: {
    // Fungsi untuk mengambil data warehouse dari API
    fetchWarehouses(page = 1) {
      axios
        .get(
          `http://localhost/system_inventory/public/api/warehouses?page=${page}&search=${this.search}`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`, // Pastikan token di header
            },
          }
        )
        .then((response) => {
          this.warehouses = response.data.data; // Menyimpan data
          this.pagination = response.data.pagination; // Menyimpan informasi pagination
        })
        .catch((error) => {
          // console.error('Terjadi kesalahan:', error);
          // alert("Gagal memuat data.");
        });
    },

    // Fungsi pencarian  otomatis saat mengetik
    searchData() {
      console.log("Searching for:", this.search);
      this.fetchWarehouses();
    },

    // Fungsi untuk mengedit
    editData(warehouseId) {
      // Logic untuk edit
      this.$router.push({
        name: "editwarehouse",
        params: { warehouse_id: warehouseId },
      });
    },

    // Fungsi untuk menghapus
    deleteData(warehouseId) {
      if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        axios
          .delete(
            `http://localhost/system_inventory/public/api/warehouses/${warehouseId}`,
            {
              headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`, // Pastikan token di header
              },
            }
          )
          .then((response) => {
            alert("Data berhasil dihapus.");
            this.fetchWarehouses(); // Segarkan daftar  setelah dihapus
          })
          .catch((error) => {
            console.error("Terjadi kesalahan:", error);
            alert("Gagal menghapus Data.");
          });
      }
    },
  },

  //paginate
  fetchWarehouses(page = 1) {
    axios
      .get(
        `http://localhost/system_inventory/public/api/warehouses?page=${page}&search=${this.search}`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        }
      )
      .then((response) => {
        this.warehouses = response.data.data; // Menyimpan data
        this.pagination = response.data.pagination; // Menyimpan informasi pagination
      })
      .catch((error) => {
        console.error("Terjadi kesalahan:", error);
        alert("Gagal memuat data.");
      });
  },
};
</script>

<style scoped></style>
