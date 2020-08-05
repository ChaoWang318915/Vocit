<template>
    <div v-if="(role === 'admin')">
        <div class="ui green message mb-4">
            <div class="header" >
                You have {{currentPostLimit}} {{activeBusiness.payments_count > 0 ? '' : 'free'}} posts in the <b>Posts and Coupons</b> section
            </div>
        </div>
        <div class="ui two stackable cards mt-4">
            <div class="ui card payment-plan">
                <div class="content"><h3>Subscription</h3></div>
                <div class="content" v-if="!subscription"><h3>$10/month</h3></div>
                <div class="content" v-if="!subscription">
                    <ul class="ui list">
                        <li>3 Free posts each month</li>
                        <li>Dedicated business page</li>
                        <li>30 days recurring payment</li>
                    </ul>
                </div>
                <a v-if="!subscription" href="/payment?package=Pro&quantity=1" class="ui bottom primary attached button">
                    Buy
                    <i class="chevron right icon"></i>
                </a>
<!--                Active Subscription-->
                <div class="content" v-if="(subscription && subscription.package.length > 0)">
                    <label class="ui green label">Active</label>
                </div>
                <div class="content" v-if="(subscription && subscription.package.length > 0)">
                    <ul class="ui list">
                        <li>Current Plan: {{subscription ? subscription.package : 'N/A'}}</li>
                        <li>Renewal Interval: 30 days</li>
                        <li>Amount: ${{subscription ? subscription.amount: 'N/A'}}</li>
                        <li>Usage Limit: {{plan ? plan.posts : 0}} posts</li>
                    </ul>
                </div>

                <div v-if="(subscription && subscription.package.length > 0)" class="ui bottom red attached button" @click="cancelSubscription()">
                    Cancel
                    <i class="chevron right icon"></i>
                </div>

            </div>
            <div class="ui card payment-plan">
                <div class="content"><h3>Purchase More Posts</h3></div>
                <div class="content"><h3>$7/post</h3></div>
                <div class="content">
                    <ul class="ui list">
                        <li>Post extra with this package.</li>
                    </ul>
                    <div class="ui from">
                        <div class="inline field">
                            <label>Quantity: </label>
                            <select class="ui dropdown quantity-select" @change="setQuantity($event)">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                </div>
                <a :href="'/payment?package=Single&quantity=' + quantity" class="ui primary bottom attached button">
                    Buy
                    <i class="chevron right icon"></i>
                </a>
            </div>
        </div>
<!--        <div class="ui two column stackable grid mb-3">-->
<!--            <div class="column">-->
<!--                <div class="ui small header">-->
<!--                    Purchase more posts-->
<!--                </div>-->
<!--                <div class="ui fluid card payment-plan">-->
<!--                    <div class="content"><h3>$7/post</h3></div>-->
<!--                    <div class="content">-->
<!--                        <ul class="ui list">-->
<!--                            <li>Post extra with this package.</li>-->
<!--                        </ul>-->
<!--                        <div class="ui from">-->
<!--                            <div class="inline field">-->
<!--                                <label>Quantity: </label>-->
<!--                                <select class="ui dropdown quantity-select" @change="setQuantity($event)">-->
<!--                                    <option value="1">1</option>-->
<!--                                    <option value="2">2</option>-->
<!--                                    <option value="3">3</option>-->
<!--                                    <option value="4">4</option>-->
<!--                                    <option value="5">5</option>-->
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <a :href="'/payment?package=Single&quantity=' + quantity" class="ui primary bottom attached button">-->
<!--                        Buy-->
<!--                        <i class="chevron right icon"></i>-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!--&lt;!&ndash;            <div class="column" v-if="!hasActiveSubscription">&ndash;&gt;-->
<!--            <div class="column">-->
<!--                <div class="ui small header">-->
<!--                    Activate your monthly Subscription-->
<!--                </div>-->

<!--                <div class="ui fluid card payment-plan">-->
<!--                    <div class="content"><h3>$10/month</h3></div>-->
<!--                    <div class="content">-->
<!--                        <ul class="ui list">-->
<!--                            <li>3 Free posts each month</li>-->
<!--                            <li>Dedicated business page</li>-->
<!--                            <li>30 days recurring payment</li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                    <a href="/payment?package=Pro&quantity=1" class="ui bottom primary attached button">-->
<!--                        Buy-->
<!--                        <i class="chevron right icon"></i>-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="column">-->
<!--                <div class="ui small header">-->
<!--                    Activate your monthly Subscription-->
<!--                </div>-->

<!--                <div class="ui fluid card payment-plan">-->
<!--                    <div class="content"><h3>$10/month</h3></div>-->
<!--                    <div class="content">-->
<!--                        <ul class="ui list">-->
<!--                            <li>3 Free posts each month</li>-->
<!--                            <li>Dedicated business page</li>-->
<!--                            <li>30 days recurring payment</li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                    <a href="/payment?package=Pro&quantity=1" class="ui bottom primary attached button">-->
<!--                        Buy-->
<!--                        <i class="chevron right icon"></i>-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</template>

<script>
    import $ from "jquery";
    import Vue from "vue";
    import NProgress from 'nprogress';
    import 'nprogress/nprogress.css';

    export default {
        props: [
            'paymentPlans',
            'hasSubscription',
            'postLimit',
            'accessRole',
            'business'
        ],
        data() {
            return {
                hasActiveSubscription: false,
                paymentPackages: '',
                quantity : 1,
                currentPostLimit : 0,
                role: '',
                activeBusiness: '',
                subscription: ''
            }
        },
        mounted() {
            this.hasActiveSubscription = this.hasSubscription;
            this.currentPostLimit = this.postLimit;
            this.paymentPackages = this.paymentPlans;
            this.role = this.accessRole;
            this.activeBusiness = this.business;
            this.subscription = this.business.subscription;
        },
        methods: {
            setQuantity($this) {
                this.quantity = $($this.target).val()
            },
            cancelSubscription() {
                NProgress.start();
                let formData = {};
                axios.post('/api/subscription/cancel', formData).then(response => {
                    Vue.$toast.success(response.data.message);
                    setTimeout(function(){
                        NProgress.done();
                        window.location.href = ''
                    }, 2000)

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
