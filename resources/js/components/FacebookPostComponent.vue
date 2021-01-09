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
        var desc = "I received a " + this.serviceName + " from " + this.businessName;
        $("head").append('<meta property="og:title" content="' + this.businessName + '">');
        $("head").append("<meta property='og:type' content='website'>");
        $("head").append('<meta property="og:description" content="' + desc + '">');                                  
        $("head").append('<meta property="og:image" content="' + this.fbUrl + '">');
        $("head").append("<meta property='og:url' content='https://vocit.io/facebookpost' />");  
    }
}
</script>