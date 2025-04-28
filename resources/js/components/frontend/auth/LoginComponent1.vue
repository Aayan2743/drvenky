<template>
    <div>
      <div v-if="!otpSent">
        <input v-model="phone" type="text" placeholder="Enter phone number" />
        <button @click="sendOTP">Send OTP</button>
      </div>
  
      <div v-if="otpSent">
        <input v-model="otp" type="text" placeholder="Enter OTP" />
        <button @click="verifyOTP">Verify OTP</button>
      </div>
  
      <div v-if="message" :class="{'success': isSuccess, 'error': !isSuccess}">
        {{ message }}
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';

  import router from "../../../router";
  import LoadingComponent from "../components/LoadingComponent";
  import alertService from "../../../services/alertService";
  import appService from "../../../services/appService";
  import ENV from "../../../config/env";

  export default {
    data() {
      return {
        phone: '',
        otp: '',
        otpSent: false,
        message: '',
        isSuccess: false,
      };
    },
   
    methods: {
  sendOTP() {
    axios.post('http://localhost:8000/api/auth/send-otp', { phone: this.phone })
      .then(response => {
        this.otpSent = true;   // Marks OTP as sent
        this.message = response.data.message;  // Sets success message
        this.isSuccess = true;   // Marks the operation as successful
      })
      .catch(error => {
        if (error.response) {
          // Response from backend
          console.log(error.response.data);
          this.message = error.response.data.message || 'Something went wrong';
        } else if (error.request) {
          // No response from backend
          console.log(error.request);
          this.message = 'No response from the server';
        } else {
          // Some other error
          console.log('Error', error.message);
          this.message = error.message || 'An unknown error occurred';
        }
        this.isSuccess = false;  // Marks the operation as failed
      });
  },
        verifyOTP() {
            axios.post('http://localhost:8000/api/auth/verify-otp', {
            phone: this.phone,   // The phone number entered by the user
            otp: this.otp        // The OTP entered by the user
            })
            .then(response => {
            this.isSuccess = true;  // Marks the operation as successful
            this.message = response.data.message;  // Success message from backend
            console.log(response.data);
            const userType = response.data.user_type.type;
              console.log(userType);
            // Redirect as per user type (same as login)
            if (userType === 0) {
                router.push({ name: "admin.dashboard" });
            } else if (userType === 1) {
                router.push({ name: "frontend.home" });
            } else if (userType === 5) {
                router.push({ name: "admin.dashboard" });
            }

            })
            .catch(error => {
            if (error.response) {
                // Response from backend
                console.log(error.response.data);
                this.message = error.response.data.message || 'Something went wrong';
            } else if (error.request) {
                // No response from backend
                console.log(error.request);
                this.message = 'No response from the server';
            } else {
                // Some other error
                console.log('Error', error.message);
                this.message = error.message || 'An unknown error occurred';
            }
            this.isSuccess = false;  // Marks the operation as failed
            });
        }
},
  }
      
  </script>

  
  <style scoped>
  .success {
    color: green;
  }
  .error {
    color: red;
  }
  </style>
  