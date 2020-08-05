<template>
    <div>
        <h3>Join as Business to upload requests</h3>
        <div class="ui form mt-5">

            <div class="field">
                <input type="text" v-model="businessName"  placeholder="Business Name (required)" required>
                <label class="ui header red mt-3" v-show="nameError">
                </label>
            </div>
            <div class="two fields">
                <div class="field">
                    <input type="text" v-model="businessEmail" placeholder="Business Email (required)" required>
                    <label class="ui header red mt-3" v-show="emailError">
                    </label>
                </div>
                <div class="field">
                    <input type="text" v-model="businessPhone" placeholder="Business Phone (required)" required>
                    <label class="ui header red mt-3" v-show="phoneError">
                    </label>
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <input type="text" v-model="businessZip"  placeholder="Zip Code of Business (Optional)">
                    <label class="ui header red mt-3" v-show="zipError">
                    </label>
                </div>
                <div class="field">
                    <input type="text" v-model="businessState"  placeholder="State of Business (Optional)" required autocomplete="new-password">
                    <label class="ui header red mt-3" v-show="stateError">
                    </label>
                </div>
            </div>
            <div class="field">
                <textarea v-model="businessAddress" placeholder="Mailing Address (required)" rows="4"></textarea>
                <label class="ui header red mt-3" v-show="addressError">
                </label>
            </div>
            <div class="field">
                <button class="ui active green button choose-images-btn">
                    <i class="upload icon"></i>
                    Upload Logo
                </button>
                <span v-show="filesCount > 0">{{filesCount}} image selected</span>
                <input type="file" ref="file" v-on:change="handleFilesChange()" class="d-none image-input" accept="image/x-png,image/gif,image/jpeg">
            </div>
            <div class="two fields">
                <div class="field">
                    <div class="inline fields mt-2">
                        <label for="business">Is this a registered business:</label>
                        <div class="field">
                            <div class="ui radio checkbox business-check">
                                <input type="radio" name="business" v-bind:checked="registerValue" v-model="registerValue" tabindex="0" class="hidden" value="yes">
                                <label>Yes</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox business-check">
                                <input type="radio" name="business" tabindex="0" v-bind:checked="!registerValue" v-model="registerValue" class="hidden" value="no">
                                <label>No</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field business-ein">
                    <input class="ein" type="text" v-model="businessEIN" placeholder="EIN (required)" required>
                </div>
            </div>

            <div class="contact-person" v-show="!registerValue">
                <div class="ui small header">Contact Person</div>
                <div class="two fields">
                    <div class="field">
                        <input type="text" v-model="contactPerson" placeholder="Contact Name (required)" required>
                    </div>
                    <div class="field">
                        <input type="text" v-model="contactEmail" placeholder="Contact Email (required)"  required>
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <input type="text" v-model="contactPhone" placeholder="Contact Phone (required)" required>
                    </div>
                </div>
            </div>
            <div class="field text-center mt-4" v-show="formError">
                <label class="ui small header red">{{formError}}</label>
            </div>
            <div class="field text-center mt-4">
                <button class="ui button btn-primary" type="submit" @click="joinBusiness">JOIN</button>
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
        data () {
            return {
                businessName : '',
                businessEmail : '',
                businessPhone : '',
                businessZip : '',
                businessState : '',
                businessAddress : '',
                businessEIN : '',
                contactPerson : '',
                contactEmail : '',
                contactPhone : '',
                images: '',
                filesCount: 0,
                formError: '',
                registerValue: 'yes',
                addressError: '',
                nameError: '',
                emailError: '',
                stateError: '',
                zipError: '',
                phoneError: '',
            }
        },
        mounted() {
        },
        methods: {
            handleFilesChange() {
                this.images = this.$refs.file.files;
                this.filesCount = this.$refs.file.files.length
            },
            hasValidData() {
                if(this.businessName.length === 0){
                    this.nameError = 'Name is required';
                    return false;
                }

                if(this.businessEmail.length === 0){
                    this.emailError = 'Email is required';
                    return false;
                }

                if(this.businessPhone.length === 0){
                    this.phoneError = 'Phone is required';
                    return false;
                }

                return true;
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

                if(this.businessEIN.length === 0){
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

                if(this.filesCount === 0){
                    this.formError = 'Please add a logo';
                    return false;
                }

                let formData = new FormData();
                let images = this.images;
                for( var i = 0; i < images.length; i++ ){
                    let file = images[i];
                    formData.append('images[' + i + ']', file);
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
                formData.append('is_registered', this.registerValue);

                NProgress.start();
                axios.post('/api/business/join', formData).then(response => {
                    let data = response.data.data;
                    Vue.$toast.success(response.data.message);
                    setTimeout(function(){
                        NProgress.done();
                        window.location.href = '/'+data.subdomain+'/profile'
                    }, 2000)

                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    this.formError = response.data.message
                });
            }
        }
    }
</script>

<style scoped>

</style>
