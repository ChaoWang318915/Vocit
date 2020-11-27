<template>
    <div>
        <h3>Profile</h3>
        <div class="profile-image position-relative">
            <img v-bind:src="user.profile_pic">
            <span class="choose-image" @click="chooseImage" ><i class="pencil icon"></i></span>
        </div>
        <div class="mt-4" v-show="!editInformation">
            <div class="ui header">{{user.name}}</div>
            <p>Email: {{email}}</p>
            <p v-if="phone">Phone: {{phone}}</p>
            <p v-if="username">UserName: {{username}}</p>
            <button class="ui btn-primary button" @click="initInfoEdit()">Edit Profile</button>
        </div>

        <input class="d-none" type="file" id="cropperBtn" @change="croppie" accept="image/*"/>

        <div class="mt-4 w-100" v-show="editInformation">
            <div class="ui form">
                <div class="field">
                    <label>First Name</label>
                    <input type="text" v-model="firstName"  placeholder="First Name" required>
                </div>
                <div class="field">
                    <label>Last Name</label>
                    <input type="text" v-model="lastName" placeholder="Last Name" required>
                </div>
                <div class="field disabled">
                    <label>Email</label>
                    <input type="email" v-model="email" placeholder="Email" required>
                </div>
                <div class="field">
                    <label>Username</label>
                    <input type="text" v-model="username" placeholder="Username" required>
                </div>
                <div class="field">
                    <label>Phone</label>
                    <input type="text" v-model="phone" placeholder="Phone" required>
                </div>
<!--                <div class="field">-->
<!--                    <button class="ui green compact button choose-images-btn">Choose Profile Picture</button>-->
<!--                    <span v-show="filesCount > 0">{{filesCount}} image selected</span>-->
<!--                    <input type="file" ref="file" v-on:change="handleFilesChange()" class="d-none image-input" accept="image/x-png,image/gif,image/jpeg">-->
<!--                </div>-->
                <div class="field mt-4" v-show="formError">
                    <label class="ui small header red">{{formError}}</label>
                </div>
                <div class="field mt-4">
                    <button class="ui button btn-primary" type="submit" @click="updateProfile">Update</button>
                    <button class="ui button" @click="closeInfoEdit">Cancel</button>
                </div>
            </div>
        </div>
        <h3 v-if="hasSocialAccount">Connected Accounts</h3>
        <div class="mt-4">
            <button v-if="user.facebook_id" class="ui facebook button popup-element position-relative" data-content="Unlink" @click="unlinkSocialAccount('facebook')">
                <i class="facebook icon"></i>
                Facebook
            </button>
            <button v-if="user.twitter_id" class="ui twitter button popup-element position-relative" data-content="Unlink" @click="unlinkSocialAccount('twitter')">
                <i class="twitter icon"></i>
                Twitter
            </button>
        </div>
        <div class="ui modal small cropper-modal">
            <div class="header">Upload Image</div>
            <div class="content">
                <vue-croppie ref="croppieRef"
                             :enableOrientation="true"
                             :boundary="{ width: 500, height: 500}"
                             :viewport="{ width:250, height:250, 'type':'square' }"
                             :enableResize="false">
                </vue-croppie>
            </div>
            <div class="actions">
                <div class="ui button"  @click="crop">Crop</div>
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
    import VueCroppie from 'vue-croppie';
    import 'croppie/croppie.css' // import the croppie css manually

    Vue.use(VueCroppie);
    Vue.use(VueToast);

    export default {
        props: ['currentUser'],
        data() {
            return {
                user: '',
                email: '',
                firstName: '',
                lastName: '',
                phone : '',
                username: '',
                editInformation: false,
                filesCount: 0,
                formError: '',
                images: '',
                hasSocialAccount: false,
                croppieImage: '',
                cropped: null
            }
        },
        mounted() {
            this.user = this.currentUser ? this.currentUser : '';
            this.firstName =  this.user.first_name ? this.user.first_name : '';
            this.lastName = this.user.last_name ? this.user.last_name : '';
            this.email = this.user.email ? this.user.email : '';
            this.phone = this.user.phone ? this.user.phone : '';
            this.username = this.user.username ? this.user.username : '';
            this.hasSocialAccount = (this.user.twitter_id || this.user.facebook_id);
        },
        methods: {
            handleFilesChange() {
                this.images = this.$refs.file.files;
                this.filesCount = this.$refs.file.files.length
            },
            validateData() {
                if(this.firstName.length > 0
                    && this.lastName.length > 0
                    && this.email.length > 0
                ){
                    return true;
                }
                return false;
            },
            updateProfile() {
                if(!this.validateData()){
                    this.formError = 'Please fill all the required fields';
                    return false;
                }

                let formData = new FormData();
                let images = this.images;
                if(this.filesCount > 0){
                    for( var i = 0; i < images.length; i++ ){
                        let file = images[i];
                        formData.append('images[' + i + ']', file);
                    }
                }

                formData.append('first_name', this.firstName);
                formData.append('last_name', this.lastName);
                formData.append('email', this.email);
                formData.append('phone', this.phone);
                formData.append('username', this.username);

                NProgress.start();
                axios.post('/api/users/' + this.user.id, formData).then(response => {
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
            initInfoEdit() {
                this.editInformation = true;
            },
            closeInfoEdit() {
                this.editInformation = false;
            },
            unlinkSocialAccount(type) {
                NProgress.start();
                let formData = {
                    account: type
                };
                axios.post('/api/users/' + this.user.id + '/socialunlink', formData).then(response => {
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
            croppie (e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length) return;

                var reader = new FileReader();
                reader.onload = e => {
                    this.$refs.croppieRef.bind({
                        url: e.target.result
                    });
                };

                reader.readAsDataURL(files[0]);
                $('.cropper-modal').modal('show');
            },
            crop() {
                // Options can be updated.
                // Current option will return a base64 version of the uploaded image with a size of 600px X 450px.
                let options = {
                    type: 'base64',
                    size: { width: 250, height: 250 },
                    format: 'jpeg'
                };
                this.$refs.croppieRef.result(options, output => {
                    this.cropped = this.croppieImage = output;
                    let formData = {'image' : this.croppieImage};
                    axios.post('/api/users/' + this.user.id, formData).then(response => {
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
                });
            },
            // EVENT USAGE
            cropViaEvent() {
                this.$refs.croppieRef.result(options);
            },
            result(output) {
                this.cropped = output;
            },
            update(val) {
            },
            rotate(rotationAngle) {
                // Rotates the image
                this.$refs.croppieRef.rotate(rotationAngle);
            },
            chooseImage() {
                $(document).find('#cropperBtn').trigger('click');
            }
        }
    }
</script>

<style scoped>

</style>
