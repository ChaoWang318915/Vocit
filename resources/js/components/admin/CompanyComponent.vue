<template>
    <div>
        <div class="ui grid">
            <div class="column">
                <div v-show="showForm">
                    <h3 class="ui small header">New Company</h3>
                    <div class="ui fluid form">
                        <div class="ui two fluid fields">
                            <div class="field">
                                <label>Name</label>
                                <input type="text" name="first-name" placeholder="Name" v-model="companyName">
                            </div>
                            <div class="field">
                                <label>Email</label>
                                <input type="text" name="first-name" placeholder="Email" v-model="companyEmail">
                            </div>
                        </div>
                        <div class="ui two fluid fields">
                            <div class="field">
                                <label>Website</label>
                                <input type="text" name="first-name" placeholder="Name" v-model="companyWebsite">
                            </div>
                            <div class="field">
                                <label>Select Logo</label>
                                <button class="ui green labeled icon button choose-images-btn">
                                    <i class="upload icon"></i>
                                    Upload
                                </button>
                                <span v-show="filesCount > 0">{{filesCount}} image selected</span>
                                <input ref="file" v-on:change="handleFilesChange()" name="images" type="file" class="d-none image-input" accept="image/x-png,image/gif,image/jpeg">
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui red small header label" v-show="formError">{{formError}}</div>
                        </div>
                        <div class="field">
                            <button class="ui button bg-custom" @click="saveCompany()">Save</button>
                        </div>
                    </div>
                </div>
                <h3 class="ui small header">Companies</h3>
                <div class="ui fluid card">
                    <div class="content">
<!--                        <button class="ui basic green button right floated" @click="companyForm()"><i class="plus icon"></i> New</button>-->
                        <div class="ui icon input right floated mr-2">
                            <input type="text" placeholder="Search..." v-on:keyup.enter="searchCompany($event.target.value)">
                            <i class="search icon"></i>
                        </div>
                    </div>
                    <table class="ui striped padded table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
<!--                            <th>Phone</th>-->
                            <th>Address</th>
                            <th>EIN</th>
                            <th>Contact Email</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="company in companies">
                            <td class="middle aligned"><img class="ui mini image" v-bind:src="company.logo"></td>
                            <td class="middle aligned" data-label="Name"><a :href="'/' + company.subdomain">{{company.name}}</a> </td>
                            <td class="middle aligned" data-label="Email">{{company.email}}</td>
<!--                            <td class="middle aligned" data-label="Email">{{company.phone ? company.phone : '-'}}</td>-->
                            <td class="middle aligned" data-label="Email">{{company.address}}</td>
                            <td class="middle aligned" data-label="Email">{{company.ein ? company.ein : '-'}}</td>
                            <td class="middle aligned" data-label="Email">{{company.contact_email ? company.contact_email : '-'}}</td>
                            <td class="middle aligned" data-label="Email">
<!--                                <button class="ui red button" v-if="!user.is_suspended" @click="suspendBusiness(company.id, 1)">Suspend</button>-->
<!--                                <button class="ui green button" v-if="user.is_suspended" @click="restoreBusiness(company.id, 0)">Restore</button>-->
                                <button class="ui red button" @click="deleteBusiness(company.id)">Delete</button>
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
        props: ['companiesList'],
        data() {
            return {
                currentPage: '',
                nextPage: '',
                previousPage: '',
                hasMorePage: '',
                totalUsersCount: '',
                postsShowing: '',
                companies: '',
                showForm: false,
                companyName: '',
                companyEmail: '',
                companyWebsite: '',
                images: '',
                filesCount : 0,
                formError: '',
                updatingCompany: ''
            }
        },
        mounted() {
            console.log(this.companiesList);
            this.currentPage = this.companiesList.current_page;
            this.nextPage = this.companiesList.next_page_url;
            this.previousPage = this.companiesList.prev_page_url;
            this.totalUsersCount = this.companiesList.total;
            this.postsShowing = this.companiesList.to;
            this.companies = this.companiesList.data;
            this.hasMorePage = !!this.companiesList.next_page_url || !!this.companiesList.prev_page_url;
        },
        methods: {
            markSolved(postId, companyId) {
                NProgress.start();
                axios.put('/api/posts/' + postId, {solved_by: companyId}).then(response => {
                    Vue.$toast.success(response.data.message);
                    window.location.reload();
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error(response.data.message);
                });
            },
            companyForm(company = '') {
                this.showForm = true;
                if(company){
                    this.updatingCompany = company.id;
                    this.companyName = company.name;
                    this.companyEmail = company.email;
                    this.companyWebsite = company.website;
                }
            },
            isValidForm() {
                return (this.companyName.length > 0 &&
                    this.companyWebsite.length > 0 &&
                    this.companyEmail.length > 0 && this.filesCount > 0);
            },
            saveCompany() {
                let companyId = this.updatingCompany;
                if(!this.isValidForm()){
                    this.formError = 'Please fill all the required fields.';
                    return false;
                }

                let formData = new FormData();
                let images = this.images;
                for( var i = 0; i < images.length; i++ ){
                    let file = images[i];
                    formData.append('images[' + i + ']', file);
                }

                formData.append('name',  this.companyName);
                formData.append('email', this.companyEmail);
                formData.append('website', this.companyWebsite);

                let url = '/api/companies';
                if(companyId){
                    formData.append('company_id', companyId);
                }

                NProgress.start();
                axios.post(url, formData).then(response => {
                    Vue.$toast.success(response.data.message);
                    setTimeout(function(){
                        NProgress.done();
                        window.location.reload();
                    }, 2000)

                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error(response.data.message);
                });
            },
            suspendBusiness(businessId) {
                NProgress.start();
                axios.post('/api/business/' + businessId + '/suspend', {}).then(response => {
                    Vue.$toast.success(response.data.message);
                    window.location.reload();
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error(response.data.message);
                });
            },
            restoreBusiness(businessId) {
                NProgress.start();
                axios.post('/api/business/' + businessId + '/restore', {}).then(response => {
                    Vue.$toast.success(response.data.message);
                    window.location.reload();
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error(response.data.message);
                });
            },
            deleteBusiness(businessId) {
                NProgress.start();
                axios.delete('/api/business/' + businessId).then(response => {
                    Vue.$toast.success(response.data.message);
                    window.location.reload();
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error(response.data.message);
                });
            },
            searchCompany(value) {
                window.history.pushState('search', 'Searching '+ value, '?search=' + value);
                window.location.reload();
            },
            handleFilesChange() {
                this.images = this.$refs.file.files;
                this.filesCount = this.$refs.file.files.length
            },
        }
    }
</script>

<style scoped>

</style>
