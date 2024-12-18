<template>
  <div class="p-3 mt-4 card">
    <h1>Warehouse Stock</h1>
    <div class="row mb-3">
      <div class="col-md-4">
        <!-- Dropdown untuk memilih warehouse -->
        <select
          v-model="selectedWarehouse"
          @change="fetchStockByWarehouse"
          class="form-select"
        >
          <option value="" disabled>Select a warehouse</option>
          <option
            v-for="warehouse in warehouses"
            :key="warehouse.warehouse_id"
            :value="warehouse.warehouse_id"
          >
            {{ warehouse.name }}
          </option>
        </select>
      </div>
      <div class="col-md-4">
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
      <div class="col-md-4 text-end">
        <router-link to="/addstock" class="btn btn-primary"
          >Add Data</router-link
        >
      </div>
    </div>

    <!-- Tabel untuk menampilkan stok -->
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Warehouse Name</th>
              <th>Product Name</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
            <!-- Menampilkan stok untuk semua warehouse jika belum ada yang dipilih -->
            <tr v-for="(stock, index) in stocks" :key="stock.product_id">
              <td>{{ index + 1 }}</td>
              <td>{{ stock.warehouse_name }}</td>
              <td>{{ stock.product_name }}</td>
              <!-- <td v-if="stock.product">{{ stock.product.name }}</td>
              <td v-else>{{ stock.product_name }}</td> -->
              <td>{{ stock.qty }}</td>
            </tr>
            <tr v-if="stocks.length === 0">
              <td colspan="3" class="text-center">
                No stock available for this warehouse.
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination UI -->
        <div class="d-flex justify-content-center">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <!-- First page -->
              <li
                class="page-item"
                :class="{ disabled: pagination.current_page === 1 }"
              >
                <button
                  class="page-link"
                  @click="fetchData(1)"
                  :disabled="pagination.current_page === 1"
                >
                  First
                </button>
              </li>

              <!-- Previous page -->
              <li
                class="page-item"
                :class="{ disabled: pagination.current_page === 1 }"
              >
                <button
                  class="page-link"
                  @click="fetchData(pagination.current_page - 1)"
                  :disabled="pagination.current_page === 1"
                >
                  Previous
                </button>
              </li>

              <!-- Next page -->
              <li
                class="page-item"
                :class="{
                  disabled: pagination.current_page === pagination.last_page,
                }"
              >
                <button
                  class="page-link"
                  @click="fetchData(pagination.current_page + 1)"
                  :disabled="pagination.current_page === pagination.last_page"
                >
                  Next
                </button>
              </li>

              <!-- Last page -->
              <li
                class="page-item"
                :class="{
                  disabled: pagination.current_page === pagination.last_page,
                }"
              >
                <button
                  class="page-link"
                  @click="fetchData(pagination.last_page)"
                  :disabled="pagination.current_page === pagination.last_page"
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
</template>

<script>
import axios from "axios";

export default {
  name: "Stock",
  data() {
    return {
      warehouses: [], // Menyimpan daftar warehouse
      selectedWarehouse: "", // Warehouse yang dipilih
      stocks: [], // Menyimpan stok berdasarkan warehouse
      search: "", // Menyimpan query pencarian
      pagination: {
        // Menyimpan informasi pagination
        total: 0,
        current_page: 1,
        last_page: 1,
      },
    };
  },
  mounted() {
    this.fetchWarehouses(); // Ambil daftar warehouse saat komponen dimuat
    // this.fetchAllStocks();  // Ambil stok semua warehouse saat halaman pertama kali dimuat
    this.fetchData(); // Ambil produk saat komponen dimuat
  },
  methods: {
    // Fungsi untuk mengambil data produk dari API
    fetchData(page = 1) {
      axios
        .get(
          `http://localhost/system_inventory/public/api/stockgetAll?page=${page}&search=${this.search}`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`, // Pastikan token di header
            },
          }
        )
        .then((response) => {
          console.log(response.data);
          this.stocks = response.data.data; // Menyimpan data produk
          this.pagination = response.data.pagination; // Menyimpan informasi pagination
        })
        .catch((error) => {
          console.error("Terjadi kesalahan:", error);
          // Alert atau handling error jika perlu
        });
    },

    searchData() {
      console.log("Searching for:", this.search);
      this.fetchData();
    },
    // Mengambil daftar warehouse
    fetchWarehouses() {
      axios
        .get(`http://localhost/system_inventory/public/api/warehousesvue`, {
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
        })
        .then((response) => {
          this.warehouses = response.data[0]; // Mengambil array warehouse dari array pertama
          console.log("Warehouse Data:", this.warehouses); // Periksa data yang diterima
        })
        .catch((error) => {
          console.error("Error fetching warehouses:", error);
          alert("Gagal memuat daftar warehouse.");
        });
    },

    // Mengambil stok untuk semua warehouse saat halaman dimuat
    // fetchAllStocks() {
    //   axios
    //     .get(`http://localhost/system_inventory/public/api/stockgetAll`, {
    //       headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    //     })
    //     .then(response => {
    //       this.stocks = response.data;
    //       console.log("Stocks:", this.stocks);
    //     })
    //     .catch(error => {
    //       console.error("Error fetching all stocks:", error);
    //       this.stocks = []; // Kosongkan stok jika error
    //       alert("Gagal memuat semua stok.");
    //     });
    // },

    // Mengambil stok berdasarkan warehouse yang dipilih
    fetchStockByWarehouse() {
      if (!this.selectedWarehouse) {
        this.fetchData(); // Jika tidak ada warehouse yang dipilih, tampilkan semua stok
        return;
      }

      axios
        .get(
          `http://localhost/system_inventory/public/api/stocks?warehouse_id=${this.selectedWarehouse}`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        )
        .then((response) => {
          this.stocks = response.data;
        })
        .catch((error) => {
          console.error("Error fetching stock:", error);
          this.stocks = []; // Kosongkan stok jika error
          alert("Gagal memuat stok untuk warehouse ini.");
        });
    },
    // Fungsi pencarian produk otomatis saat mengetik
  },
};
</script>

<style scoped></style>
