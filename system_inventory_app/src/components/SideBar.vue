<template lang="">
      <header v-if="$route.name != 'login'">
    <div class="container-fluid overflow-hidden">
      <div class="row vh-100 overflow-auto">
        <div class="col-12 col-sm-3 col-xl-2 px-sm-2 px-0 bg-white d-flex sticky-top">
            <div class="d-flex flex-sm-column flex-row flex-grow-1 align-items-center align-items-sm-start px-3 pt-2 text-white">
                <a href="/" class="d-flex align-items-center pb-sm-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fw-bold text-black">Inventory</span>
                </a>
                <ul class="nav nav-pills flex-sm-column flex-row flex-nowrap flex-shrink-1 flex-sm-grow-0 flex-grow-1 mb-sm-auto mb-0 justify-content-center align-items-center align-items-sm-start" id="menu">
                    <li>
                        <a href="/" data-bs-toggle="collapse" class="nav-link px-sm-0 px-2 text-black">
                            <i class="fs-5 bi-speedometer2"></i><span class="ms-1 d-none d-sm-inline">Dashboard</span> </a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle px-sm-0 px-1 text-black" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fs-5 bi-database"></i><span class="ms-1 d-none d-sm-inline">Master Data</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdown">
                            <li><a class="dropdown-item" href="/product">Product</a></li>
                            <li><a class="dropdown-item" href="/warehouse">Warehouse</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/transaksi" data-bs-toggle="collapse" class="nav-link px-sm-0 px-2 text-black">
                            <i class="fs-5 bi-cash-coin"></i><span class="ms-1 d-none d-sm-inline">Transaksi</span> </a>
                    </li>
                    <li>
                        <a href="/stock" data-bs-toggle="collapse" class="nav-link px-sm-0 px-2 text-black">
                            <i class="fs-5 bi-box-seam"></i><span class="ms-1 d-none d-sm-inline">Stock</span> </a>
                    </li>
                </ul>
                <div class="dropdown py-sm-4 mt-sm-auto ms-auto ms-sm-0 flex-shrink-1">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../assets/Foto/default.png" alt="hugenerd" width="28" height="28" class="rounded-circle">
                        <span class="d-none d-sm-inline mx-1 text-black">Hi, {{ name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#" @click="logout()">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col d-flex flex-column h-sm-100 bg-light">
            <slot>

            </slot> <!-- Slot untuk konten yang akan berubah -->
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import axios from 'axios';
import router from '@/router';

export default {
    props : ['name'],
    methods: {
        logout() {
        axios.get('http://localhost/system_inventory/public/api/auth/logout', {
            headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
                })
                .then(function (response) {
                    localStorage.removeItem('email');
                    localStorage.removeItem('name');
                    localStorage.removeItem('token');
                    localStorage.removeItem('user_id');
                    router.push({
                        name: 'login'
                    })
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
    }
}
</script>