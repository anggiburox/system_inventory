import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import LoginView from "../views/LoginView.vue";
import RegisterView from "../views/RegisterView.vue";
import ProductView from "@/views/Products/ProductView.vue";
import AddProductView from "@/views/Products/AddProductView.vue";
import MainContent from "@/components/MainContent.vue";
import EditProductView from "@/views/Products/EditProductView.vue";
import WarehouseView from "@/views/Warehouses/WarehouseView.vue";
import AddWarehouseView from "@/views/Warehouses/AddWarehouseView.vue";
import EditWarehouseView from "@/views/Warehouses/EditWarehouseView.vue";
import StockView from "@/views/Stocks/StockView.vue";
import TransactionView from "@/views/Transactions/TransactionView.vue";
import AddTransactionView from "@/views/Transactions/AddTransactionView.vue";
import AddStockVIew from "@/views/Stocks/AddStockVIew.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      component: MainContent, // Layout Utama
      meta: { requiresAuth: true }, // Butuh autentikasi
      children: [
        {
          path: "",
          name: "home",
          component: HomeView, // Home Page
          meta: { requiresAuth: true }, // Tambahkan meta auth di child
        },
        {
          path: "/product",
          name: "product",
          component: ProductView, // Product Page
          meta: { requiresAuth: true },
        },
        {
          path: "/addproduct",
          name: "addproduct",
          component: AddProductView, // Product Page
          meta: { requiresAuth: true },
        },
        {
          path: "/editproduct/:product_id",
          name: "editproduct",
          component: EditProductView, // Product Page
          meta: { requiresAuth: true },
        },
        {
          path: "/warehouse",
          name: "warehouse",
          component: WarehouseView, // Warehouse Page
          meta: { requiresAuth: true },
        },
        {
          path: "/addwarehouse",
          name: "addwarehouse",
          component: AddWarehouseView, // Warehouse Page
          meta: { requiresAuth: true },
        },
        {
          path: "/editwarehouse/:warehouse_id",
          name: "editwarehouse",
          component: EditWarehouseView, // Warehouse Page
          meta: { requiresAuth: true },
        },
        {
          path: "/stock",
          name: "stock",
          component: StockView, // Stock Page
          meta: { requiresAuth: true },
        },
        {
          path: "/addstock",
          name: "addstock",
          component: AddStockVIew, // Stock Page
          meta: { requiresAuth: true },
        },
        {
          path: "/transaction",
          name: "transaction",
          component: TransactionView, // Transaction Page
          meta: { requiresAuth: true },
        },
        {
          path: "/addtransaction",
          name: "addtransaction",
          component: AddTransactionView, // Transaction Page
          meta: { requiresAuth: true },
        },
      ],
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
      meta: { guest: true }, // Hanya untuk guest
    },
    {
      path: "/register",
      name: "register",
      component: RegisterView,
      meta: { guest: true },
    },
  ],
});

// Route Guard untuk mengecek autentikasi
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem("token"); // Mengecek token di localStorage

  if (to.meta.requiresAuth && !isAuthenticated) {
    // Jika halaman memerlukan autentikasi tapi user belum login
    next({ name: "login" });
  } else if (to.meta.guest && isAuthenticated) {
    // Jika halaman khusus guest tapi user sudah login
    next({ name: "home" });
  } else {
    // Jika kondisi sesuai, lanjutkan akses
    next();
  }
});

export default router;
