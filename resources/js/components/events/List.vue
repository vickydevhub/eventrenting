<template>
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="col-12 text-center">
                                <label>Don't have an event? <router-link :to="{name:'newEvent'}">Add Event</router-link></label>
                            </div>
                <div class="card shadow sm">
                    <div class="card-body">
                        <h1 class="text-center">Events</h1>
                        <hr/>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'
import Auth from '../../Auth';
export default {
    name:"List Event",
    data(){
        return {
            user:{
                email:"",
                password:""
            },
            validationErrors:{},
            processing:false
        }
    },
    methods:{
        login() {
                axios.post('/login', this.user)
                    .then(({data}) => {
                        Auth.login(data.data.token,data.data.user); //set local storage
                        this.$router.push('/');
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        
    }
}
</script>