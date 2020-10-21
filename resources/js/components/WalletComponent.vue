<template>
    <div>
        <h3 class="ui header">Active Rewards</h3>
        <h3 class="ui small red header" v-if="activeCoupons.length === 0">
            Active RewardsYou haven’t received any rewards, share your pictures to receive rewards.
        </h3>       
        <div class="ui column grid" v-if="activeCoupons.length > 0">
            <div class="column" >
                <div class="ui stackable three cards">
                    <a v-bind:href="'post/' +coupon.post.id + '#' + coupon.exchange_id " class="ui card" v-for="coupon in activeCoupons" :key="coupon.id" >
                        <div class="content">
                            <div class="coupon-container">
                                <img class="business-logo" v-bind:src="coupon.business.logo">
                                <!--                            <h2>{{coupon.business.name}}</h2>-->
                                <h1>{{coupon.post.short_description}}</h1>
                                <img v-bind:src="coupon.qr_code">
                            </div>
                        </div>
                        <a :href="'/coupons/' + coupon.id + '/pdf'" class="ui bottom attached button">
                            <i class="download icon"></i>
                            Download Pdf
                        </a>
                    </a>
                </div>
            </div>
        </div>
        <div v-if="redeemedCoupons.length > 0">
            <h3 class="ui header orange mt-5 mb-5">Redeemed Coupons</h3>
            <div class="ui grid" v-if="redeemedCoupons.length > 0">
                <div class="column">
                    <div class="ui stackable three cards">
                        <a v-bind:href="'post/' +coupon.post.id + '#' + coupon.exchange_id " class="ui card" v-for="coupon in redeemedCoupons" :key="coupon.id" >
                            <div class="content">
                                <a class="ui orange right ribbon label">Redeemed</a>
                                <div class="coupon-container">
                                    <img class="business-logo" v-bind:src="coupon.business.logo">
                                    <!--                                <h2>{{coupon.business.name}}</h2>-->
                                    <h1>{{coupon.post.short_description}}</h1>
                                    <img v-bind:src="coupon.qr_code">
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import Vue from "vue";

    export default {
        props: ['receivedCoupons'],
        data() {
            return  {
                activeCoupons: '',
                redeemedCoupons: ''
            }
        },
        mounted() {
            this.activeCoupons = this.receivedCoupons.filter((coupon) => {
                return !coupon.is_redeemed;
            });
            this.redeemedCoupons = this.receivedCoupons.filter((coupon) => {
                return coupon.is_redeemed;
            });
        },
        methods: {
            downloadPdf(couponId){
            }
        }
    }
</script>

<style scoped>

</style>
