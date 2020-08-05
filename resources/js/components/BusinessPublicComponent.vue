<template>
    <div>
        <div class="business-banner" :style="applyBannerStyle"></div>
        <div class="ui container position-relative">
            <div class="ui grid">
                <div class="sixteen wide tablet three wide computer column">
                    <div class="business-logo">
                        <img v-bind:src="business.logo">
                    </div>
                </div>
                <div class="sixteen wide tablet seven wide computer column">
                    <h3>{{business.name}}</h3>
                    <p>{{business.requests_count}} Requests & {{business.exchanges_count}} Exchanges</p>
                </div>
                <div class="sixteen wide tablet six wide computer column">
<!--                    <button class="ui green button">Request to Join</button>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['currentUser', 'currentBusiness'],
        data() {
            return {
                user: '',
                business: ''
            }
        },
        mounted() {
            this.user = this.currentUser;
            this.business = this.currentBusiness;
        },
        methods: {
        },
        computed : {
            applyBannerStyle() {
                let defaultbanner = '/assets/images/business-banner.jpg';
                return {
                    'background' : 'url(' + (this.business.banner ? this.business.banner : defaultbanner) +')'
                }
            }
        }
    }
</script>

<style scoped>

</style>
