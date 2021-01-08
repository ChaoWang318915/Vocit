<template>
    <div>
        <img :src="fbUrl" />
        <br />
        <span> {{ "I received a '" + serviceName + "' from '" + businessName + "'"}} </span>
    </div>
</template>

<script>
export default {
    data: function() {
            return {
                fbUrl: null,
                serviceName: '',
                businessName: false
            }
        },
    mounted() {
        this.fbUrl = localStorage.getItem('fbUrl');
        this.serviceName = localStorage.getItem('serviceName');
        this.businessName = localStorage.getItem('businessName');
    }
}
</script>