<script setup>
    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';
    import { reactive, ref, onMounted } from 'vue';
    import { Form } from 'vee-validate';
    import { useToastr } from '@/toastr';
    import Swal from 'sweetalert2';

    const toastr = useToastr();
    const getToken = localStorage.getItem("token");
    const getAuthorizationHeader = () => 'Bearer '+getToken;

    const generalSettingForm = reactive({
        sitelogo: '',
        theme: '',
        footer: '',
        oldsitelogo: '',
        sidebar_option : [],
        header_setting : [],
        sidebar_variants : '',
        navbar_variants : '',
        accent_color_variants : '',
        dark_sidebar_variants : '',
        light_sidebar_variants : '',
        brand_logo_variants : ''
    });

    const generalSetting = (values,actions) => {
        // generalSettingForm.sidebar_option = selectedSidebarSetting.value;
        axios.post(
            '/api/v1/setting/generalsetting',generalSettingForm,
            {
                headers: { 'content-type': 'multipart/form-data', "Authorization" : getAuthorizationHeader() },
            }
        )
        .then((response) => {
            toastr.success('General setting saved!');
        })
        .catch((error) => {
            if(error.response.status == 422){
                toastr.error(error.response.data.message);
            }else{
                actions.setErrors(error.response.data.errors);
            }
        })
    }

    const getSetting = () => {
        axios.get(
            '/api/v1/setting/getsetting',
            {
                headers : { "Authorization" : getAuthorizationHeader() },
            }
        ).then((response) => {
            prevClass.value = response.data.navbar_variants;
            previousAccentColor.value = response.data.accent_color_variants;
            previousdarkSidebarVariantsColor.value = response.data.dark_sidebar_variants;
            previouslightSidebarVariantsColor.value = response.data.light_sidebar_variants;
            previousBrandLogo.value = 'bg-'+response.data.brand_logo_variants;

            let setting = JSON.stringify(response.data);
            localStorage.setItem("setting",setting);
            if(response.data.header_setting != null){
                generalSettingForm.header_setting = response.data.header_setting;
            }

            if(response.data.sidebar_option != null){
                generalSettingForm.sidebar_option = response.data.sidebar_option;
            }

            if(response.data.footer != null){
                generalSettingForm.footer = response.data.footer;
            }

            generalSettingForm.sitelogo = response.data.site_logo;
            generalSettingForm.theme = response.data.theme;
            generalSettingForm.oldsitelogo = response.data.oldsitelogo;
            generalSettingForm.sidebar_variants = response.data.sidebar_variants;
            generalSettingForm.navbar_variants = response.data.navbar_variants;
            generalSettingForm.accent_color_variants = response.data.accent_color_variants;
            generalSettingForm.dark_sidebar_variants = response.data.dark_sidebar_variants;
            generalSettingForm.light_sidebar_variants = response.data.light_sidebar_variants;
            generalSettingForm.brand_logo_variants = response.data.brand_logo_variants;
        });
    }

    const sitelogoimg = ($event) => {
        generalSettingForm.sitelogo = event.target.files;
    }

    // const selectedSidebarSetting = ref([]);
    const toggleSelection = ($event) => {
        if($("body").hasClass($event)){
            $("body").removeClass($event);
        }else{
            $("body").addClass($event);
        }
    };

    const headerOptionSelection = ($event) => {
        if($event == 'layout-navbar-fixed'){
            if($("body").hasClass($event)){
                $("body").removeClass($event);
            }else{
                $("body").addClass($event);
            }
        }else{
            if($(".main-header").hasClass($event)){
                $(".main-header").removeClass($event);
            }else{
                $(".main-header").addClass($event);
            }
        }
    };

    const themeSetting = ($event) => {
        let themecolor = $event.target.value;
        if(themecolor == 'dark-mode'){
            $("body:not([class*='dark-mode'])").addClass("dark-mode");
        }else{
            $("body").removeClass("dark-mode");
        }
    }

    const prevClass = ref(null);
    const navbarVariants = ($event) => {
        let navbarVariant = $event.target.value;
        $(".main-header").removeClass(prevClass.value);
        $(".main-header:not([class*='"+navbarVariant+"'])").addClass(navbarVariant);
        prevClass.value = navbarVariant;
    }

    const previousAccentColor = ref(null);
    const accentColorVariants = ($event) => {
        let accentColor = $event.target.value;
        $("body").removeClass(previousAccentColor.value);
        $("body:not([class*='"+accentColor+"'])").addClass(accentColor);
        previousAccentColor.value = accentColor;
    }

    const previousdarkSidebarVariantsColor = ref(null);
    const darkSidebarVariants = ($event) => {
        let darkSidebarVariantColor = $event.target.value;
        $(".main-sidebar").removeClass(previousdarkSidebarVariantsColor.value);
        $(".main-sidebar").removeClass(previouslightSidebarVariantsColor.value);
        $(".main-sidebar:not([class*='"+darkSidebarVariantColor+"'])").addClass(darkSidebarVariantColor);
        previousdarkSidebarVariantsColor.value = darkSidebarVariantColor;
    }

    const previouslightSidebarVariantsColor = ref(null);
    const lightSidebarVariants = ($event) => {
        let lightSidebarVariantColor = $event.target.value;
        $(".main-sidebar").removeClass(previousdarkSidebarVariantsColor.value);
        $(".main-sidebar").removeClass(previouslightSidebarVariantsColor.value);
        $(".main-sidebar:not([class*='"+lightSidebarVariantColor+"'])").addClass(lightSidebarVariantColor);
        previouslightSidebarVariantsColor.value = lightSidebarVariantColor;
    }

    const previousBrandLogo = ref(null);
    const brandLogo = ($event) => {
        let brandLogoColor = 'bg-'+$event.target.value;
        $(".brand-link").removeClass(previousBrandLogo.value);
        $(".brand-link:not([class*='"+brandLogoColor+"'])").addClass(brandLogoColor);
        previousBrandLogo.value = brandLogoColor;
    }

    const resetSetting = () => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            footer: 'Note : Site logo not reset',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reset it!'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get(
                    '/api/v1/setting/resetsetting',
                    {
                        headers : { "Authorization" : getAuthorizationHeader() },
                    }
                ).then((response) => {
                    toastr.success('Your setting reset successfully!');
                })
                .catch((error) => {
                    actions.setErrors(error.response.data.errors);
                });
            }
        })
    }
    onMounted(() => {
        getSetting();
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
                            <h1 class="m-0">Settings</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h5>Site General Setting</h5>
                        </div>
                        <Form @submit="generalSetting"  v-slot:default="{ errors }">
                            <input type="hidden" v-model="generalSettingForm.oldsitelogo">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Site Logo</label>
                                            <input type="file" name="site_logo" class="form-control-file"
                                                ref="mainImage"
                                                @change="sitelogoimg($event)"
                                                @input="mainImagePickFile"
                                            >
                                            <div id="site_logo_image" class="row mt-3">
                                                <img :src="generalSettingForm.sitelogo" class="ml-2">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Site Theme Setting</label>
                                            <select class="form-control" v-model="generalSettingForm.theme" @change="themeSetting($event)">
                                                <option value="">Select Mode</option>
                                                <option value="dark-mode">Dark Mode</option>
                                                <option value="light-mode">Light Mode</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Footer Option</label>
                                            <select class="form-control" v-model="generalSettingForm.footer">
                                                <option value="">Select Position</option>
                                                <option value="layout-footer-fixed">Fixed</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Sidebar Option</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="sidebar-collapse" @click="toggleSelection('sidebar-collapse')" v-model="generalSettingForm.sidebar_option">
                                                        <label class="form-check-label">Collapsed</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="layout-fixed" @click="toggleSelection('layout-fixed')" v-model="generalSettingForm.sidebar_option">
                                                        <label class="form-check-label">Fixed</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="sidebar-mini" @click="toggleSelection('sidebar-mini')" v-model="generalSettingForm.sidebar_option">
                                                        <label class="form-check-label">Sidebar Mini</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="sidebar-mini-md" @click="toggleSelection('sidebar-mini-md')" v-model="generalSettingForm.sidebar_option">
                                                        <label class="form-check-label">Sidebar Mini MD</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="sidebar-mini-xs" @click="toggleSelection('sidebar-mini-xs')" v-model="generalSettingForm.sidebar_option">
                                                        <label class="form-check-label">Sidebar Mini XS</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="nav-flat" @click="toggleSelection('nav-flat')" v-model="generalSettingForm.sidebar_option">
                                                        <label class="form-check-label">Nav Flat Style</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="nav-legacy" @click="toggleSelection('nav-legacy')" v-model="generalSettingForm.sidebar_option">
                                                        <label class="form-check-label">Nav Legacy Style</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="nav-compact" @click="toggleSelection('nav-compact')" v-model="generalSettingForm.sidebar_option">
                                                        <label class="form-check-label">Nav Compact</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="nav-child-indent" @click="toggleSelection('nav-child-indent')" v-model="generalSettingForm.sidebar_option">
                                                        <label class="form-check-label">Nav Child Indent</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="nav-collapse-hide-child" @click="toggleSelection('nav-collapse-hide-child')" v-model="generalSettingForm.sidebar_option">
                                                        <label class="form-check-label">Nav Child Hide on Collapse</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Header Option</label>
                                            <div class="d-flex">
                                                <div class="form-check mr-5">
                                                    <input class="form-check-input" type="checkbox" value="layout-navbar-fixed" @click="headerOptionSelection('layout-navbar-fixed')" v-model="generalSettingForm.header_setting">
                                                    <label class="form-check-label">Fixed</label>
                                                </div>
                                                <div class="form-check mr-5">
                                                    <input class="form-check-input" type="checkbox" value="border-bottom-0" @click="headerOptionSelection('border-bottom-0')" v-model="generalSettingForm.header_setting">
                                                    <label class="form-check-label">No border</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Sidebar Variants</label>
                                            <select class="form-control" v-model="generalSettingForm.sidebar_variants">
                                                <option value="dark">Dark</option>
                                                <option value="light">Light</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Navbar Variants</label>
                                            <select class="form-control" v-model="generalSettingForm.navbar_variants" @change="navbarVariants($event)">
                                                <option value="">Select Option</option>
                                                <option class="bg-primary" value="bg-primary">Primary</option>
                                                <option class="bg-secondary" value="bg-secondary">Secondary</option>
                                                <option class="bg-info" value="bg-info">Info</option>
                                                <option class="bg-success" value="bg-success">Success</option>
                                                <option class="bg-danger" value="bg-danger">Danger</option>
                                                <option class="bg-indigo" value="bg-indigo">Indigo</option>
                                                <option class="bg-purple" value="bg-purple">Purple</option>
                                                <option class="bg-pink" value="bg-pink">Pink</option>
                                                <option class="bg-navy" value="bg-navy">Navy</option>
                                                <option class="bg-lightblue" value="bg-lightblue">Lightblue</option>
                                                <option class="bg-teal" value="bg-teal">Teal</option>
                                                <option class="bg-cyan" value="bg-cyan">Cyan</option>
                                                <option class="bg-dark" value="bg-dark">Dark</option>
                                                <option class="bg-gray-dark" value="bg-gray-dark">Gray dark</option>
                                                <option class="bg-gray" value="bg-gray">Gray</option>
                                                <option class="bg-light" value="bg-light">Light</option>
                                                <option class="bg-warning" value="bg-warning">Warning</option>
                                                <option class="bg-white" value="bg-white">White</option>
                                                <option class="bg-orange" value="bg-orange">Orange</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Accent Color Variants</label>
                                            <select class="form-control" v-model="generalSettingForm.accent_color_variants" @change="accentColorVariants($event)">
                                                <option value="">Select Option</option>
                                                <option class="bg-primary" value="accent-primary">Primary</option>
                                                <option class="bg-secondary" value="accent-secondary">Secondary</option>
                                                <option class="bg-info" value="accent-info">Info</option>
                                                <option class="bg-success" value="accent-success">Success</option>
                                                <option class="bg-danger" value="accent-danger">Danger</option>
                                                <option class="bg-indigo" value="accent-indigo">Indigo</option>
                                                <option class="bg-purple" value="accent-purple">Purple</option>
                                                <option class="bg-pink" value="accent-pink">Pink</option>
                                                <option class="bg-navy" value="accent-navy">Navy</option>
                                                <option class="bg-lightblue" value="accent-lightblue">Lightblue</option>
                                                <option class="bg-teal" value="accent-teal">Teal</option>
                                                <option class="bg-cyan" value="accent-cyan">Cyan</option>
                                                <option class="bg-dark" value="accent-dark">Dark</option>
                                                <option class="bg-gray-dark" value="accent-gray-dark">Gray dark</option>
                                                <option class="bg-gray" value="accent-gray">Gray</option>
                                                <option class="bg-light" value="accent-light">Light</option>
                                                <option class="bg-warning" value="accent-warning">Warning</option>
                                                <option class="bg-white" value="accent-white">White</option>
                                                <option class="bg-orange" value="accent-orange">Orange</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Dark Sidebar Variants</label>
                                            <select class="form-control" v-model="generalSettingForm.dark_sidebar_variants" @change="darkSidebarVariants($event)">
                                                <option value="">Select Option</option>
                                                <option class="bg-primary" value="sidebar-dark-primary">Primary</option>
                                                <option class="bg-secondary" value="sidebar-dark-secondary">Secondary</option>
                                                <option class="bg-info" value="sidebar-dark-info">Info</option>
                                                <option class="bg-success" value="sidebar-dark-success">Success</option>
                                                <option class="bg-danger" value="sidebar-dark-danger">Danger</option>
                                                <option class="bg-indigo" value="sidebar-dark-indigo">Indigo</option>
                                                <option class="bg-purple" value="sidebar-dark-purple">Purple</option>
                                                <option class="bg-pink" value="sidebar-dark-pink">Pink</option>
                                                <option class="bg-navy" value="sidebar-dark-navy">Navy</option>
                                                <option class="bg-lightblue" value="sidebar-dark-lightblue">Lightblue</option>
                                                <option class="bg-teal" value="sidebar-dark-teal">Teal</option>
                                                <option class="bg-cyan" value="sidebar-dark-cyan">Cyan</option>
                                                <option class="bg-dark" value="sidebar-dark-dark">Dark</option>
                                                <option class="bg-gray-dark" value="sidebar-dark-gray-dark">Gray dark</option>
                                                <option class="bg-gray" value="sidebar-dark-gray">Gray</option>
                                                <option class="bg-light" value="sidebar-dark-light">Light</option>
                                                <option class="bg-warning" value="sidebar-dark-warning">Warning</option>
                                                <option class="bg-white" value="sidebar-dark-white">White</option>
                                                <option class="bg-orange" value="sidebar-dark-orange">Orange</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Light Sidebar Variants</label>
                                            <select class="form-control" v-model="generalSettingForm.light_sidebar_variants" @change="lightSidebarVariants($event)">
                                                <option value="">Select Option</option>
                                                <option class="bg-primary" value="sidebar-light-primary">Primary</option>
                                                <option class="bg-secondary" value="sidebar-light-secondary">Secondary</option>
                                                <option class="bg-info" value="sidebar-light-info">Info</option>
                                                <option class="bg-success" value="sidebar-light-success">Success</option>
                                                <option class="bg-danger" value="sidebar-light-danger">Danger</option>
                                                <option class="bg-indigo" value="sidebar-light-indigo">Indigo</option>
                                                <option class="bg-purple" value="sidebar-light-purple">Purple</option>
                                                <option class="bg-pink" value="sidebar-light-pink">Pink</option>
                                                <option class="bg-navy" value="sidebar-light-navy">Navy</option>
                                                <option class="bg-lightblue" value="sidebar-light-lightblue">Lightblue</option>
                                                <option class="bg-teal" value="sidebar-light-teal">Teal</option>
                                                <option class="bg-cyan" value="sidebar-light-cyan">Cyan</option>
                                                <option class="bg-dark" value="sidebar-light-dark">Dark</option>
                                                <option class="bg-gray-dark" value="sidebar-light-gray-dark">Gray dark</option>
                                                <option class="bg-gray" value="sidebar-light-gray">Gray</option>
                                                <option class="bg-light" value="sidebar-light-light">Light</option>
                                                <option class="bg-warning" value="sidebar-light-warning">Warning</option>
                                                <option class="bg-white" value="sidebar-light-white">White</option>
                                                <option class="bg-orange" value="sidebar-light-orange">Orange</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Brand Logo Variants</label>
                                            <select class="form-control" v-model="generalSettingForm.brand_logo_variants" @change="brandLogo($event)">
                                                <option value="">Select Option</option>
                                                <option class="bg-primary" value="primary">Primary</option>
                                                <option class="bg-secondary" value="secondary">Secondary</option>
                                                <option class="bg-info" value="info">Info</option>
                                                <option class="bg-success" value="success">Success</option>
                                                <option class="bg-danger" value="danger">Danger</option>
                                                <option class="bg-indigo" value="indigo">Indigo</option>
                                                <option class="bg-purple" value="purple">Purple</option>
                                                <option class="bg-pink" value="pink">Pink</option>
                                                <option class="bg-navy" value="navy">Navy</option>
                                                <option class="bg-lightblue" value="lightblue">Lightblue</option>
                                                <option class="bg-teal" value="teal">Teal</option>
                                                <option class="bg-cyan" value="cyan">Cyan</option>
                                                <option class="bg-dark" value="dark">Dark</option>
                                                <option class="bg-gray-dark" value="gray-dark">Gray dark</option>
                                                <option class="bg-gray" value="gray">Gray</option>
                                                <option class="bg-light" value="light">Light</option>
                                                <option class="bg-warning" value="warning">Warning</option>
                                                <option class="bg-white" value="white">White</option>
                                                <option class="bg-orange" value="orange">Orange</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <span class="btn btn-success mr-2" @click="resetSetting()">Reset Setting</span>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </Form>
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
            previewImage: null,
        };
    },
    methods: {
        mainImagePickFile(){
            $('#site_logo_image').html('');
            let input = this.$refs.mainImage
            let file = input.files
            if (file && file[0]) {
                let reader = new FileReader
                reader.onload = e => {
                    // this.previewImage = e.target.result
                    $('#site_logo_image').append('<img class="img-fluid col-md-4" src="'+e.target.result+'">')
                    $('.brand-image').attr('src',e.target.result);
                }
                reader.readAsDataURL(file[0])
                this.$emit('input', file[0])
            }
        },
    }
}
</script>
