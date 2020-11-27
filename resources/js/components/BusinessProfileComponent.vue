<template>
    <div>
        <div>
            <div class="w-100 business-banner-wrapper">
                <a :href="'/' + businessDomain">
                    <img class="ui fluid image" v-bind:src="(subscription && subscription.package.length > 0) ? (businessBanner ? businessBanner : '/assets/images/subscribe-banner.jpg') : '/assets/images/subscribe-banner.jpg'">
                </a>
                <div class="business-info-wrapper">
                    <a :href="'/' + businessDomain" class="banner-logo" style="background-color: #ffffff">
                        <img class="ui small image" v-bind:src="businessLogo">
                    </a>
                    <div class="business-info">
                        <div class="info mr-5">
                            <div class="ui header info">{{businessName}}</div>
                        </div>
                        <div class="info"><button class="ui button" @click="initInfoEdit()" v-if="(role === 'admin')">Edit</button></div>
                        <div class="info"><a :href="'/' + businessDomain" class="ui green button">Visit Page</a></div>
                    </div>
                </div>
            </div>
<!--            <div class="profile-image position-relative">-->
<!--                <img v-bind:src="businessLogo">-->
<!--            </div>-->
<!--            <div class="mt-4" v-show="!editInformation">-->
<!--                <div class="ui header"></div>-->
<!--                <p>{{businessName}} <a class="ml-3 text-success tooltip-element" :href="'/' + businessDomain" data-content="View Business Page"><i class="icon external"></i></a></p>-->
<!--                <p>Business Email: {{businessEmail}}</p>-->
<!--                <p>Business Phone: {{businessPhone}}</p>-->
<!--                <p v-if="businessState">Business State: {{businessState}}</p>-->
<!--                <p v-if="businessZip">Business ZIP:{{businessZip}}</p>-->
<!--                <p>Business Address: {{businessAddress}}</p>-->
<!--                <p v-if="businessEIN">EIN: {{businessEIN}}</p>-->
<!--                <p v-if="!businessEIN">Contact Person: {{contactPerson}}</p>-->
<!--                <p v-if="!businessEIN">Contact Email: {{contactEmail}}</p>-->
<!--                <p v-if="!businessEIN">Contact Phone: {{contactPhone}}</p>-->
<!--                <button class="ui btn-primary button" @click="initInfoEdit()" v-if="(role === 'admin')">Edit Information</button>-->
<!--            </div>-->
            <div class="mt-4 w-100" v-show="editInformation" v-if="role === 'admin'">
                <div class="ui fluid form">
                    <h3>Dedicated Business Page Information</h3>
                    <div class="fluid field">
                        <input type="text" v-model="businessName"  placeholder="Business Name (required)" required>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <button class="ui green fluid button choose-images-btn">Choose Logo</button>
                            <span v-show="filesCount > 0">{{filesCount}} image selected</span>
                            <input type="file" ref="file1" v-on:change="handleFilesChange()" class="d-none image-input" accept="image/x-png,image/gif,image/jpeg">
                        </div>
                        <div class="field" v-if="hasActiveSubscription">
                            <button class="ui green fluid button choose-banner-btn">Choose Banner</button>
                            <span v-show="bannerCount > 0">{{bannerCount}} image selected</span>
                            <input type="file" ref="file2" v-on:change="handleBannerChange()" class="d-none banner-input" accept="image/x-png,image/gif,image/jpeg">
                        </div>
                    </div>
                    <h3>Other Information</h3>
                    <div class="field">
                        <input type="text" v-model="businessEmail" placeholder="Business Email (required)" required>
                    </div>
                    <div class="field">
                        <input type="text" v-model="businessPhone" placeholder="Business Phone (required)" required>
                    </div>
                    <div class="field">
                        <input type="text" v-model="businessZip" placeholder="Zip Code of Business (Optional)">
                    </div>
                    <div class="field">
                        <input type="text" v-model="businessState" placeholder="State of Business (Optional)" required autocomplete="new-password">
                    </div>
                    <div class="field">
                        <textarea v-model="businessAddress" placeholder="Mailing Address (required)" rows="4"></textarea>
                    </div>

                    <div class="inline fields mt-2">
                        <label for="business">Is this a registered business:</label>
                        <div class="field">
                            <div class="ui radio checkbox business-check">
                                <input type="radio" name="business" checked="" tabindex="0" v-model="registerValue" class="hidden" value="yes">
                                <label>Yes</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox business-check">
                                <input type="radio" name="business" tabindex="0" v-model="registerValue" class="hidden" value="no">
                                <label>No</label>
                            </div>
                        </div>
                    </div>
                    <div class="field business-ein" v-show="registerValue">
                        <input class="ein" type="text" v-model="businessEIN" placeholder="EIN (required)" required>
                    </div>

                    <div class="contact-person" v-show="!registerValue">
                        <div class="ui small header">Contact Person</div>
                        <div class="field">
                            <input type="text" v-model="contactPerson" placeholder="Contact Name (required)" required>
                        </div>
                        <div class="field">
                            <input type="text" v-model="contactEmail" placeholder="Contact Email (required)"  required>
                        </div>
                        <div class="field">
                            <input type="text" v-model="contactPhone" placeholder="Contact Phone (required)" required>
                        </div>
                    </div>
                    <div class="field mt-4" v-show="formError">
                        <label class="ui small header red">{{formError}}</label>
                    </div>
                    <div class="field mt-4">
                        <button class="ui button btn-primary" type="submit" @click="joinBusiness">Update</button>
                        <button class="ui button" @click="closeInfoEdit">Cancel</button>
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
    Vue.use(VueToast);

    export default {
        props: ['currentBusiness', 'accessRole', 'activePlan', 'hasSubscription'],
        data () {
            return {
                businessName : '',
                businessDomain : '',
                businessEmail : '',
                businessPhone : '',
                businessZip : '',
                businessState : '',
                businessAddress : '',
                businessEIN : '',
                businessBanner: '',
                contactPerson : '',
                contactEmail : '',
                contactPhone : '',
                images: '',
                filesCount: 0,
                formError: '',
                registerValue: 'yes',
                editInformation: false,
                businessLogo: '',
                role: '',
                subscription: '',
                plan: '',
                banner: '',
                bannerCount: 0,
                hasActiveSubscription: false
            }
        },
        mounted() {
            let business = this.currentBusiness;
            this.subscription = business.subscription;
            this.hasActiveSubscription = this.hasSubscription;
            this.plan = this.activePlan;
            this.businessLogo = business.logo;
            this.businessBanner = business.banner;
            this.businessName = business.name ? business.name : '' ;
            this.businessDomain = business.subdomain ? business.subdomain : '' ;
            this.businessEmail = business.email ? business.email : '';
            this.businessPhone = business.phone ? business.phone : '';
            this.businessAddress = business.address ? business.address : '';
            this.businessState = business.state ? business.state : '';
            this.businessZip = business.zip ? business.zip : '';
            this.businessEIN = business.ein ? business.ein : '';
            this.registerValue = business.is_registered ? 'yes' : 'no';
            this.contactPerson = business.contact_person ? business.contact_person : '';
            this.contactEmail = business.contact_email ? business.contact_email : '';
            this.contactPhone = business.contact_phone ? business.contact_phone : '';
            this.role = this.accessRole;
        },
        methods: {
            handleFilesChange() {
                this.images = this.$refs.file1.files;
                this.filesCount = this.$refs.file1.files.length;
            },
            handleBannerChange() {
                this.banner = this.$refs.file2.files;
                this.bannerCount = this.$refs.file2.files.length
            },
            initInfoEdit() {
                this.editInformation = true;
            },
            closeInfoEdit() {
                this.editInformation = false;
            },
            hasValidData() {
                if(this.businessName.length > 0
                    && this.businessEmail.length > 0
                    && this.businessPhone.length > 0
                    && this.businessAddress.length > 0
                ){
                    return true;
                }

                return false;
            },
            hasValidContactPerson() {
                if(this.contactPerson.length > 0
                    && this.contactEmail.length > 0
                    && this.contactPhone.length > 0
                ){
                    return true;
                }

                return false;
            },
            joinBusiness() {
                this.formError = '';

                if(!this.hasValidData()){
                    this.formError = 'Please fill all the required fields';
                    return false;
                }

                if(!this.businessEIN){
                    if(this.registerValue === 'yes'){
                        this.formError = 'Please fill EIN';
                        return false;
                    }
                    else{
                        if(!this.hasValidContactPerson()){
                            this.formError = 'Please fill contact person fields';
                            return false;
                        }
                    }
                }

                let formData = new FormData();
                let images = this.images;
                if(this.filesCount > 0){
                    for( var i = 0; i < images.length; i++ ){
                        let file = images[i];
                        formData.append('images[' + i + ']', file);
                    }
                }

                let banners = this.banner;
                if(this.bannerCount > 0){
                    for( var j = 0; j < banners.length; j++ ){
                        let bannerFile = banners[j];
                        formData.append('banners[' + j + ']', bannerFile);
                    }
                }


                formData.append('name', this.businessName);
                formData.append('email', this.businessEmail);
                formData.append('phone', this.businessPhone);
                formData.append('address', this.businessAddress);
                formData.append('ein', this.businessEIN);
                formData.append('zip', this.businessZip);
                formData.append('state', this.businessState);
                formData.append('contact_person', this.contactPerson);
                formData.append('contact_email', this.contactEmail);
                formData.append('contact_phone', this.contactPhone);
                formData.append('business_id', this.currentBusiness.id);
                formData.append('is_registered', this.registerValue);

                NProgress.start();
                axios.post('/api/business/join', formData).then(response => {
                    Vue.$toast.success(response.data.message);
                    setTimeout(function(){
                        NProgress.done();
                        window.location.href = ''
                    }, 2000)

                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    this.formError = response.data.message
                });
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
