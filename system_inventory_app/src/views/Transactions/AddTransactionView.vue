<template>
  <div class="p-3 mt-4 card">
    <h3>Add Data Transaction</h3>

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

    <form @submit.prevent="addData">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="transaction_id">ID Transaksi</label>
            <input
              type="text"
              class="form-control"
              v-model="transaction.transaction_id"
              disabled
            />
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="transaction_type">Jenis Transaksi</label>
            <select
              class="form-control"
              v-model="transaction.transaction_type"
              required
            >
              <option value="in">In</option>
              <option value="out">Out</option>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- <div class="col">
            <div class="form-group">
              <label for="warehouse_id">Warehouse</label>
                <select class="form-control" v-model="transaction.warehouse_id" required>
                    <option v-for="warehouse in warehouses" :key="warehouse.warehouse_id" :value="warehouse.warehouse_id">
                        {{ warehouse.name }}
                    </option>
                </select>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="product_id">Product</label>
              <select class="form-control" v-model="transaction.product_id" required>
                <option v-for="product in products" :key="product.product_id" :value="product.product_id">{{ product.name }}</option>
              </select>
            </div>
          </div> -->

        <div class="col">
          <div class="form-group">
            <label for="warehouse_id">Warehouse</label>
            <select
              class="form-control"
              v-model="transaction.warehouse_id"
              @change="fetchProductsByWarehouse"
              required
            >
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

        <!-- Dropdown Product -->
        <div class="col">
          <div class="form-group">
            <label for="product_id">Product</label>
            <select
              class="form-control"
              v-model="transaction.product_id"
              required
            >
              <option
                v-for="product in products"
                :key="product.product_id"
                :value="product.product_id"
              >
                {{ product.product_name }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="user_name">User Name</label>
            <!-- <input type="text" class="form-control" :value="userID" disabled> -->
            <input
              type="text"
              class="form-control"
              :value="userName"
              disabled
            />
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="qty_transactions">QTY Transaction</label>
            <input
              type="number"
              min="1"
              class="form-control"
              v-model="transaction.qty_transactions"
              required
            />
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-3">Simpan</button>
      <RouterLink to="/transaction" class="btn btn-secondary mt-3 ms-2"
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
      transaction: {
        transaction_id: "", // ID transaksi otomatis dari backend
        warehouse_id: "",
        product_id: "",
        qty_transactions: 1,
        transaction_type: "in",
        user_id: "",
      },
      warehouses: [],
      products: [],
      userName: "",
      userID: "",
      message: null,
    };
  },
  mounted() {
    this.fetchTransactionId(); // Panggil ID otomatis saat komponen dimuat
    this.fetchWarehouses();
    //   this.fetchProducts();
    this.getUserName();
    this.getUserUserID();
  },
  methods: {
    fetchTransactionId() {
      console.log("Mengambil ID transaksi...");
      axios
        .get(
          "http://localhost/system_inventory/public/api/transactions/latest",
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        )
        .then((response) => {
          console.log("Response data:", response.data);
          this.transaction.transaction_id = response.data.data.transaction_id;
        })
        .catch((error) => {
          console.error(
            "Error fetching transaction ID:",
            error.response || error.message
          );
          this.message = {
            text: "Gagal mengambil ID transaksi.",
            type: "alert-danger",
          };
        });
    },

    //   fetchWarehouses() {
    //     axios
    //       .get('http://localhost/system_inventory/public/api/warehouses', {
    //         headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    //       })
    //       .then((response) => {
    //         this.warehouses = response.data.data;
    //       });
    //   },

    fetchWarehouses() {
      // Ambil data berdasarkan stock ID
      axios
        .get(
          `http://localhost/system_inventory/public/api/warehouses-by-stock`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        )
        .then((response) => {
          this.warehouses = response.data;
        })
        .catch((error) => {
          console.error("Error fetching warehouses:", error);
        });
    },

    // Ambil produk berdasarkan warehouse_id
    fetchProductsByWarehouse() {
      this.products = []; // Reset data produk sebelumnya
      if (this.transaction.warehouse_id) {
        axios
          .get(
            "http://localhost/system_inventory/public/api/products-by-warehouse",
            {
              params: {
                warehouse_id: this.transaction.warehouse_id, // Kirim parameter warehouse_id
              },
              headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
              },
            }
          )
          .then((response) => {
            this.products = response.data; // Update data produk
            console.log("Produk:", this.products); // Debugging: log data produk
          })
          .catch((error) => {
            console.error("Error fetching products:", error);
          });
      }
    },

    //   fetchProducts() {
    //     axios
    //       .get('http://localhost/system_inventory/public/api/products', {
    //         headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    //       })
    //       .then((response) => {
    //         this.products = response.data.data;
    //       });
    //   },
    getUserName() {
      this.userName = localStorage.getItem("name") || "";
    },
    getUserUserID() {
      this.userID = localStorage.getItem("user_id") || "";
    },
    addData() {
      this.message = null;
      axios
        .post(
          "http://localhost/system_inventory/public/api/transactions",
          this.transaction,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        )
        .then(() => {
          this.message = {
            text: "Transaksi berhasil ditambahkan.",
            type: "alert-success",
          };
          this.fetchTransactionId(); // Ambil ID baru setelah transaksi berhasil
          this.resetForm();
        })
        .catch((error) => {
          console.error(
            "Error posting transaction data:",
            error.response || error.message
          );
          if (error.response && error.response.data) {
            // Tampilkan pesan error dari server jika ada
            this.message = {
              text:
                error.response.data.message ||
                "Gagal menyimpan data transaksi.",
              type: "alert-danger",
            };
          } else {
            // Tampilkan pesan error umum
            this.message = {
              text: "Gagal menyimpan data transaksi.",
              type: "alert-danger",
            };
          }
        });
    },
    resetForm() {
      this.transaction = {
        transaction_id: "",
        warehouse_id: "",
        product_id: "",
        qty_transactions: 1,
        transaction_type: "in",
        user_id: "",
      };
    },
  },
};
</script>
