<template>
    <div >
        <div class="business-wallet-container">
            <div v-if="(accessRole !== 'redeeming')">
                <div class="ui two column grid mt-5">
                    <div class="sixteen wide tablet eight wide computer column middle aligned">
                        <h3 class="ui header mt-3 request-header mb-1">Active Request Posts</h3>
                    </div>
                    <div class="sixteen wide tablet eight wide computer column text-right res-text-left middle aligned">
                        <div class="ui white buttons shadow requests-filter">
                            <button class="ui active button" @click="getRequests('active')" data-value="active">Active</button>
                            <button class="ui button" @click="getRequests('archived')" data-value="archived">Archived</button>
                        </div>
                    </div>
                </div>

                <div class="ui fluid card">
                    <table class="ui padded fixed striped table" v-if="activeRequests.length > 0">
                        <tbody>
                        <tr v-for="(request, index) in activeRequests" v-bind:class="'request-' + request.id">
                            <td class="middle aligned">
                                <a :href="'/post/' + request.id">
                                    <img class="ui small image d-inline-block" v-bind:src="request.attachments[0].thumb_url">
                                </a>
                            </td>
                            <td class="middle aligned">
                                {{request.short_description}}
                            </td>
                            <td class="middle aligned">
                                {{request.exchanges.length}} Images
                            </td>
                            <td class="middle aligned">{{request.post_time_small}}</td>
                            <td class="middle aligned">
                                <div class="ui labeled button" tabindex="0">
                                    <div class="ui basic impression-button red button text-left">
                                        Applauds
                                    </div>
                                    <a class="ui red basic label impression-count">
                                        {{request.likes_count}}
                                    </a>
                                </div>
                                <div class="ui labeled button mt-1" tabindex="0">
                                    <div class="ui basic impression-button green button text-left">
                                        Shares
                                    </div>
                                    <a class="ui green basic label impression-count">
                                        {{request.shares_count}}
                                    </a>
                                </div>
                                <div class="ui labeled button mt-1" tabindex="0">
                                    <div class="ui basic impression-button blue button text-left">
                                        Clicks
                                    </div>
                                    <a class="ui blue basic label impression-count">
                                        {{request.clicks_count}}
                                    </a>
                                </div>
                            </td>
                            <td class="middle aligned text-right" style="overflow: visible">

                                <div class="ui icon top right pointing dropdown button" v-if="">
                                    <i class="ellipsis vertical icon"></i>
                                    <div class="menu">
                                        <div class="item" data-content="Renew" v-bind:class="request.is_expired ? '' : 'disabled'"><i class="history icon"></i> Renew</div>
                                        <a class="item" data-content="Edit" :href="'?edit=' + request.id"><i class="pencil icon"></i> Edit</a>
                                        <div class="item" @click="getExchanges('active', '', request.id)"><i class="exchange icon"></i> Exchanges</div>
                                        <div class="item" @click="deletePost(request.id, index)"><i class="trash icon"></i> Delete</div>

                                            <ShareNetwork
                                                class="item"
                                                network="facebook"
                                                v-bind:url="baseUrl + ('/post/' + request.id)"
                                                v-bind:title="(request.parent_short_description ? post.parent_short_description : request.content)"
                                                v-bind:description="request.content"
                                            >
                                                <i class="share icon"></i> Share
                                            </ShareNetwork>


                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="content" v-if="activeRequests.length === 0">
                        <h3 class="ui small red header">No requests yet!</h3>
                    </div>
                </div>
            </div>
            <div v-if="role !== 'marketing'">
                <div class="ui two column grid mt-4">
                    <div class="sixteen wide tablet four wide computer column">
                        <h3 class="ui header mt-3 exchange-header">Active Reward Coupons</h3>
                    </div>
                    <div class="sixteen wide tablet twelve wide computer column text-right res-text-left">
                        <div class="ui white buttons shadow exchanges-filter">
                            <button class="ui active button" @click="getExchanges('active')" data-value="active">Active</button>
                            <button class="ui button" @click="getExchanges('redeemed')" data-value="redeemed">Accept Coupon</button>
                        </div>
                        <div class="ui icon white button open-camera shadow ml-2 mr-2">
                            <i class="qrcode icon"></i>
                        </div>
                        <div class="ui icon input search-container">
                            <input class="search-input" type="text" placeholder="Search Coupon" v-model="searchText" v-bind="searchInput" @keyup="initSearchCoupons($event.target.value)">
                            <i class="search icon"></i>
                        </div>
                    </div>
                </div>
                <div class="ui fluid card" id="exchanges">
                    <table class="ui padded fixed striped table" v-if="exchanges.length > 0">
                        <tbody>
                        <tr v-for="exchange in exchanges">
                            <td class="middle aligned">
                                <a :href="'/exchange/' + exchange.id">
                                    <img class="ui small image d-inline-block" v-bind:src="exchange.attachments[0].thumb_url">
                                </a>
                            </td>
                            <td class="middle aligned">
                                {{exchange.user.username}}
                            </td>
                            <td class="middle aligned">
                                {{exchange.parent_short_description}}
                            </td>
                            <td class="middle aligned">
                                {{exchange.received_coupon.coupon}}
                            </td>
                            <td class="middle aligned">
                                <div class="ui labeled button" tabindex="0">
                                    <div class="ui basic impression-button red button text-left">
                                        Applauds
                                    </div>
                                    <a class="ui red basic label impression-count">
                                        {{exchange.likes_count}}
                                    </a>
                                </div>
                                <div class="ui labeled button mt-1" tabindex="0">
                                    <div class="ui basic impression-button green button text-left">
                                        Shares
                                    </div>
                                    <a class="ui green basic label impression-count">
                                        {{exchange.shares_count}}
                                    </a>
                                </div>
                                <div class="ui labeled button mt-1" tabindex="0">
                                    <div class="ui basic impression-button blue button text-left">
                                        Clicks
                                    </div>
                                    <a class="ui blue basic label impression-count">
                                        {{exchange.clicks_count}}
                                    </a>
                                </div>
                            </td>
                            <td class="middle aligned text-right">
                                <a style="width: 150px" class="ui button d-inline-block" :href="'/posts/' + exchange.id + '/medias' ">Download</a>
                                <div style="width: 150px" class="ui green button d-inline-block mt-1" @click="markRedeem(exchange.received_coupon.id)" v-if="!exchange.received_coupon.is_redeemed">Redeem</div>
                                <label class="ui yellow label" v-if="exchange.received_coupon.is_redeemed">Redeemed</label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="content" v-if="exchanges.length === 0">
                        <h3 class="ui small red header">No exchanges yet!</h3>
                    </div>
                </div>

            </div>
            <div class="ui modal qr-code-modal">
                <div class="content">
                    <qrcode-stream :camera="openCamera" @decode="onDecode" @init="onInit">
                        <div class="loader" style="width: 100%; height: 100%; justify-content: center;align-items: center;display: flex">Loading...</div>
                    </qrcode-stream>
                </div>
            </div>

            <div class="ui modal exchanges-modal">
                <div class="header">
                    <h3 class="ui small header">Exchanges</h3>
                </div>
                <div class="content">
                    <table class="ui padded striped table" v-if="coupons.length > 0">
                        <tbody>
                        <tr v-for="coupon in coupons">
                            <td class="middle aligned">
                                <a :href="'/exchange/' + coupon.id">
                                    <img class="ui small image d-inline-block" v-bind:src="coupon.attachments[0].thumb_url">
                                </a>
                            </td>
                            <td class="middle aligned">
                                {{coupon.user.username}}
                            </td>
                            <td class="middle aligned">
                                {{coupon.post.short_description}}
                            </td>
                            <td class="middle aligned">
                                {{coupon.coupon}}
                            </td>
                            <td class="middle aligned">
                                <div class="ui labeled button" tabindex="0">
                                    <div class="ui basic impression-button red button text-left">
                                        Applauds
                                    </div>
                                    <a class="ui red basic label impression-count">
                                        {{coupon.likes_count}}
                                    </a>
                                </div>
                                <div class="ui labeled button mt-1" tabindex="0">
                                    <div class="ui basic impression-button green button text-left">
                                        Shares
                                    </div>
                                    <a class="ui green basic label impression-count">
                                        {{coupon.shares_count}}
                                    </a>
                                </div>
                                <div class="ui labeled button mt-1" tabindex="0">
                                    <div class="ui basic impression-button blue button text-left">
                                        Clicks
                                    </div>
                                    <a class="ui blue basic label impression-count">
                                        {{coupon.clicks_count}}
                                    </a>
                                </div>
                            </td>
                            <td class="middle aligned text-right">
                                <a style="width: 150px" class="ui button d-inline-block" :href="'/posts/' + coupon.exchange.id + '/medias' ">Download</a>
                                <div style="width: 150px" class="ui green button d-inline-block mt-1" @click="markRedeem(coupon.id)" v-if="!coupon.is_redeemed">Redeem</div>
                                <label class="ui yellow label" v-if="coupon.is_redeemed">Redeemed</label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="content" v-if="coupons.length === 0">
                        <h3>No exchanges yet!</h3>
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
    import VueQrcodeReader from "vue-qrcode-reader";
    import VuejsDialog from 'vuejs-dialog';
    import VuejsDialogMixin from 'vuejs-dialog/dist/vuejs-dialog-mixin.min.js'; // only needed in custom components

    // include the default style
    import 'vuejs-dialog/dist/vuejs-dialog.min.css';

    Vue.use(VueToast);
    Vue.use(VueQrcodeReader);
    Vue.use(VuejsDialog);



    let timer = 0;
    export default {
        props: ['accessRole','activeBusinessName'],
        data() {
            return {
                searchedCoupons: '',
                searchInput: '',
                searchText: '',
                openCamera: 'off',
                sectionTitle: 'Active Requests',
                showCoupons: false,
                activeRequests: '',
                expiredRequests: '',
                exchanges: '',
                coupons: '',
                role: '',
                nextRequestPage : '/api/business/posts?page=1',
                nextExchangePage : '/api/business/posts?page=1',
                businessName: '',
                hasMoreReuests: false,
                hasMoreExchanges: false,
                isSearching : false,
                baseUrl : window.location.origin
            }
        },
        mounted() {
            this.role = this.accessRole;
            this.businessName = this.activeBusinessName;
            let $elem = this;
            $(document).on('click', '.open-camera', function(){
                navigator.permissions.query({name: 'camera'}).then( permissionStatus => {
                    
                })
                $elem.openCamera = 'auto';
                $('.qr-code-modal').find('.loader').stop(0).show();
                $('.qr-code-modal').modal({
                    onHide: function(){
                        $('.qr-code-modal').find('.loader').stop(0).hide();
                        $elem.openCamera = 'off';
                    },
                }).modal('show')
            })

            $(document).on('click', '.exchange-btn', function () {
                let postId = $(this).attr('data-post');
                let $modal = $('.exchanges-modal');
                $elem.searchCoupons(postId, true);
                $modal.modal('show');
            });


            this.getRequests();
            this.getExchanges('active');

            $(document).on('click', '.exchanges-filter .button', function() {
                $(document).find('.exchange-header').html($(this).html() + ' Exchanges');
                $('.exchanges-filter .button').removeClass('active');
                $(this).addClass('active');
            })
            $(document).on('click', '.requests-filter .button', function() {
                $(document).find('.request-header').html($(this).html() + ' Requests');
                $('.requests-filter .button').removeClass('active');
                $(this).addClass('active');
            })
            setTimeout(function() {
                $('.ui.dropdown').dropdown();
            }, 1000)

        },
        methods: {
            onInit() {
                $('.qr-code-modal').find('.loader').stop(0).hide();
            },
            onDecode(decodedString) {
                let qrCode = decodedString;
                if(qrCode){
                    this.openCamera = 'off';
                    this.searchText = qrCode;
                    this.searchCoupons(qrCode);
                    $('.qr-code-modal').find('.loader').stop(0).hide();
                }
                $('.qr-code-modal').modal('hide');
            },
            initSearchCoupons(value) {
                let $this = this;
                let filter = $('.exchanges-filter').find('.active.button').attr('data-value');
                clearTimeout(timer);
                timer = setTimeout(function () {
                    $this.getExchanges(filter, value);
                }, 500);

            },
            searchCoupons(value, isPost = false) {
                NProgress.start();

                if(!isPost && value.length < 3){
                    this.showCoupons = false;
                    this.sectionTitle = 'Active Requests';
                    return false;
                }

                let url = '/api/business/coupons?';
                if(isPost){
                    url = url + 'post=' + value;
                }else{
                    url = url + 'search=' +value;
                }

                axios.get(url).then(response => {
                    if(isPost){
                        this.coupons = response.data.data;
                        this.showCoupons = false;
                        this.sectionTitle = 'Active Requests'
                    }
                    else{
                        this.searchedCoupons = response.data.data;
                        this.showCoupons = true;
                        this.sectionTitle = 'Searched Coupons'
                    }

                    NProgress.done();
                }).catch(error => {
                    NProgress.done();
                });
            },
            markRedeem(couponId) {
                let $this = this;
                NProgress.start();
                axios.put('/api/business/coupons/' + couponId).then(response => {
                    this.showCoupons = false;
                    this.sectionTitle = 'Active Requests';
                    $this.getExchanges('active');
                    NProgress.done();
                    Vue.$toast.success(response.data.message);
                }).catch(error => {
                    NProgress.done();
                });
            },
            getRequests(filter = 'active') {
                NProgress.start();
                let url = this.nextRequestPage + '&filter=' + filter + '&type=requests&is_business=true&business=' + this.businessName;
                axios.get(url).then(response => {
                    let data = response.data.data;
                    if(data.next_page_url){
                        this.nextRequestPage = data.next_page_url;
                    }

                    this.hasMoreReuests = data.next_page_url ? true : false;
                    this.activeRequests = data.data;

                    setTimeout(function(){
                        $('.ui.dropdown').dropdown();
                    }, 1000)

                    NProgress.done();
                }).catch(error => {
                    NProgress.done();
                });
            },
            getExchanges(type = 'active', search = '', parentPost = '') {
                NProgress.start();
                let url = this.nextExchangePage + '&filter=' + type + '&type=exchanges&is_business=true&business=' + this.businessName + '&search=' +search + '&parent=' + parentPost;
                axios.get(url).then(response => {
                    let data = response.data.data;
                    if(data.next_page_url){
                        this.nextExchangePage = data.next_page_url;
                    }

                    this.hasMoreExchanges = data.next_page_url ? true : false;
                    this.exchanges = data.data;

                    if(parentPost){
                        $('html, body').animate({
                            scrollTop: $("#exchanges").offset().top
                        }, 2000);
                    }

                    NProgress.done();
                }).catch(error => {
                    NProgress.done();
                });
            },
            deletePost(postId, index) {
                this.$dialog
                    .confirm('Are you sure you want to delete?')
                    .then(function(dialog) {
                        let url = '/api/posts/' + postId;
                        axios.delete(url).then(response => {
                            Vue.$toast.success(response.data.message);
                            setTimeout(function(){
                                NProgress.done();
                                window.location.reload();
                            }, 2000)
                        }).catch(error => {
                            NProgress.done();
                        });
                    })
            },
            handleImpression( postId, action = 'click'){
                axios.post('/api/impressions', {post_id : postId, action: action})
                    .then(response => {
                        
                    }).catch(error => {
                });
            }
        }
    }
</script>

<style scoped>

</style>
