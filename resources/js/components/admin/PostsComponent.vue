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
                            <th>Creator</th>
                            <th>Short Description</th>
                            <th>Type</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="post in posts" :key="post.id">
                            <td data-label="Title"><img class="ui mini image" v-bind:src="post.attachments[0].thumb_url"></td>
                            <td data-label="Creator"><a :href="'/admin/posts?search=' + post.user.username"><img class="ui avatar image mr-1" v-bind:src="post.user.profile_pic"> {{post.user.name}}</a></td>
                            <td data-label="Comments">{{post.short_description ? post.short_description : '-' }}</td>
                            <td data-label="Votes"><label class="ui label" v-bind:class="post.is_request ? 'green' : 'yellow'">{{post.is_request ? 'Request' : 'Exchange' }}</label> </td>
                            <td>
                                <a :href="'/post/' + post.id" class="ui button">View</a>
                                <div class="ui red button" @click="deletePost(post.id)">Delete</div>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot v-if="hasMorePage">
                        <tr><th colspan="6">
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
        props: ['postsList'],
        data() {
            return {
                posts: '',
                currentPage: '',
                nextPage: '',
                previousPage: '',
                hasMorePage: '',
                totalUsersCount: '',
                postsShowing: '',
                companies: ''
            }
        },
        mounted() {
            this.currentPage = this.postsList.current_page;
            this.nextPage = this.postsList.next_page_url;
            this.previousPage = this.postsList.prev_page_url;
            this.posts = this.postsList.data;
            this.totalUsersCount = this.postsList.total;
            this.postsShowing = this.postsList.to;
            this.hasMorePage = !!this.postsList.next_page_url || !!this.postsList.prev_page_url;
        },
        methods: {
            markSolved(postId, companyId) {
                NProgress.start();
                axios.put('/api/posts/' + postId, {solved_by: companyId}).then(response => {
                    Vue.$toast.success(response.data.message);
                    window.location.reload();
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error(response.data.message);
                });
            },
            markUnSolved(postId) {
                NProgress.start();
                axios.put('/api/posts/' + postId, {solved_by: ''}).then(response => {
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
            },
            deletePost(postId) {
                axios.delete('/api/posts/' + postId).then(response => {
                    Vue.$toast.success(response.data.message);
                    setTimeout(function(){
                        window.location.reload();
                    }, 1000)
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error(response.data.message);
                });
            }
        }
    }
</script>

<style scoped>

</style>
