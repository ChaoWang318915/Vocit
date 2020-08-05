<template>

</template>

<script>
    import VueSession from 'vue-session'
    Vue.use(VueSession);

    export default {
        props: ['apiToken', 'currentOrganization', 'baseUrl'],
        mounted() {
            let hasToken = this.apiToken;
            if(hasToken){
                axios.defaults.headers.common = {
                    "Accept": "application/json",
                    "Authorization": "Bearer " + this.apiToken,
                    'Content-Type': 'multipart/form-data',
                    'BusinessId' : this.currentOrganization,
                    'Access-Control-Allow-Origin': true,
                };
            }
            else{
                axios.defaults.headers.common = {
                    "Accept": "application/json",
                    'Content-Type': 'multipart/form-data'
                };
            }
        }
    }
</script>

<style scoped>

</style>
