<template>
    <LoadingComponent :props="loading" />
    <div class="w-full max-w-3xl mx-auto rounded-2xl flex overflow-hidden gap-y-6 bg-white shadow-card mb-24 !sm:mb-0">
        <img :src="APP_URL + '/images/required/auth.jpg'" alt="banners"
            class="w-full hidden sm:block sm:max-w-xs md:max-w-sm flex-shrink-0">
        <!-- <form class="w-full p-6" @submit.prevent="login">
            <div class="text-center mb-8">
                <h3 class="capitalize text-2xl mb-2 font-bold text-primary">{{ $t('label.sign_in') }}</h3>
                <p class="font-medium">{{ $t('message.continue_shopping') }}</p>
                <div v-if="errors.validation"
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-5 rounded relative" role="alert">
                    <span class="block sm:inline">{{ errors.validation }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" @click="close">
                        <i class="lab lab-close-circle-line margin-top-5-px"></i>
                    </span>
                </div>
            </div>

            <div :class="!toggleValue ? 'mb-6' : ''">
                <div class="flex items-center justify-between">
                    <label :for="!toggleValue ? 'formEmail' : 'phone'"
                        class="text-sm font-medium capitalize mb-1 field-title required">
                        {{ inputLabel }}
                    </label>
                    <button type="button" class="text-sm font-medium capitalize mb-1 underline text-primary"
                        @click="handleField()">
                        {{ inputButton }}
                    </button>
                </div>
                <div v-if="toggleValue" :class="errors.phone ? 'invalid' : ''"
                    class="flex items-center gap-1.5 px-4 h-12 rounded-lg border border-[#D9DBE9] hover:border-primary/30 focus-within:border-primary/30 transition-all duration-500">
                    <div class="w-fit flex-shrink-0 dropdown-group">
                        <button type="button" class="flex items-center gap-1 dropdown-btn">
                            {{ flag }}
                            <span class="whitespace-nowrap flex-shrink-0 text-xs">{{
                                form.country_code
                                }}</span>
                            <i class="fa-solid fa-caret-down text-xs"></i>
                        </button>
                        <ul
                            class="p-1.5 w-24 rounded-lg shadow-xl absolute top-8 -left-4 z-10 border border-gray-200 bg-white hidden dropdown-list !h-52 !overflow-x-hidden !overflow-y-auto thin-scrolling">
                            <li v-for="countryCode in countryCodes" @click="countryCodeChange(countryCode)"
                                class="flex items-center gap-2 p-1.5 rounded-md cursor-pointer hover:bg-gray-100">
                                {{ countryCode.flag_emoji }}
                                <span class="whitespace-nowrap text-xs">{{ countryCode.calling_code }}</span>
                            </li>
                        </ul>

                    </div>
                    <input v-model="form.phone" v-on:keypress="phoneNumber($event)" v-bind:class="errors.phone
                        ? 'invalid' : ''" type="text" id="phone" class="pl-2 text-sm w-full h-full" />
                </div>
                <input v-if="!toggleValue" v-model="form.email" :class="errors.email ? 'invalid' : ''" id="formEmail"
                    type="text"
                    class="w-full h-12 px-4 rounded-lg text-base border border-[#D9DBE9] hover:border-primary/30 focus-within:border-primary/30 transition-all duration-500" />
                <small class="db-field-alert" v-if="errors.email_or_phone">{{ errors.email_or_phone }}</small>
                <span v-else>
                    <small class="db-field-alert" v-if="errors.phone && toggleValue">{{ errors.phone[0] }}</small>
                    <small class="db-field-alert" v-if="errors.email && !toggleValue">{{ errors.email[0] }}</small>
                </span>
            </div>

            <div class="mb-3">
                <label for="formPassword" class="text-sm font-medium capitalize mb-1 field-title required">
                    {{ $t('label.password') }}
                </label>
                <input v-model="form.password" :class="errors.password ? 'invalid' : ''" id="formPassword"
                    type="password"
                    class="w-full h-12 px-4 rounded-lg text-base border border-[#D9DBE9] hover:border-primary/30 focus-within:border-primary/30 transition-all duration-500" />
                <small class="db-field-alert" v-if="errors.password">{{ errors.password[0] }}</small>
            </div>
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="formRemember" class="custom-checkbox">
                    <label for="formRemember" class="text-sm -mb-0.5 capitalize cursor-pointer whitespace-nowrap">{{
                        $t('label.remember_me')
                        }}</label>
                </div>
                <router-link :to="{ name: 'auth.forgotPassword' }" class="field-label text-primary">
                    {{ $t('label.forgot_password') }}
                </router-link>
            </div>
            <button type="submit"
                class="font-bold text-center w-full h-12 leading-12 rounded-full bg-primary text-white capitalize mb-6">
                {{ $t('label.sign_in') }}
            </button>
            <div class="flex items-center justify-center gap-1.5">
                <span class="font-medium text-text">{{ $t('message.dont_have_account') }}</span>
                <router-link class="capitalize font-bold text-primary" :to="{ name: 'auth.signup' }">
                    {{ $t('label.sign_up') }}
                </router-link>
            </div>

            <div v-if="demo === 'true' || demo === 'TRUE' || demo === 'True' || demo === '1' || demo === 1"
                class="mt-6">
                <h2 class="mb-6 text-center text-lg font-medium text-heading">{{ $t('message.for_quick_demo') }}</h2>
                <nav class="grid grid-cols-2 gap-3">
                    <button type="button" @click.prevent="setupCredit('admin')"
                        class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-orange-500"
                        id="adminClick">
                        {{ $t('label.admin') }}
                    </button>
                    <button type="button" @click.prevent="setupCredit('customer')"
                        class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-emerald-500"
                        id="customerClick">
                        {{ $t('label.customer') }}
                    </button>
                    <button type="button" @click.prevent="setupCredit('manager')"
                        class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-sky-600"
                        id="branchManagerClick">
                        {{ $t('label.manager') }}
                    </button>
                    <button type="button" @click.prevent="setupCredit('posOperator')"
                        class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-purple-500"
                        id="posOperatorClick">
                        {{ $t('label.pos_operator') }}
                    </button>
                </nav>
            </div>
        </form> -->


        <!-- <form class="w-full p-6" @submit.prevent="otpSent ? verifyOtp() : sendOtp()">
    <div v-if="!isOtpLogin">
   
        <div :class="!toggleValue ? 'mb-6' : ''">
            <div class="flex items-center justify-between">
                <label :for="!toggleValue ? 'formEmail' : 'phone'"
                    class="text-sm font-medium capitalize mb-1 field-title required">
                    {{ inputLabel }}
                </label>
                <button type="button" class="text-sm font-medium capitalize mb-1 underline text-primary"
                    @click="handleField()">
                    {{ inputButton }}
                </button>
            </div>
            <input v-if="!toggleValue" v-model="form.email" :class="errors.email ? 'invalid' : ''" id="formEmail"
                type="text"
                class="w-full h-12 px-4 rounded-lg text-base border border-[#D9DBE9] hover:border-primary/30 focus-within:border-primary/30 transition-all duration-500" />
            <small class="db-field-alert" v-if="errors.email_or_phone">{{ errors.email_or_phone }}</small>
        </div>

        <div class="mb-3">
            <label for="formPassword" class="text-sm font-medium capitalize mb-1 field-title required">
                {{ $t('label.password') }}
            </label>
            <input v-model="form.password" :class="errors.password ? 'invalid' : ''" id="formPassword"
                type="password"
                class="w-full h-12 px-4 rounded-lg text-base border border-[#D9DBE9] hover:border-primary/30 focus-within:border-primary/30 transition-all duration-500" />
            <small class="db-field-alert" v-if="errors.password">{{ errors.password[0] }}</small>
        </div>
    </div>

    
    <div v-else>
        <div class="mb-6">
            <label for="phone" class="text-sm font-medium capitalize mb-1 field-title required">
                Phone Number
            </label>
            <input v-model="form.phone" type="text" id="phone"
                class="w-full h-12 px-4 rounded-lg text-base border" />
            <small class="db-field-alert" v-if="errors.phone">{{ errors.phone[0] }}</small>
        </div>

        <div v-if="otpSent" class="mb-6">
            <label for="otp" class="text-sm font-medium capitalize mb-1 field-title required">
                OTP
            </label>
            <input v-model="form.otp" type="text" id="otp"
                class="w-full h-12 px-4 rounded-lg text-base border" />
            <small class="db-field-alert" v-if="errors.otp">{{ errors.otp[0] }}</small>
        </div>
    </div>

    <button type="submit"
        class="w-full h-12 rounded-full bg-primary text-white capitalize">
        {{ otpSent ? 'Verify OTP' : (isOtpLogin ? 'Send OTP' : 'Sign In') }}
    </button>

    <button type="button" class="w-full h-12 mt-4 rounded-full border text-primary capitalize"
        @click="isOtpLogin = !isOtpLogin">
        {{ isOtpLogin ? 'Login with Email' : 'Login with OTP' }}
    </button>
</form> -->

        <form class="w-full p-6" @submit.prevent="otpSent ? verifyOtp() : sendOtp()">
            <!-- Always show the OTP Login Form -->
            <div>
                <!-- Phone Input -->
                <div class="mb-6">
                    <label for="phone" class="text-sm font-medium capitalize mb-1 field-title required">
                        Phone Number
                    </label>
                    <input v-model="form.phone" type="text" id="phone"
                        class="w-full h-12 px-4 rounded-lg text-base border" />
                    <small class="text-red-600" v-if="errors.phone">{{ errors.phone[0] }}</small>
                </div>

                <!-- OTP Input (Visible only after sending OTP) -->
                <div v-if="otpSent" class="mb-6">
                    <label for="otp" class="text-sm font-medium capitalize mb-1 field-title required">
                        OTP
                    </label>
                    <input v-model="form.otp" type="text" id="otp" placeholder="Enter OTP"
                        class="w-full h-12 px-4 rounded-lg text-base border" />

                    <small class="text-red-600" v-if="errorss">{{ errorss }}</small>

                    <!-- Resend OTP Button -->
                    <button @click="resendOtp" type="button"
                        class="w-full h-12 rounded-full bg-gray-200 text-primary mt-2"
                        :disabled="loading || countdown > 0">
                        {{ countdown > 0 ? `Resend OTP in ${countdown}s` : 'Resend OTP' }}
                    </button>

                    <!-- Verify OTP Button -->

                </div>



            </div>




            <!-- Submit Button (Changes text dynamically) -->
            <button type="submit" class="w-full h-12 rounded-full bg-primary text-white capitalize">
                {{ otpSent ? 'Verify OTP' : 'Send OTP' }}
            </button>

            <div class="flex items-center justify-center gap-1.5">
                <span class="font-medium text-text">{{ $t('message.dont_have_account') }}</span>
                <router-link class="capitalize font-bold text-primary" :to="{ name: 'auth.signup' }">
                    {{ $t('label.sign_up') }}
                </router-link>
            </div>

            <div class="flex items-center justify-center gap-1.5">
                <span class="font-medium text-text">Login With Password</span>
                <router-link class="capitalize font-bold text-primary" :to="{ name: 'auth.loginPwd' }">
                    Login With Password
                </router-link>
            </div>

            <!-- <button 
    @click="otpSent ? verifyOtp() : sendOtp()" 
    :disabled="loading"
    type="button"
    class="w-full h-12 rounded-full bg-primary text-white capitalize">
    <span v-if="loading">
        {{ otpSent ? 'Verifying...' : 'Sending...' }}
    </span>
    <span v-else>
        {{ otpSent ? 'Verify OTP' : 'Send OTP' }}
    </span>
</button> -->


        </form>


    </div>
</template>

<script>
import Swal from 'sweetalert2';
import router from "../../../router";
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import ENV from "../../../config/env";

export default {
    name: "LoginComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                phone: '',
                otp: ''
            },
            otpSent: false,
            errors: {},
            countdown: 0,
            timer: null,
            flag: "",
            errors: {},
            errorss: '',
            permissions: {},
            firstMenu: null,
            demo: ENV.DEMO,
            APP_URL: ENV.API_URL,
            toggleValue: false,
            inputLabel: this.$t('label.email'),
            inputButton: this.$t('label.use_phone_instead')
        }
    },
    computed: {
        carts: function () {
            return this.$store.getters['frontendCart/lists'];
        },
        countryCodes: function () {
            return this.$store.getters['frontendCountryCode/lists'];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('frontendCountryCode/lists');
        this.$store.dispatch('frontendSetting/lists').then(res => {
            this.$store.dispatch('frontendCountryCode/show', res.data.data.company_country_code).then(res => {
                this.form.country_code = res.data.data.calling_code;
                this.flag = res.data.data.flag_emoji;

                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    methods: {
       

        sendOtp() {
            this.loading = true;
            this.$store.dispatch('sendOtp', this.form.phone)
                .then(() => {
                    this.otpSent = true;
                    this.loading = false;
                    this.errors = {}; // Clear any previous errors
                    Swal.fire({
                        icon: 'success',
                        title: 'OTP Sent!',
                        text: 'Please check your phone for the OTP.',
                        timer: 3000,
                        showConfirmButton: false
                    });


                    this.startCountdown();


                })
                .catch((errors) => {
                    this.errors = errors; // Directly use the errors returned from Vuex
                    this.loading = false;
                    Swal.fire({
                            icon: 'error',
                            title: 'Failed to Send OTP',
                            text: 'Please check your phone number and try again.',
                            timer: 3000,
                            showConfirmButton: false
                        });
                });

        },

        resendOtp() {
            this.loading = true;
            // Resend OTP API call
            this.loading = true;
            this.$store.dispatch('sendOtp', this.form.phone)
                .then(() => {
                    this.otpSent = true;
                    this.loading = false;
                    this.errors = {}; // Clear any previous errors
                    Swal.fire({
                        icon: 'success',
                        title: 'OTP Resent!',
                        text: 'Please check your phone for the OTP.',
                        timer: 3000,
                        showConfirmButton: false
                    });

                    
                    
                    this.startCountdown();


                })
                .catch((errors) => {
                    this.errors = errors; // Directly use the errors returned from Vuex
                    this.loading = false;
                });
        },

        startCountdown() {
            // Reset and start the countdown from 30 seconds
            this.countdown = 15;

            // Clear any existing interval to avoid duplicates
            if (this.timer) {
                clearInterval(this.timer);
            }

            // Start a new countdown
            this.timer = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            }, 1000);
        },
        beforeDestroy() {
            // Clear the interval when the component is destroyed to prevent memory leaks
            if (this.timer) {
                clearInterval(this.timer);
            }
        },


        // verifyOtp() {
        //     this.loading = true;
        //     this.errors = {}; // Clear previous errors

        //     this.$store.dispatch('verifyOtp', this.form)
        //         .then((res) => {
        //             this.$store.dispatch("frontendWishlist/lists").then().catch();
        //             console.log("OTP Response:", res);

        //             if (res.data.user_type.type === 0) {
        //                 console.log("Redirecting to admin page...");
        //                 if (this.carts.length > 0) {
        //                     this.$router.push({ name: "frontend.checkout" });
        //                 } else {
        //                     this.$router.push({ name: "admin.dashboard" });
        //                 }
        //             } else if (res.data.user_type.type === 1) {
        //                 console.log("Redirecting to home page...");
        //                 if (this.carts.length > 0) {
        //                     this.$router.push({ name: "frontend.checkout" });
        //                 } else {
        //                     this.$router.push({ name: "frontend.home" });
        //                 }
        //             } else if (res.data.user_type.type === 5) {
        //                 console.log("Redirecting to POS dashboard...");
        //                 if (this.carts.length > 0) {
        //                     this.$router.push({ name: "frontend.checkout" });
        //                 } else {
        //                     this.$router.push({ name: "admin.dashboard" });
        //                 }
        //             }
        //             this.loading = false;
        //         })
        //         .catch((error) => {

        //             console.error("Error Response:", error.response); // Detailed error log

        //     if (error.response && error.response.status === 201) {
        //         // Display validation errors
        //         this.errors = error.response.data.errors || error.response.data.message;
        //     } else {
        //         // Display a generic error message for other status codes
        //         this.errors = "An unexpected error occurred.";
        //     }

        //     this.loading = false;
        //             // console.log("asif errors");
        //             // if (error.response && error.response.status === 422) {
        //             //     console.log("asif errors");
        //             //     this.errors = error.response.data.errors; // Store validation errors
        //             // } else {
        //             //     console.error("Error:", error); // Log other errors
        //             // }
        //             // this.loading = false;

        //             // if (error.response && error.response.data.message) {
        //             //     this.errors = error.response.data.message;
        //             // } else {
        //             //     this.errors = "An unexpected error occurred.";
        //             // }
        //             // this.loading = false;
        //         });
        // },

        verifyOtp() {
            this.loading = true;
            this.$store.dispatch('verifyOtp', this.form)
                .then((res) => {
                    // Check the status from the response data
                    if (res.data.status === false) {
                        this.errors = res.data.message;
                        this.loading = false;
                    } else {
                        // Successful OTP verification logic
                        this.$store.dispatch("frontendWishlist/lists").then().catch();

                        if (res.data.user_type.type === 0) {
                            if (this.carts.length > 0) {
                                this.$router.push({ name: "frontend.checkout" });
                            } else {
                                this.$router.push({ name: "admin.dashboard" });
                            }
                        } else if (res.data.user_type.type === 1) {
                            if (this.carts.length > 0) {
                                this.$router.push({ name: "frontend.checkout" });
                            } else {
                                this.$router.push({ name: "frontend.home" });
                            }
                        } else if (res.data.user_type.type === 5) {
                            if (this.carts.length > 0) {
                                this.$router.push({ name: "frontend.checkout" });
                            } else {
                                this.$router.push({ name: "admin.dashboard" });
                            }
                        }
                        this.loading = false;
                    }
                })
                .catch((error) => {
                    console.error("Error Response:", error.response);

                    if (error.response && error.response.data.message) {
                        this.errorss = error.response.data.message;
                    } else {
                        this.errorss = "An unexpected error occurred.";
                    }
                    this.loading = false;
                });
        },




        phoneNumber(e) {
            return appService.phoneNumber(e);
        },
        login: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('login', this.form).then((res) => {
                    this.loading.isActive = false;
                    console.log("user tye", res.data.user_type.type)
                    alertService.success(res.data.message);
                    this.$store.dispatch("frontendWishlist/lists").then().catch();

                    if (res.data.user_type.type === 0) {
                        console.log("success go to admin page")
                        if (this.carts.length > 0) {
                            router.push({ name: "frontend.checkout" });
                        }


                        // router.push({ name: "admin.administrators.list" });     
                        router.push({ name: "admin.dashboard" });
                    } else if (res.data.user_type.type === 1) {
                        console.log("success go to home page")

                        if (this.carts.length > 0) {
                            console.log("go to checkout");
                            router.push({ name: "frontend.checkout" });
                        } else {
                            router.push({ name: "frontend.home" });
                        }


                        // 
                    }
                    else if (res.data.user_type.type === 5) {
                        console.log("success go to pos")


                        if (this.carts.length > 0) {
                            router.push({ name: "frontend.checkout" });
                        }


                        // router.push({ name: "admin.administrators.list" });     
                        router.push({ name: "admin.dashboard" });


                        // 
                    }

                    // if(res.data.user_type.type===1){
                    //         console.log("success go to user page")
                    //     router.push({ name: "frontend.home" });     
                    // }

                    // if (this.carts.length > 0) {
                    //     router.push({ name: "frontend.checkout" });
                    // } else {
                    //     router.push({ name: "admin.dashboard" });    
                    //     // router.push({ name: "frontend.home" });
                    // }
                    // router.push({ name: "frontend.home" });
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                })
            } catch (err) {
                this.loading.isActive = false;
            }
        },
        handleField: function () {
            this.toggleValue = !this.toggleValue

            if (this.toggleValue) {
                this.form.email = "";
                this.inputLabel = this.$t('label.phone');
                this.inputButton = this.$t('label.use_email_instead');
            } else {
                this.form.phone = "";
                this.inputLabel = this.$t('label.email');
                this.inputButton = this.$t('label.use_phone_instead');
            }
        },
        countryCodeChange: function (e) {
            this.flag = e.flag_emoji;
            this.form.country_code = e.calling_code;
        },
        close: function () {
            this.errors = {}
        },
        setupCredit: function (e) {
            if (e === 'admin') {
                this.form.email = 'admin@example.com';
                this.form.password = '123456';
            } else if (e === 'customer') {
                this.form.email = 'customer@example.com';
                this.form.password = '123456';
            } else if (e === 'manager') {
                this.form.email = 'manager@example.com';
                this.form.password = '123456';
            } else if (e === 'posOperator') {
                this.form.email = 'posoperator@example.com';
                this.form.password = '123456';
            }
        }
    }
}
</script>