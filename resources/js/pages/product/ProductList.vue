<script setup>
    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';

</script>
<template>
    <div class="wrapper">
        <Header />
        <Sidebar />
        <div class="content-wrapper" >
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Product</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Product</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <a href="">
                                        <router-link to="/admin/product/create">
                                            <button class="btn btn-primary">
                                                <i class="fa fa-plus-circle mr-1"></i> Add New Product
                                            </button>
                                        </router-link>
                                    </a>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Qty</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Mr. Martin Glover MD</td>
                                                <td>2023-01-27</td>
                                                <td>
                                                    <span class="badge badge-success">closed</span>
                                                </td>
                                                <td>
                                                    <a href="">
                                                        <i class="fa fa-edit mr-2"></i>
                                                    </a>

                                                    <a href="">
                                                        <i class="fa fa-trash text-danger"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>
