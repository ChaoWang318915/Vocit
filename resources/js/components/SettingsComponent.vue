<template>
    <div>
<!--        <h3 class="ui header mt-2">Switch Businesses</h3>-->
<!--        <div class="ui stackable four cards mt-4">-->
<!--            <a :href="'/'+ business.subdomain + '/switch'" class="ui card" v-for="business in businesses">-->
<!--                <div class="image">-->
<!--                    <a class="ui right green corner label">-->
<!--                        <i class="check icon"></i>-->
<!--                    </a>-->
<!--                    <img v-bind:src="business.logo">-->
<!--                </div>-->
<!--            </a>-->
<!--        </div>-->

        <h3 class="ui header mt-4" v-if="(role === 'admin')">Associates – Access to Business Account</h3>
        <div class="ui fluid card mt-4" v-if="(role === 'admin')">
            <div class="content">
                <div class="ui action input right floated">
                    <select class="ui dropdown quantity-select" v-model="type">
                        <option value="1">Admin</option>
                        <option value="2">Marketing</option>
                        <option value="3">Redeeming</option>                        
                    </select>
                    <input class="shadow-none bg-gray" type="text" placeholder="Email Address" v-model="inviteEmail">
                    <button class="ui green button" @click="inviteUser()">Invite</button>
                </div>
            </div>
            <table class="ui padded striped table">
                <tbody>
                <tr v-for="member in members" :key="member.id">
                    <td class="middle aligned">
                        <img class="ui avatar d-inline-block" v-bind:src="member.user.profile_pic">
                    </td>
                    <td class="middle aligned" data-label="Name">{{member.user.name}}</td>
                    <td class="middle aligned" data-label="Name">{{member.user.email}}</td>
                    <td class="middle aligned" data-label="Name">{{member.role}}</td>
                    <td class="middle aligned text-right">
                        <label class="ui orange label" v-if="!member.is_joined">Invited</label>
                        <label class="ui green label" v-if="member.is_joined">Active</label>
                        <!--                            <button class="ui red button d-inline-block" v-if="member.is_joined" @click="suspendUser()">Suspend</button>-->
                    </td>
                    <td>
                        <div class="ui button compact red" @click="removeUser(member.user_id)" v-if="role === 'admin'">Remove</div>
                        <div class="ui icon top left dropdown pointing compact button mt-2">
                            Change Role
                            <div class="menu">
                                <div class="item" @click="changeRole(member.user_id, 'admin')">Admin</div>
                                <div class="item" @click="changeRole(member.user_id, 'marketing')">Marketing</div>
                                <div class="item" @click="changeRole(member.user_id, 'redeeming')">Redeeming</div>
                            </div>
                        </div>
<!--                        <button class="ui compact button">Change Role</button>-->
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import $ from "jquery";
    import Vue from 'vue';
    import VueToast from 'vue-toast-notification';
    import 'vue-toast-notification/dist/theme-default.css';
    import NProgress from 'nprogress';
    import 'nprogress/nprogress.css';
    import VuejsDialog from 'vuejs-dialog';
    import VuejsDialogMixin from 'vuejs-dialog/dist/vuejs-dialog-mixin.min.js'; // only needed in custom components

    // include the default style
    import 'vuejs-dialog/dist/vuejs-dialog.min.css';

    Vue.use(VueToast);
    Vue.use(VuejsDialog);

    export default {
       props: ['memberList', 'associatedBusiness', 'accessRole'],
        data () {
           return {
               members : '',
               inviteEmail: '',
               businesses: '',
               role: '',
               type:1,

           }
        },
        mounted() {
           this.members = this.memberList;
           this.businesses = this.associatedBusiness;
           this.role = this.accessRole
        },
        methods: {          
            inviteUser() {
                if(this.inviteEmail.length === 0){
                    Vue.$toast.error('Enter valid email address');
                    return false;
                }
                let formData = {
                    email: this.inviteEmail,
                    role:this.type
                }
                NProgress.start();
                axios.post('/api/business/invite', formData).then(response => {
                    this.inviteEmail = '';
                    NProgress.done();
                    this.members = response.data.data;
                    Vue.$toast.success(response.data.message);
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error( response.data.message);
                });
            },
            suspendUser() {

            },
            changeRole(userId, role) {
                NProgress.start();
                axios.put('/api/users/'+ userId +'/role', {role: role}).then(response => {
                    this.members = response.data.data;
                    Vue.$toast.success(response.data.message);
                    NProgress.done();
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error( response.data.message);
                });
            },
            removeUser(userId) {
                let $this =  this;
                this.$dialog
                    .confirm('Are you sure you want to delete?')
                    .then(function(dialog) {
                        NProgress.start();
                        axios.delete('/api/users/'+ userId +'/remove').then(res => {
                            $this.members = res.data.data;
                            Vue.$toast.success(res.data.message);
                            NProgress.done();
                        }).catch(error => {
                            NProgress.done();
                            let response = error.response;
                            Vue.$toast.error( response.data.message);
                        });
                    })

            }
        }
    }
</script>

<style scoped>

</style>
