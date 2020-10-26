<template>
    <div>
        <div class="w-100 mt-4">
            <div class="ui fluid card" v-show="false">
                <div class="content">
                    <div class="ui grid">
                        <div class="three wide computer column sixteen wide tablet column">
                            <img class="ui small image" src="https://www.hubspot.com/hubfs/Canva%20images/canva-image-10.png">
                        </div>
                        <div class="thirteen wide computer column sixteen wide tablet column">
                            <h3 class="ui small header">Need a CRM, social media manager, and email manager? Vocit has a hubspot API to make it easy for you to get your received content curated, designed, and shared</h3>
                            <a href="https://www.hubspot.com/products/get-started" target="_blank" class="ui primary button">Get Hubspot</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui fluid card">
                <div class="content">
                    <div class="ui grid">
                        <div class="three wide computer column sixteen wide tablet column">
                            <img class="ui small image" src="https://jumpseller.com/images/support/zapier/logo.png">
                        </div>
                        <div class="thirteen wide computer column sixteen wide tablet column">
                            <h3 class="ui small header">Connect your apps with Vocit to automate your content upload process. To get started please contact <a class="link" href="mailto:justin.vocit@gmail.com
">Our Admin</a></h3>
                            <p>Zapier API Key: {{this.apiKey}}</p>
                            <button class="ui primary button add-connection mt-3">Add Connection</button>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="ui grid mt-2"  v-if="integrations.length > 0" v-for="integration in integrations">
                        <div class="three wide computer column sixteen wide tablet column">
                            {{integration.name}}
                        </div>
                        <div class="ten wide computer column sixteen wide tablet column">
                            {{integration.key}}
                        </div>
                        <div class="three wide computer column sixteen wide tablet column text-right">
                            <button class="ui red button" @click="handleDeleteConnection(integration.id)"><i class="close icon"></i></button>
                        </div>
                    </div>
                    <div class="ui grid mt-2" v-if="integrations.length === 0">
                        <h2>No Connections Found</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui modal connection-modal">
            <div class="header">Zapier Connection</div>
            <div class="content">
                <div class="ui form">
                    <div class="field">
                        <label>App Name</label>
                        <input type="text" v-model="name" placeholder="Eg, Facebook">
                    </div>
                    <div class="field">
                        <label>Zapier Hook Url</label>
                        <input type="text" v-model="key">
                    </div>
                    <div class="field">
                        <button class="ui primary button" @click="handleAddConnection()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from "vue";
    import VueToast from "vue-toast-notification";
    import NProgress from 'nprogress';
    import 'nprogress/nprogress.css';

    Vue.use(VueToast);
    export default {
        props: ['zapierIntegrations', 'apiKey'],
        data() {
            return {
                integrations: [],
                appName: 'zapier',
                name: '',
                key: ''
            }
        },
        mounted() {
            this.integrations = this.zapierIntegrations
            $(document).on('click', '.add-connection', function () {
                $('.ui.connection-modal')
                    .modal('show')
                ;
            })

        },
        methods: {
            handleAddConnection() {
                let formData = new FormData();

                formData.append('name', this.name);
                formData.append('app_name', this.appName);
                formData.append('key', this.key);

                NProgress.start();
                axios.post('/api/business/integrations', formData).then(response => {
                    Vue.$toast.success(response.data.message);
                    setTimeout(function() {
                        NProgress.done();
                        window.location.reload();
                    }, 2000)
                }).catch(error => {
                    NProgress.done();
                    let response = error.response;
                    Vue.$toast.error(response.data.message);
                });
            },
            handleDeleteConnection(connectionId) {
                NProgress.start();
                axios.delete('/api/business/integrations/' + connectionId).then(response => {
                    Vue.$toast.success(response.data.message);
                    setTimeout(function() {
                        NProgress.done();
                        window.location.reload();
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
