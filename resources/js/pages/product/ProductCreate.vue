<script setup>
    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';
    import { reactive, onMounted, ref, watch } from 'vue';
    import { useRouter,useRoute } from 'vue-router';
    import { Form } from 'vee-validate';
    import { useToastr } from '@/toastr';
    import { debounce } from 'lodash';

    const toastr = useToastr();
    const parentCategories = ref();
    const childCategories = ref();
    const router = useRouter();
    const route = useRoute();
    const editMode = ref(false);
    const getToken = localStorage.getItem("token");
    const getAuthorizationHeader = () => 'Bearer '+getToken;
    const form = reactive({
        name: '',
        qty: '',
        price: '',
        description: '',
        parent_categorie_id: '',
        child_categorie_id: '',
        product_image: '',
        product_gallery_image : '',
    });

    const getParentCategorie = () => {
        axios.get(
            '/api/v1/parent-categories-list',
            {
                headers: { "Authorization" : getAuthorizationHeader() },
            }
        )
        .then((response) => {
            parentCategories.value = response.data;
        })
    };

    const getChildCategorie = ($event) => {
        let id = event.target.value;
        childCategorie(id);
    }

    const childCategorie = (id) => {
        axios.post(
            '/api/v1/child-categories-list/',{id: id},
            {
                headers: { "Authorization" : getAuthorizationHeader() },

            }
        )
        .then((response) => {
            childCategories.value = response.data;
        })
    }

    const handleSubmit = (values,actions) => {
        if(editMode.value){
            updateProduct(values, actions);
        }else{
            createProduct(values, actions);
        }
    };

    const mainImageFile = ($event) => {
        form.product_image = event.target.files;
    }
    const galleryImageFiles = ($event) => {
        form.product_gallery_image = event.target.files;
    }

    const createProduct = (values,actions) => {
        axios.post(
            '/api/v1/product/create',form,
            {
                headers: { 'content-type': 'multipart/form-data', "Authorization" : getAuthorizationHeader() },
            }
        )
        .then((response) => {
            router.push('/admin/product');
            toastr.success('Product created successfully!');
        })
        .catch((error) => {
            if(error.response.status == 422){
                toastr.error(error.response.data.message);
                actions.setErrors(error.response.data.errors);
            }else{
                actions.setErrors(error.response.data.errors);
            }
        })
    }

    const updateProduct = (values, actions) => {
        axios.post(
            `/api/v1/product/${route.params.id}/edit`, form,
            {
                headers: { 'content-type': 'multipart/form-data',"Authorization" : getAuthorizationHeader() },
            }
        )
        .then((response) => {
            router.push('/admin/product');
            toastr.success('Product updated successfully!');
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
    }

    const getProduct = () => {
        axios.get(
            `/api/v1/product/${route.params.id}/edit`,
            {
                headers: { "Authorization" : getAuthorizationHeader() },
            }
        )
        .then(({data}) => {
            form.name = data.name;
            form.qty = data.qty;
            form.price = data.price;
            form.description = data.description;
            form.parent_categorie_id = data.cid;
            form.child_categorie_id = data.sid;
            form.product_image = data.image;
            form.product_gallery_image = data.productimages;
        })
    };


    onMounted(() => {
        getParentCategorie();
        if (route.name === 'admin.product.edit') {
            editMode.value = true;
            getProduct();
            watch(debounce(() => {
                let sid = $('#categorie').val();
                childCategorie(sid);
            }, 3000));
        }
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
                            <h1 class="m-0">Create Product</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <router-link to="/admin/dashboard">Home</router-link>
                                </li>
                                <li class="breadcrumb-item">
                                    <router-link to="/admin/product">Product</router-link>
                                </li>
                                <li class="breadcrumb-item active">Create</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <Form @submit="handleSubmit"  v-slot:default="{ errors }">
                                    <div class="card-header">
                                        <h4>Product Details Form</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Product detail</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" v-model="form.name" :class="{ 'is-invalid': errors.name }" class="form-control" id="name" placeholder="Enter product name">
                                                            <span class="invalid-feedback">{{ errors.name }}</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Qty</label>
                                                            <input type="number" v-model="form.qty" :class="{ 'is-invalid': errors.qty }" class="form-control" id="qty" placeholder="Enter product qty">
                                                            <span class="invalid-feedback">{{ errors.qty }}</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Price</label>
                                                            <input type="number" v-model="form.price" :class="{ 'is-invalid': errors.price }" class="form-control" id="price" placeholder="Enter product price">
                                                            <span class="invalid-feedback">{{ errors.price }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Product Categorie</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Categorie</label>
                                                                    <select v-model="form.parent_categorie_id" :class="{ 'is-invalid': errors.parent_categorie_id }" class="form-control" id="categorie" @change="getChildCategorie($event)">
                                                                        <option value="">Select Categorie</option>
                                                                        <option v-for='parentCategorie in parentCategories' :value='parentCategorie.id' :key="parentCategorie.id">{{ parentCategorie.name }}</option>
                                                                    </select>
                                                                    <span class="invalid-feedback">{{ errors.parent_categorie_id }}</span>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Sub-Categorie</label>
                                                                    <select v-model="form.child_categorie_id" :class="{ 'is-invalid': errors.child_categorie_id }" class="form-control">
                                                                        <option value="">Select Child Categorie</option>
                                                                        <option v-for="childCategorie in childCategories" :value="childCategorie.id" :key="childCategorie.id">{{ childCategorie.name}}</option>
                                                                    </select>
                                                                    <span class="invalid-feedback">{{ errors.child_categorie_id }}</span>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="client">Sub-Sub-Categorie</label>
                                                                    <select id="client" class="form-control">
                                                                        <option value="">Select Child Categorie</option>
                                                                        <option v-for="childCategorie in childCategories" :value="childCategorie.id" :key="childCategorie.id">{{ childCategorie.name}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea v-model="form.description" :class="{ 'is-invalid': errors.description }" class="form-control" id="description" rows="3" placeholder="Enter product Description"></textarea>
                                                    <span class="invalid-feedback">{{ errors.description }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Product Mail Image</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Add your product image</label>
                                                            <input type="file" id="product" class="form-control-file" name="product_image"
                                                                ref="mainImage"
                                                                @input="mainImagePickFile"
                                                                @change="mainImageFile($event)"
                                                                :class="{ 'is-invalid': errors.product_image }"
                                                            >

                                                            <div id="product_main_img_prev" v-if="editMode" class="row mt-3">
                                                                <img :src="form.product_image" alt="" class="product_image">
                                                            </div>
                                                            <div id="product_main_img_prev" v-else class="row mt-3"></div>
                                                            <span class="invalid-feedback">{{ errors.product_image }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Product Gallery Image</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Add your product gallery image</label>
                                                            <input type="file" id="product" class="form-control-file" multiple name="product_gallery_image[]"
                                                                ref="galleryImageInput"
                                                                @input="galleryImagePickFile"
                                                                @change="galleryImageFiles($event)"
                                                                :class="{ 'is-invalid': errors.product_gallery_image }"
                                                            >
                                                            <div id="product_gallery_img_prev" class="row mt-3"></div>
                                                            <span class="invalid-feedback">{{ errors.product_gallery_image }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </Form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </div>

</template>
<script>
export default {
    data() {
        return {
            previewImage: null
        };
    },
    methods: {
        mainImagePickFile(){
            $('#product_main_img_prev').html('');
            let input = this.$refs.mainImage
            let file = input.files
            if (file && file[0]) {
                let reader = new FileReader
                reader.onload = e => {
                    // this.previewImage = e.target.result
                    $('#product_main_img_prev').append('<img class="img-fluid col-md-4" src="'+e.target.result+'">')
                }
                reader.readAsDataURL(file[0])
                this.$emit('input', file[0])
            }
        },
        galleryImagePickFile () {
            $('#product_gallery_img_prev').html('');
            let input = this.$refs.galleryImageInput
            let file = input.files
            for (let index = 0; index < file.length; index++) {
                if (file && file[index]) {
                    let reader = new FileReader
                    reader.onload = e => {
                        $('#product_gallery_img_prev').append('<img class="img-fluid col-md-4" src="'+e.target.result+'">')
                    }
                    reader.readAsDataURL(file[index])
                    this.$emit('input', file[index])
                }
            }
        }
    }
}
</script>
