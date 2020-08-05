<template>
    <div>
        <div class="ui grid">
                <h3 class="ui small header">Companies</h3>
                <div class="ui fluid card">
                    <table class="ui striped padded table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Payment Method</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="payment in payments">
                            <td class="middle aligned">
                                <img class="ui mini image" v-bind:src="payment.business.logo">
                            </td>
                            <td class="middle aligned" data-label="Name">
                                <a :href="'/' + payment.business.subdomain">{{payment.business.name}}</a>
                            </td>
                            <td class="middle aligned" data-label="Email">{{payment.amount}}</td>
                            <td class="middle aligned" data-label="Email">{{payment.is_subscription ? 'Subscription' : 'Purchase'}}</td>
                            <td class="middle aligned" data-label="Email">{{payment.payment_method}}</td>
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
</template>

<script>
    export default {
        props: ['paymentList'],
        data() {
            return {
                payments: '',
                currentPage: '',
                nextPage: '',
                previousPage: '',
                hasMorePage: '',
                totalPaymentsCount: '',
            }
        },
        mounted() {
            this.currentPage = this.paymentList.current_page;
            this.nextPage = this.paymentList.next_page_url;
            this.previousPage = this.paymentList.prev_page_url;
            this.payments = this.paymentList.data;
            this.totalPaymentsCount = this.paymentList.total;
            this.hasMorePage = !!this.paymentList.next_page_url;
        },
        methods: {

        }
    }
</script>

<style scoped>

</style>
