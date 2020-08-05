<template>
    <div>
        <div class="ui small header">Select a payment method</div>
        <div class="ui two cards">

            <form id="paypalForm" method="post" class="d-none">
                <input type="hidden" name="_token" v-model="csrf">
            </form>

            <a class="ui green card payment-method payment-stripe" data-method="stripe" @click="payWithStripe()">
                <div class="content">
                    <img height="50px" style="max-width: 100%" src="/assets/images/credit-card-logos.png">
                </div>
            </a>

            <a class="ui card payment-method payment-paypal" data-method="paypal" @click="payWithPaypal()">
                <div class="content">
                    <img height="50px" src="/assets/images/paypal.png">
                </div>
            </a>
        </div>
        <div class="ui form mt-3">
            <div class="field">
                <label>Name on Card</label>
                <input type="text" placeholder="Name" v-model="name">
            </div>
            <div class="field">
                <label>Card Number</label>
                <input type="text" maxlength="16" placeholder="Number" v-model="card">
            </div>
            <div class="three fields">
                <div class="field">
                    <label>Expiry Month</label>
                    <input type="text" maxlength="2" placeholder="Month" v-model="month">
                </div>
                <div class="field">
                    <label>Expiry Year</label>
                    <input type="text" maxlength="4" placeholder="Year" v-model="year">
                </div>
                <div class="field">
                    <label>CVC</label>
                    <input type="text" maxlength="3" placeholder="CVC" v-model="cvc">
                </div>
            </div>
            <div class="field">
                <div class="ui red small header" v-show="error">{{error}}</div>
            </div>
            <div class="field">
                <a :href="'/' + businessName + '/posts'" class="ui button">Cancel</a>
                <button class="ui green button" @click="makeStripePayment()">Checkout</button>
            </div>
        </div>
    </div>
</template>

<script>
    import NProgress from 'nprogress';
    import 'nprogress/nprogress.css';

    export default {
        props: ['quantity', 'package', 'csrfToken', 'sessionError', 'activeBusinessName'],
        data () {
            return {
                selectedPackage: this.package,
                selectedQuantity : this.quantity,
                csrf: '',
                card : '',
                name: '',
                month: '',
                year: '',
                cvc: '',
                error: '',
                method: '',
                businessName : this.activeBusinessName
            }
        },
        mounted() {
            this.csrf = this.csrfToken;
            this.error = this.sessionError;
            let $this = this;
            $(document).on('click', '.payment-method', function(){
                let method = $(this).attr('data-method');
                $('.payment-method').removeClass('green');
                $('.payment-' + method).addClass('green');
                $this.method = method;
            })
        },
        methods: {
            payWithPaypal() {
                $('#paypalForm').submit();
            },
            payWithStripe() {

            },
            valdateStripe() {
                if(
                    this.card.length > 10 &&
                    this.name.length > 1 &&
                    this.year.length > 3 &&
                    this.month.length > 1 &&
                    this.cvc.length > 0
                ){
                    return true;
                }

                return false;
            },
            makeStripePayment() {
                NProgress.start();
                if(!this.valdateStripe()){
                    this.error = 'Invalid card information provided';
                    return false;
                }

                let formData = {
                    quantity: this.selectedQuantity,
                    package: this.selectedPackage,
                    card: this.card,
                    month: this.month,
                    year: this.year,
                    cvc: this.cvc,
                    name: this.name
                }
                axios.post('/api/payment', formData).then(response => {
                    console.log(response);
                    NProgress.done();
                    window.location.href = ('/' + this.businessName + '/profile');
                }).catch(error => {
                    NProgress.done();
                    this.error = error.response.data.message;
                });
            }
        }
    }
</script>

<style scoped>

</style>
