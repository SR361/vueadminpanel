<script setup>
    import { reactive, onMounted, ref } from 'vue';
    import { Form } from 'vee-validate';
    import { useRouter } from 'vue-router';
    import { useToastr } from '@/toastr';

    const router = useRouter();
    const toastr = useToastr();
    const form = reactive({
        email: '',
        password: '',
    });
    const handleSubmit = (values,actions) => {
        axios.post('/api/v1/login', form)
        .then((response) => {
            if(response.data.status == 200){
                const data = response.data.data;
                // this.$store.dispatch('user', data.user);
                toastr.success(response.data.message);
                localStorage.setItem("token",data.token);
                router.push('/admin/dashboard');
            }else{
                toastr.error(response.data.message);
            }
        })
        .catch((error) => {
            if(error.response != undefined){
                if(error.response.status == 422){
                    actions.setErrors(error.response.data.errors);
                }
            }
        })
    };
</script>
<style >
    .login-box {
        -ms-flex-align: center;
        align-items: center;
        background-color: #e9ecef;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        height: 100vh;
        -ms-flex-pack: center;
        justify-content: center;
    }
    .login-card{
        width: 30%;
    }
    .login-box, .register-box{
        width: 100%;
    }
</style>
<template>
    <div class="login-box">
        <div class="login-card card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Admin</b>Login</a>
            </div>
            <div class="card-body">
                <Form @submit="handleSubmit" v-slot:default="{ errors }">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" v-model="form.email" :class="{ 'is-invalid': errors.email }">
                        <span class="invalid-feedback">{{ errors.email }}</span>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" v-model="form.password" :class="{ 'is-invalid': errors.password }">
                        <span class="invalid-feedback">{{ errors.password }}</span>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
