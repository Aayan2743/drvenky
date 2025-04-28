<template>
    <div class="bg-white rounded-2xl shadow-card">
        <div class="p-4 border-b border-[#EFF0F6]">
            <h3 class="text-lg font-semibold capitalize">{{ $t('label.order_summery') }}</h3>
        </div>

        <ul class="flex flex-col gap-3 p-4 border-b border-[#EFF0F6]">
            <li class="flex items-center justify-between">
                <span class="capitalize">{{ $t('label.subtotal') }}</span>
                <span class="font-medium">{{ currencyFormat(subtotal, setting.site_digit_after_decimal_point,
                    setting.site_default_currency_symbol, setting.site_currency_position) }}</span>
            </li>

             <li class="flex items-center justify-between">
                <span class="capitalize">Cost (Without - Tax)</span>
                <span class="font-medium">{{ currencyFormat(subtotal- totalTax, setting.site_digit_after_decimal_point,
                    setting.site_default_currency_symbol, setting.site_currency_position) }} </span>
            </li>    


            <!-- <li class="flex items-center justify-between">
                <span class="capitalize">{{ $t('label.tax') }} </span>
                <span class="font-medium">{{ currencyFormat(totalTax, setting.site_digit_after_decimal_point,
                    setting.site_default_currency_symbol, setting.site_currency_position) }} </span>
            </li> -->

            <li class="flex items-center justify-between">
                    <span class="capitalize">
                       
                                  <div v-if="isTelangana">
                                    {{ $t('label.tax_fee') + ' '+'( IGST)'  }}:
                                </div>
                                <div v-else>
                                    {{ $t('label.tax_fee') + ' '+'( CGST)'  }} <br>
                                    {{ $t('label.tax_fee') + ' '+'( SGST)'  }}:
                                </div>

                    </span>

                    <div v-if="isTelangana">
                 <p>Rs. {{ igstAmount }}</p>
                </div>
                <div v-else>
                    <p>Rs. {{ cgstAmount }}</p>
                    <p>Rs. {{ sgstAmount }}</p>
                </div>

                

                    <!-- <span class="font-medium">
                        <template v-if="igstAmount > 0">
                            {{ currencyFormat(igstAmount, setting.site_digit_after_decimal_point,
                            setting.site_default_currency_symbol, setting.site_currency_position) }}
                        </template>
                        <template v-else>
                            {{ currencyFormat(cgstAmount + sgstAmount, setting.site_digit_after_decimal_point,
                            setting.site_default_currency_symbol, setting.site_currency_position) }}
                        </template>
                    </span> -->
                </li>
    

            <li class="flex items-center justify-between">
                <span class="capitalize">{{ $t('label.shipping_charge') }}</span>
                <span class="font-medium">{{ currencyFormat(shippingCharge, setting.site_digit_after_decimal_point,
                    setting.site_default_currency_symbol, setting.site_currency_position) }}</span>
            </li>
            <li class="flex items-center justify-between">
                <span class="capitalize">{{ $t('label.discount') }}</span>
                <span class="font-medium">{{ currencyFormat(discount, setting.site_digit_after_decimal_point,
                    setting.site_default_currency_symbol, setting.site_currency_position) }}</span>
            </li>
        </ul>
        <div class="p-4">
            <dl class="flex items-center justify-between">
                <dt class="font-semibold capitalize">{{ $t('label.total') }}</dt>
                <dd class="font-semibold">{{ currencyFormat(total, setting.site_digit_after_decimal_point,
                    setting.site_default_currency_symbol, setting.site_currency_position) }}</dd>
            </dl>
        </div>


      

    </div>
</template>

<script>
import appService from "../../../services/appService";

export default {

    props: {
    // isTelangana: Boolean,
    stateName: String // Ensure this prop is also declared
  },
  mounted() {
    console.log("isTelangana Prop:", this.isTelangana); // Should be true/false
    console.log("Received State:", this.stateName); // Should be "Telangana" or other
    console.log("Received Inside:", this.getShippingAddress_inside.state); // Should be "Telangana" or other
  },

   

    name: "SummeryComponent",
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        subtotal: function () {
            return this.$store.getters['frontendCart/subtotal'];
        },
        discount: function () {
            return this.$store.getters['frontendCart/discount'];
        },
        totalTax: function () {
            // console.log("total tax", this.$store.getters['frontendCart/totalTax'] );
            return this.$store.getters['frontendCart/totalTax'];
        },
        shippingCharge: function () {
            return this.$store.getters['frontendCart/shippingCharge'];
        },
        total: function () {
            return this.$store.getters['frontendCart/total'];
        },

        igstAmount() {
            return this.isTelangana ? parseFloat(this.calculateTax('IGST', this.totalTax).toFixed(2)) : 0;
        },
        cgstAmount() {
                return !this.isTelangana ? parseFloat(this.calculateTax('CGST', this.totalTax).toFixed(2)) : 0;
            },
            sgstAmount() {
                return !this.isTelangana ? parseFloat(this.calculateTax('SGST', this.totalTax).toFixed(2)) : 0;
            },
                    
        getShippingAddress_inside: function () {

           

                return this.$store.getters['frontendCart/shippingAddress'];


        },
            isTelangana() {
            console.log("Checking State inside:", this.getShippingAddress_inside?.state);
            return this.getShippingAddress_inside?.state != "Telangana";
        }


        
    },


    methods: {
        currencyFormat(amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
       
        calculateTax(type, amount) {
        if (type === 'IGST') {
            return amount / 1; // 18% IGST
                } else if (type === 'CGST' || type === 'SGST') {
                    return amount / 2; // 9% each for CGST and SGST
                }
                return 0;
            }

    }
}
</script>