<style scoped>
img.avatar{
    width: 4em !important;
    height: 4em !important;
    border-radius: 500rem !important;
 }
 .logo_title{
     font-size: 15px;
 }
</style>
<template>
    <div @scroll="handleScroll()">
        <div class="masonry-grid">
            <a :href=" (post.is_request ? ('/post/' + post.id) : ('/exchange/' + post.id))" v-for="post in items" class="ui card grid-item" v-if="post.is_displayable">
                <div v-if="post.is_request" class="content">
                    <div class="ui two column grid">                      
                        <span class="wide text-right">
                            Request from <a :href="'/' + post.business.subdomain" class="logo_title">{{post.business.name}}</a>
                            <a :href="'/' + post.business.subdomain"><img class="ui avatar image ml-1" v-bind:src="post.business.logo"></a>
                        </span>
                    </div>
                </div>

                <div v-else class="content">
                    <div class="ui two column grid">
                        <div class="wide">                           
                            <a :href="'/' + post.business.subdomain"><img class="ui xs avatar image ml-1" v-bind:src="post.business.logo"></a>
                            <a :href="'/' + post.business.subdomain" class="business-popup-btn logo_title">{{post.business.name}}</a>
                        </div>                        
                    </div>
                </div>

                <div class="image">
                    <div class="ui placeholder image-placeholder">
                        <div class="square image"></div>
                    </div>
                    <img v-bind:src="post.attachments[0].lg_url" class="ui wireframe image d-none" @load="imageLoaded($event)">
<!--                    <template v-show="post.request_type === 2">-->
<!--                        <img class="business business-logo" :src="post.business.logo" />-->
<!--                        <p class="business business-name">{{ post.business.name }}</p>-->
<!--                    </template>-->
                </div>

                <div v-if="!post.is_request" class="content">
                    <p>{{post.user.username}} {{post.parent_short_description ? (post.parent_short_description).replace('Get', 'received') : ''}}</p>
                    <div class="text-sm text-fade">View all comments ({{post.comments_count}})</div>
                    <div class="ui two column grid comment-section">
                        <div class="ten wide column">
                            <span v-if="!user.id">Login to comment</span>
                            <span v-if="user.id"><img  class="ui avatar image" v-bind:src="user.profile_pic"> {{user.username}} <span class="text-fade">comment here...</span></span>
                        </div>
                        <div class="six wide column text-right">
                            <button class="ui round icon button">
                                <i class="plus icon"></i>
                            </button>
                            <div class="d-inline-block" @click="$event.preventDefault()">
                                <ShareNetwork
                                    network="facebook"
                                    v-bind:url="(baseUrl + ((post.is_request ? '/post/' : '/exchange/')) + post.id)"
                                    v-bind:title="(post.is_request ? post.short_description : post.parent_short_description)"
                                    v-bind:description="post.content"  @close="closeSocial"
                                >
                                    <button class="ui round icon share-btn button">
                                        <i class="share icon"></i>
                                    </button>
                                </ShareNetwork>
                            </div>

<!--                            <button class="ui round icon button impression-btn" @click="handleImpression($event, post.id, 'like')">-->
<!--                                <i v-bind:class="post.is_liked ? 'heart' : 'heart outline'" class="icon"></i>-->
<!--                            </button>-->
                            <button class="ui round icon impression-btn button" v-bind:class="post.is_liked ? 'clapped' : ''" @click="handleImpression($event, post.id, 'like')">
                                <span class="likes-count">{{post.likes_count}}</span> <span class="impression-icon"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else class="content">
                    <label v-if="!post.is_expired">Expires In: {{post.expiry_time}}</label>
                    <div class="ui tiny progress post-progress" v-bind:class="post.expire_color" v-if="!post.is_expired" v-bind:data-value="post.expire_percentage" data-total="100">
                        <div class="bar"></div>
                    </div>
                    <div class="ui two column grid">
                        <div class="ten wide column">
                            <p class="post-rule">{{post.short_description ? post.short_description : post.content}}</p>
                            <a href="" class="text-sm text-fade">Click here to read more</a>
                        </div>
                        <div class="six wide column text-right">
                            <button class="ui round icon button">
                                <i class="up arrow icon"></i>
                            </button>
                            <div class="d-inline-block" @click="$event.preventDefault()">
                                <ShareNetwork
                                    network="facebook"
                                    v-bind:url="(baseUrl + ((post.is_request ? '/post/' : '/exchange/')) + post.id)"
                                    v-bind:title="(post.is_request ? post.short_description : post.parent_short_description)"
                                    v-bind:description="post.content"
                                >
                                    <button class="ui round icon share-btn button">
                                        <i class="share icon"></i>
                                    </button>
                                </ShareNetwork>
                            </div>
                            <button class="ui round icon impression-btn button" v-bind:class="post.is_liked ? 'clapped' : ''" @click="handleImpression($event, post.id, 'like')">
                                <span class="likes-count">{{post.likes_count}}</span> <span class="impression-icon"></span>
                            </button>
<!--                            <button class="ui round icon button impression-btn" @click="handleImpression($event, post.id, 'like')">-->
<!--                                <i v-bind:class="post.is_liked ? 'heart' : 'heart outline'" class="icon"></i>-->
<!--                            </button>-->
<!--                            <button class="ui round icon button impression-btn" @click="handleImpression($event, post.id, 'like')">-->
<!--                                24 <i v-bind:class="post.is_liked ? 'heart' : 'heart outline'" class="icon"></i>-->
<!--                            </button>-->
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="ui grid mt-5 mb-5" v-show="contentLoading">
            <div class="column posts-loader">
                <div class="ui active inverted dimmer">
                    <div class="ui text loader">Loading</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery';
    import Vue from 'vue'

    export default {
        props: ['posts', 'currentUser', 'pageParams'],
        data: function() {
            return {
                items: null,
                user: '',
                hasMorePage: false,
                nextPage: '',
                loadedViaWeb: true,
                queryParams: null,
                currentPage : 1,
                contentLoading: false,
                baseUrl : window.location.origin
            }
        },
        mounted() {
            this.items = this.posts.data;
            this.user = this.currentUser;
            this.nextPage = this.posts.next_page_url;
            this.hasMorePage = this.posts.next_page_url;
            this.queryParams = this.pageParams;
            this.currentPage = this.posts.current_page;
            let $elem = this;
            $(window).on('scroll', function(){
                var nearToBottom = 100;
                if ($elem.hasMorePage && !$elem.contentLoading && ($(window).scrollTop() + $(window).height() >
                    $(document).height() - nearToBottom))
                {
                    $elem.contentLoading = true;
                    $elem.loadNextPosts();
                }
            })
        },
        methods: {
            closeSocial(){
                console.log("Close")
            },
            generatePageUrl(){
                if (this.hasMorePage) {
                    if(this.pageParams && this.pageParams.type == 'business'){
                        let pagenumber = parseInt(this.currentPage) + 1;
                        let url = '/api/posts?page=' + pagenumber + '&is_business=true&business=' + this.pageParams.value;
                        return url;
                    }
                    else{
                        let pagenumber = parseInt(this.currentPage) + 1;
                        let url = '/api/posts?page=' + pagenumber + '&type=' + this.queryParams['type'];
                        if(this.queryParams['value']){
                            url = url + '&value=' + this.queryParams['value'];
                        }

                        return url;
                    }

                }

                return null;
            },
            loadNextPosts() {
                let url = this.generatePageUrl();
                if(url){
                    axios.get(url)
                        .then(response => {
                            let data = response.data.data;
                            this.contentLoading = false;

                            let $list = this.items;
                            data.data.map(function (item) {
                                $list.push(item);
                            });

                            this.currentPage = data.current_page;
                            this.hasMorePage = !!data.next_page_url;
                            this.nextPage = this.next_page_url;
                        }).catch(error => {
                    });
                }
                else{
                    this.contentLoading = false;
                }
            },
            handleScroll() {
                console.log('scroll');
            },
            imageLoaded($event){
                $($event.target).removeClass('d-none');
                $($event.target).parent().find('.image-placeholder').remove()
            },
            handleImpression($event, postId, action){
                $event.preventDefault();
                let $elem = $($event.target);
                axios.post('/api/impressions', {post_id : postId, action: action})
                    .then(response => {
                        if($elem.parents('.column').find('.impression-btn').hasClass('clapped')){
                            $elem.parents('.column').find('.impression-btn').removeClass('clapped');
                            $elem.parents('.column').find('.likes-count').html(parseInt($elem.parents('.column').find('.likes-count').html()) - 1)
                        }
                        else{
                            $elem.parents('.column').find('.impression-btn').addClass('clapped');
                            $elem.parents('.column').find('.likes-count').html(parseInt($elem.parents('.column').find('.likes-count').html()) + 1)
                        }
                    }).catch(error => {
                });
            }
        }
    }
</script>

<style scoped>
.business {
    position: absolute;
    z-index: 10;
}
.business-logo {
    top: 10px;
    left: 10px;
    width: 50px !important;
    height: 50px !important;
    border: 2px solid #eee !important;
    -webkit-border-radius: 50% !important;
    -moz-border-radius: 50% !important;
    border-radius: 50% !important;
}
.business-name {
    bottom: 10px;
    right: 5px;
    padding: 10px 0 10px 20px;
    -webkit-border-radius: 4px;
    font-family: fantasy;
    font-weight:bold;
    -moz-border-radius: 4px;
    /*border-radius: 4px;*/
    /*border: 2px solid #F26421 ;*/
    color: #F26421;
    font-size: 18px;
    /*background-color: rgba(255, 255, 255, 0.75);*/
}
</style>
