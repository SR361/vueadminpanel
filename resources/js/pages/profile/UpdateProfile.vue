<script setup>
    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';

    import { ref, onMounted,reactive } from 'vue';
    import { useToastr } from '../../toastr.js';
    import { Form, Field } from 'vee-validate';
    import * as yup from 'yup';

    const toastr = useToastr();
    const formValues = ref({
        name : '',
        designation : '',
        education : '',
        location : '',
        skills : '',
    });

    const config = {
        headers: {
            'content-type': 'multipart/form-data',
            "Authorization" : 'Bearer '+localStorage.getItem("token")
        }
    };
    const form = ref(null);

    const profileDetails = yup.object({
        name : yup.string().required(),
        email : yup.string().email().required(),
    })

    const changepasswordschema = yup.object({
        password : yup.string().required('Password is required').min(6),
        password_confirmation : yup.string().required('Confirm password is required').min(6),
    })

    const changePasswordFormSubmit = (values,{resetForm, setErrors}) => {
        axios.post('/api/v1/changepassword', values, config)
        .then(function (response) {
            if (response.status === 200) {
                toastr.success('Password change successfully!');
                $('#password').val('');
                $('#confirmpassword').val('');
            }
        })
        .catch((error) => {
            if (error.response) {
                setErrors(error.response.data.errors);
            }
        })
    }

    const changepasswordsubmit = (values,actions) => {
        changePasswordFormSubmit(values,actions);
    }

    const formSubmit = (values,{ setErrors }) => {
        const config = {
            headers: {
                'content-type': 'multipart/form-data',
                "Authorization" : 'Bearer '+localStorage.getItem("token")
            }
        }
        axios.post('/api/v1/updateprofile', values, config)
        .then(function (response) {
            let user = JSON.stringify(response.data);
            localStorage.setItem("user",user);
            $('.admin_name').text(response.data.name);
            $('.admin_profile_photo').attr('src',response.data.profile_photo);
            toastr.success('Profile details update successfully!');
        })
        .catch((error) => {
            if (error.response) {
                setErrors(error.response.data.errors);
            }
        })
    }

    const handleSubmit = (values, actions) => {
        formSubmit(values, actions);
    }

    const companySettingFormValues = ref({
        companyname : '',
        companyemail : '',
        companyphone : '',
        companywebsite : '',
    })

    const companysettingsubmit = (values,actions) => {
        companysettingFormSubmit(values,actions);
    }

    const companysettingFormSubmit = (values, {setErrors}) => {
        axios.post('/api/v1/createcompany', values, config)
        .then(function (response) {
            if (response.status === 200) {
                toastr.success('Company details update successfully!');
            }
        })
        .catch((error) => {
            if (error.response) {
                setErrors(error.response.data.errors);
            }
        })
    }

    const getUserProfile = () => {
        axios.get('/api/v1/getuserprofile',config)
        .then(response=>{
            let user = JSON.stringify(response.data);
            localStorage.setItem("user",user);
            formValues.value = {
                name : response.data.name,
                email : response.data.email,
                designation : response.data.designation,
                education : response.data.education,
                location : response.data.location,
                skills : response.data.skills,
            }
        }).catch(error=>{
            console.log(error)
        })
    }

    const getCompany = () => {
        axios.get('/api/v1/getcompaneis',config)
        .then(response=>{
            console.log(response.data);
            companySettingFormValues.value = {
                companyname : response.data.company_name,
                companyemail : response.data.company_email,
                companyphone : response.data.company_phone,
                companywebsite : response.data.website,
            }
        }).catch(error=>{
            console.log(error)
        })
    }

    onMounted(() => {
        getUserProfile();
        getCompany();
        let user = JSON.parse(localStorage.getItem("user"));
        $('.profile-user-img').attr('src',user.profile_photo);
    })
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
                            <h1 class="m-0">Profile</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle" src="https://adminlte.io/themes/v3/dist/img/user4-128x128.jpg" alt="User profile picture">
                                    </div>
                                    <h3 class="profile-username text-center">{{ formValues.name }}</h3>
                                    <p class="text-muted text-center">{{ formValues.designation }}</p>
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Followers</b> <a class="float-right">1,322</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Following</b> <a class="float-right">543</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Friends</b> <a class="float-right">13,287</a>
                                        </li>
                                    </ul>
                                    <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                                </div>
                            </div>
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">About Me</h3>
                                </div>

                                <div class="card-body">
                                    <strong><i class="fas fa-book mr-1"></i> Education</strong>
                                    <p class="text-muted">
                                        {{ formValues.education }}
                                    </p>
                                    <hr>
                                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
                                    <p class="text-muted">{{ formValues.location }}</p>
                                    <hr>
                                    <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>
                                    <p class="text-muted">
                                        {{ formValues.skills }}
                                    </p>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#changepassword" data-toggle="tab">Change Password</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#companysetting" data-toggle="tab">Company Setting</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane" id="activity">
                                            <div class="post">
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm" src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg" alt="user image">
                                                    <span class="username">
                                                        <a href="#">Jonathan Burke Jr.</a>
                                                        <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                                    </span>
                                                    <span class="description">Shared publicly - 7:30 PM today</span>
                                                </div>
                                                <p>
                                                    Lorem ipsum represents a long-held tradition for designers,
                                                    typographers and the like. Some people hate it and argue for
                                                    its demise, but others ignore the hate as they create awesome
                                                    tools to help create filler text for everyone from bacon lovers
                                                    to Charlie Sheen fans.
                                                </p>
                                                <p>
                                                    <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                                    <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                                    <span class="float-right">
                                                        <a href="#" class="link-black text-sm">
                                                            <i class="far fa-comments mr-1"></i> Comments (5)
                                                        </a>
                                                    </span>
                                                </p>
                                                <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                                            </div>
                                            <div class="post clearfix">
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm" src="https://adminlte.io/themes/v3/dist/img/user7-128x128.jpg" alt="User Image">
                                                    <span class="username">
                                                        <a href="#">Sarah Ross</a>
                                                        <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                                    </span>
                                                    <span class="description">Sent you a message - 3 days ago</span>
                                                </div>
                                                <p>
                                                    Lorem ipsum represents a long-held tradition for designers,
                                                    typographers and the like. Some people hate it and argue for
                                                    its demise, but others ignore the hate as they create awesome
                                                    tools to help create filler text for everyone from bacon lovers
                                                    to Charlie Sheen fans.
                                                </p>
                                                <form class="form-horizontal">
                                                    <div class="input-group input-group-sm mb-0">
                                                        <input class="form-control form-control-sm" placeholder="Response">
                                                        <div class="input-group-append">
                                                            <button type="submit" class="btn btn-danger">Send</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="post">
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm" src="https://adminlte.io/themes/v3/dist/img/user6-128x128.jpg" alt="User Image">
                                                    <span class="username">
                                                        <a href="#">Adam Jones</a>
                                                        <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                                    </span>
                                                    <span class="description">Posted 5 photos - 5 days ago</span>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-6">
                                                        <img class="img-fluid" src="https://adminlte.io/themes/v3/dist/img/photo1.png" alt="Photo">
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <img class="img-fluid mb-3" src="https://adminlte.io/themes/v3/dist/img/photo2.png" alt="Photo">
                                                                <img class="img-fluid" src="https://adminlte.io/themes/v3/dist/img/photo3.jpg" alt="Photo">
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <img class="img-fluid mb-3" src="https://adminlte.io/themes/v3/dist/img/photo4.jpg" alt="Photo">
                                                                <img class="img-fluid" src="https://adminlte.io/themes/v3/dist/img/photo1.png" alt="Photo">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p>
                                                    <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                                    <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                                    <span class="float-right">
                                                        <a href="#" class="link-black text-sm">
                                                            <i class="far fa-comments mr-1"></i> Comments (5)
                                                        </a>
                                                    </span>
                                                </p>
                                                <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                                            </div>
                                        </div>
                                        <div class="tab-pane active" id="settings">
                                            <Form ref="form" @submit="handleSubmit" :validation-schema="profileDetails" class="form-horizontal" v-slot="{ errors }" :initial-values="formValues">
                                                <div class="form-group row">
                                                    <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                                    <div class="col-sm-10">
                                                        <Field name="name" v-model="formValues.name" :class="{ 'is-invalid':errors.name }" id="name" type="text" class="form-control" placeholder="Name"  />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                                    <div class="col-sm-10">
                                                        <Field name="email" :class="{ 'is-invalid':errors.email }" id="email" type="email" class="form-control" placeholder="Email"  />
                                                        <span class="invalid-feedback">{{ errors.email }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputName2" class="col-sm-2 col-form-label">Designation</label>
                                                    <div class="col-sm-10">
                                                        <Field name="designation" v-model="formValues.designation" :class="{ 'is-invalid':errors.designation }" type="text" class="form-control" placeholder="Name"  />
                                                        <span class="invalid-feedback">{{ errors.email }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputExperience" class="col-sm-2 col-form-label">Education</label>
                                                    <div class="col-sm-10">
                                                        <Field as="textarea" name="education" v-model="formValues.education" :class="{ 'is-invalid':errors.education }" class="form-control" placeholder="Education" />
                                                        <span class="invalid-feedback">{{ errors.education }}</span>
                                                        <!-- <Field v-slot="{ field }" name="education">
                                                            <textarea v-bind="field" class="form-control" />
                                                        </Field> -->
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputSkills"  class="col-sm-2 col-form-label">Skills</label>
                                                    <div class="col-sm-10">
                                                        <Field name="skills" v-model="formValues.skills" :class="{ 'is-invalid':errors.skills }" type="text" class="form-control" placeholder="Skills" />
                                                        <span class="invalid-feedback">{{ errors.skills }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputSkills"  class="col-sm-2 col-form-label">Location</label>
                                                    <div class="col-sm-10">
                                                        <Field name="location" v-model="formValues.location" :class="{ 'is-invalid':errors.location }" type="text" class="form-control" placeholder="Location" />
                                                        <span class="invalid-feedback">{{ errors.location }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputFile" class="col-sm-2">File input</label>
                                                    <div class="input-group col-sm-10">
                                                        <div class="custom-file">
                                                            <Field name="profile_photo" v-on:change="onChange" type="file" class="custom-file-input" />
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="offset-sm-2 col-sm-10">
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                    </div>
                                                </div>
                                            </Form>
                                        </div>
                                        <div class="tab-pane" id="changepassword">
                                            <!-- :initial-values="changePasswordFormValues" -->
                                            <Form ref="form" @submit="changepasswordsubmit" :validation-schema="changepasswordschema" v-slot="{ errors }"  class="form-horizontal">
                                                <div class="form-group row">
                                                    <label  class="col-sm-3">New Password Pasword</label>
                                                    <div class="input-group col-sm-9">
                                                        <div class="custom-file">
                                                            <!-- <input v-validate="'required'" name="password" type="password" :class="{'is-danger': errors.has('password')}" placeholder="Password" ref="password"> -->
                                                            <Field id="password" name="password"  :class="{ 'is-invalid':errors.password }" class="form-control" type="password" placeholder="Enter Password" />
                                                            <span class="invalid-feedback ml-3">{{ errors.password }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label  class="col-sm-3">Confirm Pasword</label>
                                                    <div class="input-group col-sm-9">
                                                        <div class="custom-file">
                                                            <!-- <input v-validate="'required|confirmed:password'" name="password_confirmation" type="password" :class="{'is-danger': errors.has('password_confirmation')}" placeholder="Password, Again" data-vv-as="password"> -->
                                                            <Field id="confirmpassword" name="password_confirmation" :class="{ 'is-invalid':errors.password_confirmation }" class="form-control" type="password" placeholder="Enter Confirm Password" />
                                                            <span class="invalid-feedback ml-3">{{ errors.password_confirmation }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row ">
                                                    <div class="col-sm-12 d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-success">Change Password</button>
                                                    </div>
                                                </div>
                                            </Form>
                                        </div>
                                        <div class="tab-pane" id="companysetting">
                                            <Form ref="form" @submit="companysettingsubmit" v-slot="{ errors }" :initial-values="companySettingFormValues" class="form-horizontal">
                                                <div class="form-group row">
                                                    <label  class="col-sm-3">Company Name</label>
                                                    <div class="input-group col-sm-9">
                                                        <div class="custom-file">
                                                            <Field id="companyname" v-model="companySettingFormValues.companyname" name="companyname"  :class="{ 'is-invalid':errors.companyname }" class="form-control" type="text" placeholder="e.g. Acme Corporation" />
                                                            <span class="invalid-feedback ml-3">{{ errors.companyname }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label  class="col-sm-3">Company Email</label>
                                                    <div class="input-group col-sm-9">
                                                        <div class="custom-file">
                                                            <Field id="companyemail" v-model="companySettingFormValues.companyemail" name="companyemail"  :class="{ 'is-invalid':errors.companyemail }" class="form-control" type="text" placeholder="e.g. johndoe@example.com" />
                                                            <span class="invalid-feedback ml-3">{{ errors.companyemail }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label  class="col-sm-3">Company Phone</label>
                                                    <div class="input-group col-sm-9">
                                                        <div class="custom-file">
                                                            <Field id="companyphone" v-model="companySettingFormValues.companyphone" name="companyphone"  :class="{ 'is-invalid':errors.companyphone }" class="form-control" type="text" placeholder="e.g. 9875486548" />
                                                            <span class="invalid-feedback ml-3">{{ errors.companyphone }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label  class="col-sm-3">Company Website</label>
                                                    <div class="input-group col-sm-9">
                                                        <div class="custom-file">
                                                            <Field id="companywebsite" v-model="companySettingFormValues.companywebsite" name="companywebsite"  :class="{ 'is-invalid':errors.companywebsite }" class="form-control" type="url" placeholder="e.g. https://www.spacex.com/" />
                                                            <span class="invalid-feedback ml-3">{{ errors.companywebsite }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row ">
                                                    <div class="col-sm-12 d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-success">Save</button>
                                                    </div>
                                                </div>
                                            </Form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <Footer />
    </div>
</template>
<script>
    export default {
        data() {
            return {
                name: '',
                file: '',
                success: ''
            };
        },
        methods: {
            onChange(e) {
                this.file = e.target.files[0];

                let reader = new FileReader
                reader.onload = e => {
                    $('.profile-user-img').attr('src',e.target.result);
                }
                reader.readAsDataURL(e.target.files[0])
            },
        }
    }
</script>
