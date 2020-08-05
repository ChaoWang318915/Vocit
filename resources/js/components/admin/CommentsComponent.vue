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
                        <th>User</th>
                        <th>Comment</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="comment in comments">
                        <td data-label=""><img class="ui md avatar" v-bind:src="comment.user.profile_pic"> {{comment.user.username}}</td>
                        <td data-label="Name">{{comment.content}}</td>
                        <td data-label="Member Since">{{comment.post_time}}</td>
                        <td class="text-right">
                            <button class="ui red button" @click="deleteComment(comment.id)">Delete</button>
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
        props: ['commentsList'],
        data() {
            return {
                comments: '',
                currentPage: '',
                nextPage: '',
                previousPage: '',
                hasMorePage: '',
                totalUsersCount: '',
                usersShowing: ''
            }
        },
        mounted() {
            this.currentPage = this.commentsList.current_page;
            this.nextPage = this.commentsList.next_page_url;
            this.previousPage = this.commentsList.prev_page_url;
            this.comments = this.commentsList.data;
            this.totalUsersCount = this.commentsList.total;
            this.hasMorePage = !!this.commentsList.next_page_url;
            this.usersShowing = this.commentsList.to;
        },
        methods: {
            deleteComment(postId) {
                NProgress.start();
                axios.delete('/api/posts/' + postId).then(response => {
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
