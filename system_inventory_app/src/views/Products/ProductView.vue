<template>
  <div class="p-3 mt-4 card">
    <h1>Products</h1>
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
                <router-link to="/addproduct" class="btn btn-primary"
                  >Tambah Data</router-link
                >
              </div>
            </div>
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama Produk</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <!-- Menampilkan data produk -->
                <tr
                  v-for="(product, index) in products"
                  :key="product.product_id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ product.name }}</td>
                  <td>
                    <button
                      class="btn btn-warning btn-sm me-2"
                      @click="editProducts(product.product_id)"
                    >
                      Edit
                    </button>
                    <button
                      class="btn btn-danger btn-sm me-2"
                      @click="deleteProduct(product.product_id)"
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
                      @click="fetchProducts(1)"
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
                      @click="fetchProducts(pagination.current_page - 1)"
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
                      @click="fetchProducts(pagination.current_page + 1)"
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
                      @click="fetchProducts(pagination.last_page)"
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
  name: "Product",
  data() {
    return {
      products: [], // Menyimpan data produk
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
    this.fetchProducts(); // Ambil produk saat komponen dimuat
  },
  methods: {
    // Fungsi untuk mengambil data produk dari API
    fetchProducts(page = 1) {
      axios
        .get(
          `http://localhost/system_inventory/public/api/products?page=${page}&search=${this.search}`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`, // Pastikan token di header
            },
          }
        )
        .then((response) => {
          this.products = response.data.data; // Menyimpan data produk
          this.pagination = response.data.pagination; // Menyimpan informasi pagination
        })
        .catch((error) => {
          // console.error('Terjadi kesalahan:', error);
          // alert("Gagal memuat data produk.");
        });
    },

    // Fungsi pencarian produk otomatis saat mengetik
    searchData() {
      console.log("Searching for:", this.search);
      this.fetchProducts();
    },

    // Fungsi untuk mengedit produk
    editProducts(productId) {
      // Logic untuk edit produk
      this.$router.push({
        name: "editproduct",
        params: { product_id: productId },
      });
    },

    // Fungsi untuk menghapus produk
    deleteProduct(productId) {
      if (confirm("Apakah Anda yakin ingin menghapus produk ini?")) {
        axios
          .delete(
            `http://localhost/system_inventory/public/api/products/${productId}`,
            {
              headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`, // Pastikan token di header
              },
            }
          )
          .then((response) => {
            alert("Produk berhasil dihapus.");
            this.fetchProducts(); // Segarkan daftar produk setelah dihapus
          })
          .catch((error) => {
            console.error("Terjadi kesalahan:", error);
            alert("Gagal menghapus produk.");
          });
      }
    },
  },
};
</script>

<style scoped>
/* Gaya khusus untuk komponen Produk */
</style>
