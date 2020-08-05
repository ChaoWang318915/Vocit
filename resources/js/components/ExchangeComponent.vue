<template>
    <div>
        <div class="ui two column grid ">
            <div class="sixteen wide tablet ten wide computer column">
                <div class="ui fluid sticky card sticky-card">
                    <div class="content">
                        <img width="100%" v-bind:src="imageUrl">
                    </div>
                </div>
            </div>
            <div class="sixteen wide tablet six wide computer column">
                <div class="post-header">
                    <a :href="isExchange ? '#' : '/' + business.subdomain" class="block image-block">
                        <img class="ui large avatar image" v-bind:src="isExchange ? post.user.profile_pic : business.logo">
                    </a>
                    <div class="block">
                        <div class="w-100 d-block header-top">
                            <div class="left floated">
                                <a :href="isExchange ? '#' : '/' + business.subdomain" class="ui header m-0">{{isExchange ? post.user.username : business.name}}</a>
                                <p class="text-fade">{{post.post_time}}</p>
                            </div>
                        </div>
                        <p v-if="!isExchange" class="w-100 d-block header-bottom mb-0 position-relative float-left" style="white-space: pre-line">
                            {{post.short_description ? post.short_description : 'Short description not available'}}
                        </p>
                        <p v-if="isExchange" class="w-100 d-block header-bottom mb-0 position-relative float-left" style="white-space: pre-line">
                            {{post.parent_short_description ? (post.parent_short_description).replace('Get', 'Received'): 'Short description not available'}}
                        </p>
                        <h3 v-if="!isExchange" class="ui small header mt-3 mb-0 position-relative w-100 d-block float-left">Rules:</h3>
                        <p v-if="!isExchange" class="w-100 d-block header-bottom position-relative float-left" style="white-space: pre-line; margin-top: -15px !important;">
                            {{post.content}}
                        </p>
                        <label v-if="!isExchange" v-bind:class="post.is_expired ? 'text-danger' : ''">Expires In: {{!post.is_expired ? post.expiry_time : 'Expired'}}</label>
                        <div class="ui tiny progress post-progress" v-bind:class="post.expire_color" v-if="!isExchange && !post.is_expired" v-bind:data-value="post.expire_percentage" data-total="100">
                            <div class="bar"></div>
                        </div>

                        <div class="share-area d-inline-block mt-4" v-if="isExchange" @click="handleImpression(post.id, 'share')">
                            <ShareNetwork
                                network="facebook"
                                v-bind:url="shareableUrl"
                                v-bind:title="(post.is_request ? post.short_description : post.parent_short_description)"
                                v-bind:description="post.content"
                            >
                                <button class="ui basic icon orange round button mt-2"><i class="share icon"></i></button>
                            </ShareNetwork>
                        </div>

                        <div class="ui icon basic round green button like-btn mt-2" v-if="isExchange" v-bind:class="post.is_liked ? 'clapped' : ''" @click="handleImpression(post.id, 'like')">
                            <span class="likes-count">{{post.likes_count}}</span> <span class="impression-icon"></span>
                        </div>

                        <div class="w-100 mt-2" v-if="!isExchange && !post.is_expired && !isSandboxUser">
                            <div class="ui btn-primary-outline button upload-for-reward mt-2" @click="checkIfLoggedIn()">
                                <i class="camera icon"></i>
                                Upload for Reward
                            </div>
                            <span v-show="filesCount">{{filesCount}} Image is being uploaded</span>
                            <input ref="file" v-on:change="handleFilesChange()" name="images" type="file" class="d-none image-input" accept="image/x-png,image/gif,image/jpeg" multiple max="2">
                            <div class="share-area d-inline-block" @click="handleImpression(post.id, 'share')">
                                <ShareNetwork
                                    network="facebook"
                                    v-bind:url="shareableUrl"
                                    v-bind:title="(post.is_request ? post.short_description : post.parent_short_description)"
                                    v-bind:description="post.content"
                                >
                                    <button class="ui basic icon orange round button mt-2"><i class="share icon"></i></button>
                                </ShareNetwork>
                            </div>
                            <div class="ui icon basic round green button like-btn mt-2" v-bind:class="post.is_liked ? 'clapped' : ''" @click="handleImpression(post.id, 'like')">
                                <span class="likes-count">{{post.likes_count}}</span> <span class="impression-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ui form mt-5" v-if="isExchange && !sandboxUser">
                    <div class="field">
                        <textarea rows="2" placeholder="Post a comment here" v-model="parentComment"></textarea>
                        <button class="ui compact button mt-2 comment-post-btn" @click="postComment(post.id)">POST</button>
                    </div>
                </div>

                <div class="ui comments position-relative float-left mt-5 w-100" v-if="exchanges.length">
                    <h3 class="ui header">Exchanges</h3>
                    <div class="comment" v-for="exchange in exchanges" :id="exchange.id">
                        <a class="avatar">
                            <img v-bind:src="exchange.user.profile_pic">
                        </a>
                        <div class="content">
                            <a class="author">{{exchange.user.username ? exchange.user.username : exchange.user.name }}</a>
                            <div class="metadata">
                                <span class="date">{{exchange.post_time}}</span>
                            </div>
                            <div class="text">
                                <div v-if="exchange.is_image" class="ui tiny images">
                                    <img v-for="attachment in exchange.attachments" class="ui image" v-bind:src="attachment.thumb_url">
                                </div>
                                <div v-if="isExchange && !exchange.is_image">
                                    {{exchange.content}}
                                </div>
                            </div>
                            <div class="actions" v-if="isExchange && !sandboxUser">
                                <a class="reply comment-reply-btn">Reply</a>
                                <div class="ui form mt-3 comment-area" style="display: none">
                                    <div class="field">
                                        <textarea rows="2" placeholder="Post a comment here" v-model="content"></textarea>
                                        <button class="ui compact button mt-2 comment-post-btn" @click="postComment(exchange.id)">POST</button>
                                        <a class="comment-cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="comments" v-if="exchange.exchanges.length > 0">
                            <div class="comment" v-for="comment in exchange.exchanges">
                                <a class="avatar">
                                    <img v-bind:src="comment.user.profile_pic">
                                </a>
                                <div class="content">
                                    <a class="author">{{comment.user.username}}</a>
                                    <div class="metadata">
                                        <span class="date">{{comment.post_time}}</span>
                                    </div>
                                    <div class="text">
                                        {{comment.content}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    import VueSocialSharing from 'vue-social-sharing'

    Vue.use(VueSocialSharing);
    Vue.use(VueToast);

    export default {
        props: ['activePost', 'isLoggedIn', 'exchangePost', 'sandboxUser', 'shareUrl', 'canShare'],
        data (){
            return {
                post: '',
                imageUrl: '',
                business: '',
                exchanges: '',
                filesCount: 0,
                images : '',
                content: '',
                isExchange: false,
                parentComment: '',
                isSandboxUser: false,
                shareableUrl : this.shareUrl,
                canSharePost : this.canShare
            }
        },
        mounted() {
            this.post = this.activePost;
            this.imageUrl = this.activePost.attachments[0].thumb_url;
            this.business = this.activePost.business;
            this.exchanges = this.activePost.exchanges;
            let locationHash = window.location.hash;
            this.isExchange = this.exchangePost;
            this.isSandboxUser = this.sandboxUser;
            console.log(this.post);
            console.log('ddd', (this.post.is_request ? this.post.short_description : this.post.parent_short_description))
            if(locationHash){
                setTimeout(function(){
                    console.log($(locationHash));
                    $('html, body').animate({
                        scrollTop: (parseInt($(locationHash).offset().top) - 95)
                    }, 1000);
                },500)
            }

            this.handleImpression(this.post.id)
        },
        methods: {
            handleFilesChange() {
                this.images = this.$refs.file.files;
                this.filesCount = this.$refs.file.files.length;

                this.initExchange();
            },
            postComment(postId) {
                if(!this.checkIfLoggedIn()){
                    return false;
                }

                if(this.content.length === 0 && this.parentComment.length === 0 ){
                    Vue.$toast.error('Write something to post');
                    return false;
                }

                this.initExchange(postId);
            },
            initExchange(postId = '') {
                let formData = new FormData();
                let images = this.images;
                if(this.filesCount > 0){
                    for( var i = 0; i < images.length; i++ ){
                        let file = images[i];
                        formData.append('images[' + i + ']', file);
                    }
                }

                formData.append('parent_id', (postId ? postId : this.post.id));
                formData.append('origin_post', this.post.id);
                formData.append('is_draft', 0);
                formData.append('business_id', this.post.business_id);
                formData.append('content', this.parentComment.length > 0 ? this.parentComment : this.content);

                NProgress.start();
                axios.post('/api/exchange', formData).then(response => {
                    console.log(response);
                    this.content = '';
                    this.parentComment = '';
                    this.filesCount = '';
                    this.images = '';

                    $(document).find('.comment-area').stop(0).slideUp('fast');
                    $(document).find('.comment-reply-btn').stop(0).slideDown('fast');

                    this.exchanges = response.data.data.exchanges;

                    NProgress.done();
                    Vue.$toast.success(response.data.message);
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    this.formError = response.data.message
                });
            },
            checkIfLoggedIn() {
                let isLoggedIn = this.isLoggedIn;
                if(!isLoggedIn){
                    window.location.href = '/login'
                }
                return true;
            },
            handleImpression(postId, action = 'click'){
                axios.post('/api/impressions', {post_id : postId, action: action})
                    .then(response => {
                        if(action == 'like'){
                            if($(document).find('.like-btn').hasClass('clapped')){
                                $(document).find('.like-btn').removeClass('clapped');
                                $(document).find('.likes-count').html(parseInt( $(document).find('.likes-count').html()) - 1)
                            }
                            else{
                                $(document).find('.like-btn').addClass('clapped');
                                $(document).find('.likes-count').html(parseInt( $(document).find('.likes-count').html()) + 1)
                            }
                        }
                    }).catch(error => {
                });
            }
        }
    }
</script>

<style scoped>

</style>
