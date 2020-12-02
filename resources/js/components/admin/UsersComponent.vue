<template>
<div>
    <div class="ui grid">
        <div class="column">
            <div class="ui fluid card">
                <div class="content">
                    <div class="ui icon input right floated">
                        <input type="text" placeholder="Search..." v-on:keyup.enter="searchUsers($event.target.value)">
                        <i class="search icon"></i>
                    </div>
                </div>
                <table class="ui striped padded table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Member Since</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(user, index) in users" :key="index">
                        <td data-label=""><img class="ui md avatar" v-bind:src="user.profile_pic"></td>
                        <td data-label="Name">{{user.name}}</td>
                        <td data-label="Email">{{user.email}}</td>
                        <td data-label="Location"><label class="ui label" v-bind:class="user.is_blocked ? 'red' : 'green'">{{user.is_blocked ? 'Blocked' : 'Active'}}</label></td>
                        <td data-label="Member Since">{{user.member_since}}</td>
                        <td data-label="">
                            <button class="ui red button" v-if="!user.is_blocked" @click="updateUser(user.id, 1)">Suspend</button>
                            <button class="ui green button" v-if="user.is_blocked" @click="updateUser(user.id, 0)">Restore</button>
                            <button class="ui red button" v-if="user.is_blocked" @click="deleteUser(user.id)">Delete</button>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot v-if="hasMorePage">
                    <tr><th colspan="5">
                        <div class="ui right floated pagination menu">
                            <a :href="previousPage" v-bind:class="{'disabled': !previousPage}" class="icon item">
                                <i class="left chevron icon"></i>
                            </a>
                            <a :href="nextPage" v-bind:class="{'disabled': !nextPage}" class="icon item">
                                <i class="right chevron icon"></i>
                            </a>
                        </div>
                    </th>
                    </tr></tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import $ from 'jquery';
    import Vue from 'vue';
    import VueToast from 'vue-toast-notification';
    import 'vue-toast-notification/dist/theme-default.css';
    import NProgress from 'nprogress';
    import 'nprogress/nprogress.css';
    Vue.use(VueToast);

    export default {
        props: ['usersList'],
        data() {
            return {
                users: '',
                currentPage: '',
                nextPage: '',
                previousPage: '',
                hasMorePage: '',
                totalUsersCount: '',
                usersShowing: ''
            }
        },
        mounted() {
            this.currentPage = this.usersList.current_page;
            this.nextPage = this.usersList.next_page_url;
            this.previousPage = this.usersList.prev_page_url;
            this.users = this.usersList.data;
            this.totalUsersCount = this.usersList.total;
            this.hasMorePage = !!this.usersList.next_page_url;
            this.usersShowing = this.usersList.to;
        },
        methods: {
            updateUser(userId, suspendAction) {
                NProgress.start();
                axios.post('/api/users/' + userId, {is_blocked: suspendAction}).then(response => {
                    Vue.$toast.success(response.data.message);
                    window.location.reload();
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error(response.data.message);
                });
            },
            deleteUser(userId) {
                NProgress.start();
                axios.delete('/api/users/' + userId).then(response => {
                    Vue.$toast.success(response.data.message);
                    window.location.reload();
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error(response.data.message);
                });
            },
            searchUsers(value) {
                window.history.pushState('search', 'Searching '+ value, '?search=' + value);
                window.location.reload();
            }
        }
    }
</script>

<style scoped>

</style>
