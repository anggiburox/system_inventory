<template>
  <div class="p-3 mt-4 card">
    <h1>Transaction</h1>
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
                <router-link to="/addtransaction" class="btn btn-primary"
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
                  <th scope="col">ID Transaction</th>
                  <th scope="col">Transaction Type</th>
                  <th scope="col">Name Warehouse</th>
                  <th scope="col">Name Product</th>
                  <th scope="col">QTY Transaction</th>
                  <th scope="col">User</th>
                  <th scope="col">Waktu Transaction</th>
                </tr>
              </thead>
              <tbody>
                <!-- Menampilkan data produk -->
                <tr
                  v-for="(transaction, index) in transactions"
                  :key="transaction.transaction_id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ transaction.transaction_id }}</td>
                  <td
                    :class="{
                      'text-success': transaction.transaction_type === 'in',
                      'text-danger': transaction.transaction_type === 'out',
                    }"
                  >
                    {{ transaction.transaction_type }}
                  </td>
                  <td>{{ transaction.warehouse_name }}</td>
                  <td>{{ transaction.product_name }}</td>
                  <td>{{ transaction.qty_transactions }}</td>
                  <td>{{ transaction.user_name }}</td>
                  <td>{{ transaction.created_at }}</td>
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
                      @click="fetchData(1)"
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
                      @click="fetchData(pagination.current_page - 1)"
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
                      @click="fetchData(pagination.current_page + 1)"
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
                      @click="fetchData(pagination.last_page)"
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
  name: "Transaction",
  data() {
    return {
      transactions: [], // Menyimpan data
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
    this.fetchData(); // Ambil saat komponen dimuat
  },
  methods: {
    // Fungsi untuk mengambil data dari API
    fetchData(page = 1) {
      axios
        .get(
          `http://localhost/system_inventory/public/api/transactions?page=${page}&search=${this.search}`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`, // Pastikan token di header
            },
          }
        )
        .then((response) => {
          this.transactions = response.data.data; // Menyimpan data
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
      this.fetchData();
    },
  },

  //paginate
  fetchData(page = 1) {
    axios
      .get(
        `http://localhost/system_inventory/public/api/transactions?page=${page}&search=${this.search}`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        }
      )
      .then((response) => {
        this.transactions = response.data.data; // Menyimpan data
        this.pagination = response.data.pagination; // Menyimpan informasi pagination
      })
      .catch((error) => {
        console.error("Terjadi kesalahan:", error);
        alert("Gagal memuat data.");
      });
  },
};
</script>

<style scoped>
/* Gaya khusus untuk komponen Produk */
</style>
