<template>
    <div>
        <img :src="fbUrl" />
        <br />
        <span> {{ "I received a '" + serviceName + "' from '" + businessName + "'"}} </span>
    </div>
</template>

<script>
export default {
    props: {
        businessName: {
            type: String,
            default: ""
        },
        serviceName: {
            type: String,
            default: ""
        }
    },
    data: function() {
            return {
                fbUrl: null
            }
        },
    mounted() {
        this.fbUrl = localStorage.getItem('fbUrl');
    }
}
</script>