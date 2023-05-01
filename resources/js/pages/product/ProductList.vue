<script setup>
    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';

    import { onMounted, ref,watch } from 'vue';
    import { Bootstrap4Pagination } from 'laravel-vue-pagination';
    import axios from 'axios';
    import { useToastr } from '@/toastr';
    import { debounce } from 'lodash';

    const toastr = useToastr();
    const products = ref({'data' : []});
    const getToken = localStorage.getItem("token");
    const getAuthorizationHeader = () => 'Bearer '+getToken;
    const productgallery = ref(null);

    const getProducts = (page = 1) => {
        axios.get(
            `/api/v1/products?page=${page}`,
            {
                headers: { "Authorization" : getAuthorizationHeader() }
            }
        )
        .then((response) => {
            products.value = response.data;
        })
    }

    const productImage = (id) => {
        axios.get(
            `/api/v1/product-gallery-image?id=${id}`,
            {
                headers: { "Authorization" : getAuthorizationHeader() }
            }
        )
        .then((response) => {
            if(response.data.status){
                productgallery.value = response.data.data;
                $('#product-gallery-images').modal('show');
            }else{
                toastr.error(response.data.message);
            }
        })
    }

    const imageIdBeingDeleted = ref(null);
    const confirmDeleteImage = (id) => {
        imageIdBeingDeleted.value = id;
        $('#deleteImageModal').modal('show');
    };
    const deleteImage = () => {
        axios.delete(
            '/api/v1/galleryimagedelete/'+imageIdBeingDeleted.value,
            {
                headers: { "Authorization" : getAuthorizationHeader() }
            }
        )
        .then((response) => {
            $('#deleteImageModal').modal('hide');
            toastr.success('Image delete successfully!');
            watch(debounce(() => {
                $('#galleryimage'+imageIdBeingDeleted.value).remove();
            }, 2000));
        });
    }

    onMounted(() => {
        getProducts();
    });
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
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Categorie</th>
                                                <th>Child Categorie</th>
                                                <th>Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(product, index) in products.data" :key="product.id">
                                                <td>{{ index + 1 }}</td>
                                                <td><img :src="product.image" class="img-md"></td>
                                                <td>{{ product.name }}</td>
                                                <td>{{ product.qty }}</td>
                                                <td>{{ product.price }}</td>
                                                <td>{{ product.category.name }}</td>
                                                <td>{{ product.childcategory.name }}</td>
                                                <td>
                                                    <router-link :to="`/admin/product/${product.id}/edit`">
                                                        <i class="fa fa-edit mr-2"></i>
                                                    </router-link>
                                                    <a href="">
                                                        <i class="fa fa-trash text-danger mr-2"></i>
                                                    </a>
                                                    <span @click.prevent="productImage(product.id)" style="cursor: pointer;">
                                                        <i class="fas fa-images"></i>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <Bootstrap4Pagination :data="products" @pagination-change-page="getProducts" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </div>
    <div class="modal fade" id="product-gallery-images" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Product Gallery Images</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3" v-for="proimg in productgallery" :key="proimg.id" :id="`galleryimage${proimg.id}`">
                            <div class="card w-100">
                                <img class="card-img-top" :src="proimg.image" style="height: 106px;">
                                <div class="card-body p-0">
                                    <button @click.prevent="confirmDeleteImage(proimg.id)" class="btn btn-danger btn-sm w-100">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteUser" type="button" class="btn btn-primary">Delete User</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteImageModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Delete Image</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this image ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteImage" type="button" class="btn btn-primary">Delete Image</button>
                </div>
            </div>
        </div>
    </div>
</template>
