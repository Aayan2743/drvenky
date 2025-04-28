<template>
  <LoadingComponent :props="loading" />
  <div class="md:w-[calc(100%-340px)] lg:w-[calc(100%-320px)] xl:w-[calc(100%-377px)]">


    <form class="w-full mb-4" @submit.prevent="searchProduct">
      <div class="row">
        <div class="col-12 sm:col-6"
          :class="checkoutProps.form.category || checkoutProps.form.brand ? 'xl:col-3' : 'xl:col-4'">
          <div class="w-full flex items-center h-10 px-3 rounded-md border border-[#EFF0F6] bg-white">
            <button type="submit" class="lab-line-search ltr:mr-2 rtl:ml-2"></button>
            <input type="search" v-model="props.search.name1" placeholder="scan barcode" class="w-full">
          </div>
        </div>
        </div>  
      </form> 

    <form class="w-full mb-4" @submit.prevent="search">
      <div class="row">
        <div class="col-12 sm:col-6"
          :class="checkoutProps.form.category || checkoutProps.form.brand ? 'xl:col-3' : 'xl:col-4'">
          <div class="w-full flex items-center h-10 px-3 rounded-md border border-[#EFF0F6] bg-white">
            <button type="submit" class="lab-line-search ltr:mr-2 rtl:ml-2"></button>
            <input type="search" v-model="props.search.name" :placeholder="$t('label.search_here')" class="w-full">
          </div>
        </div>

        <div class="col-12 sm:col-6 xl:col-4">
          <div class="db-field w-full">
            <vue-select v-model="checkoutProps.form.category"
              class="db-field-control appearance-none cursor-pointer f-b-custom-select" id="customer"
              :options="categories" label-by="option" value-by="id" :closeOnSelect="true" :searchable="true"
              :clearOnClose="true" :placeholder="$t('label.select_category')"
              :search-placeholder="$t('label.search_category')" @update:modelValue="setCategory($event)" />
          </div>
        </div>

        <div class="col-12 sm:col-6 xl:col-4">
          <div class="db-field w-full">
            <vue-select v-model="checkoutProps.form.brand" class="db-field-control appearance-none cursor-pointer"
              id="customer" :options="brands" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
              :clearOnClose="true" :placeholder="$t('label.select_brand')"
              :search-placeholder="$t('label.search_brand')" @update:modelValue="setBrand($event)" />
          </div>
        </div>

        <div class="col-12 sm:col-6 xl:col-1" v-if="checkoutProps.form.category || checkoutProps.form.brand">
          <button class="db-btn-outline h-[38px] w-full flex-shrink-0 !text-[#FB4E4E] !bg-white !border-[#FB4E4E]"
            @click="reset">
            <i class="lab lab-line-reset"></i>
            <span>{{ $t("button.reset") }}</span>
          </button>
        </div>
      </div>

    </form>
    <ProductListComponent v-if="products.length > 0" :products="products" />
  </div>

  <div
    class="db-pos-cartDiv fixed top-0 ltr:right-0 rtl:left-0 w-full h-dvh rounded-none z-50 md:z-10 md:top-[85px] ltr:md:right-5 rtl:md:left-5 md:w-[322px] lg:w-[305px] xl:w-[360px] md:h-[calc(100vh-85px)] md:rounded-lg overflow-y-auto thin-scrolling bg-white">
    <div class="p-4">
      <div class="md:hidden text-right mb-3">
        <button class="db-pos-cartCls">
          <i class="lab-line-circle-cross text-lg text-[#E93C3C]"></i>
        </button>
      </div>
      <div class="db-field mb-3">
        <vue-select
          class="db-field-control text-sm rounded-lg appearance-none cursor-pointer text-heading border-[#D9DBE9]"
          id="customer" v-model="checkoutProps.form.customer_id" :options="customers" label-by="phone" value-by="id"
          :closeOnSelect="true" :searchable="true" :clearOnClose="true" :placeholder="$t('label.select_customer')"
          :search-placeholder="$t('label.search_customer')" />
      </div>
      <a href="#" @click.prevent="openAddCustomerModal" class="text-blue-500 float-right">Add customer</a>
    </div>

    
    <div v-if="showAddCustomerModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
      <h3 class="text-lg font-semibold mb-4 text-gray-700">Add New Customer</h3>
      <form @submit.prevent="submitNewCustomer">
        <!-- Customer Name Input -->
        <div class="mb-4">
          <label class="block text-gray-600 text-sm mb-1">Customer Name</label>
          <input type="text" ref="nameInput" v-model="newCustomer.name" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter name" required />
          <p v-if="errors.name" class="text-red-500 text-sm">{{ errors.name[0] }}</p>
        </div>

        <!-- Phone Number Input -->
        <div class="mb-4">
          <label class="block text-gray-600 text-sm mb-1">Phone Number</label>
          <input type="text" v-model="newCustomer.phone" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter phone number" required />
          <p v-if="errors.phone" class="text-red-500 text-sm">{{ errors.phone[0] }}</p>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-2">
          <button type="button" @click="closeAddCustomerModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Save</button>
        </div>
      </form>
    </div>
  </div>

    <div v-if="carts.length === 0" class="flex items-center justify-center">
      <img class="w-52" :src="setting.image_cart" alt="empty">
    </div>

    <ul v-if="carts.length > 0" class="p-4">
      <li v-for="(cart, index) in carts"
        class="flex items-start gap-3 pb-4 mb-4 border-b last:mb-0 last:pb-0 last:border-none border-gray-100">
        <img :src="cart.image" alt="products" class="w-28 rounded-lg flex-shrink-0" />
        <div class="relative w-full overflow-hidden">
          <h4 class="font-semibold capitalize whitespace-nowrap overflow-hidden text-ellipsis mb-1">
            {{ cart.name }}
          </h4>
          <div v-if="cart.variation_id > 0" class="flex flex-wrap mb-2">
            <span class="text-xs capitalize inline-flex items-center">{{ cart.variation_names }}</span>
          </div>
          <div class="flex flex-wrap gap-3 mb-3">
            <span class="font-semibold font-sans">
              {{
                currencyFormat(cart.price, setting.site_digit_after_decimal_point,
                  setting.site_default_currency_symbol, setting.site_currency_position)
              }}
            </span>
            <del v-if="cart.discount > 0" class="font-semibold font-sans text-[#FF6262]">
              {{
                currencyFormat(cart.old_price, setting.site_digit_after_decimal_point,
                  setting.site_default_currency_symbol, setting.site_currency_position)
              }}
            </del>
          </div>
          <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-1 w-20 p-1 rounded-full bg-[#F7F7FC]">
              <button @click.prevent="quantityDecrement(index, cart)" type="button"
                :class="cart.quantity === 1 ? 'cursor-not-allowed' : ''"
                class="lab-fill-circle-minus text-lg leading-none transition-all duration-300 hover:text-primary"></button>
              <input v-on:keypress="onlyNumber($event)" v-on:keyup="quantityUp(index, cart, $event)" type="number"
                v-model="cart.quantity" class="text-center w-full h-5 text-sm font-medium">
              <button :class="cart.quantity >= cart.stock ? 'cursor-not-allowed' : ''"
                @click.prevent="quantityIncrement(index, cart)" type="button"
                class="lab-fill-circle-plus text-lg leading-none transition-all duration-300 hover:text-primary"></button>
            </div>
            <button @click.prevent="removeProduct(index)"
              class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#FFF4F4] text-[#E93C3C] transition-all duration-300 hover:bg-[#E93C3C] hover:text-white">
              <i class="lab-line-trash text-sm"></i>
              <span class="text-xs font-medium capitalize hidden sm:block">{{ $t('button.remove') }}</span>
            </button>
          </div>
        </div>
      </li>
    </ul>

    <div class="p-4">
      <div class="flex h-[38px] mb-4" v-if="carts.length > 0">
        <div class="db-field-down-arrow">
          <select v-model="discountType"
            class="w-[120px] h-full cursor-pointer text-sm font-client ltr:rounded-tl ltr:rounded-bl rtl:rounded-tr rtl:rounded-br appearance-none border ltr:pl-3 rtl:pr-3 text-heading border-[#EFF0F6]">
            <option :value="discountTypeEnum.PERCENTAGE">{{ $t("label.percentage") }}</option>
            <option :value="discountTypeEnum.FIXED">{{ $t("label.fixed") }}</option>
          </select>
        </div>
        <input v-model="discount" type="text" v-on:keypress="floatNumber($event)"
          :placeholder="$t('label.add_discount')" class="w-full h-full border-t border-b px-3 border-[#EFF0F6]">
        <button @click.prevent="applyDiscount" type="submit"
          class="flex-shrink-0 w-16 h-full text-sm font-medium font-client capitalize ltr:rounded-tr ltr:rounded-br rtl:rounded-tl rtl:rounded-bl text-white bg-[#008BBA]">
          {{ $t('button.apply') }}
        </button>
      </div>
      <small class="db-field-alert" v-if="discountErrorMessage">{{ discountErrorMessage }}</small>
      <ul class="flex flex-col gap-1.5 mb-4">
        <li class="flex items-center justify-between">
          <span class="text-sm font-client capitalize leading-6 text-[#2E2F38]">
            {{ $t("label.sub_total") }}
          </span>
          <span class="text-sm font-client capitalize leading-6 text-[#2E2F38]">
            {{
              currencyFormat(subtotal, setting.site_digit_after_decimal_point,
                setting.site_default_currency_symbol, setting.site_currency_position)
            }}
          </span>
        </li>
        <li class="flex items-center justify-between">
          <span class="text-sm font-client capitalize leading-6">{{ $t('label.tax') }}</span>
          <span class="text-sm font-client capitalize leading-6">
            {{
              currencyFormat(totalTax, setting.site_digit_after_decimal_point,
                setting.site_default_currency_symbol, setting.site_currency_position)
            }}
          </span>
        </li>
        <li class="flex items-center justify-between">
          <span class="text-sm font-client capitalize leading-6">{{ $t("label.discount") }}</span>
          <span class="text-sm font-client capitalize leading-6">
            {{
              currencyFormat(posDiscount,
                setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                setting.site_currency_position)
            }}
          </span>
        </li>

        <li class="flex items-center justify-between">
          <span class="text-sm font-medium font-client capitalize leading-6 text-[#2E2F38]">
            {{ $t("label.total") }}
          </span>
          <span class="text-sm font-medium font-client capitalize leading-6 text-[#2E2F38]">
            {{
              currencyFormat((subtotal + totalTax) - posDiscount,
                setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                setting.site_currency_position)
            }}
          </span>
        </li>
      </ul>

      <!-- new code -->

      <div class="flex items-center justify-center gap-6" v-if="carts.length > 0">
  <!-- Radio buttons for payment methods -->
  <div class="flex gap-4 mb-4">
    <label>
      <input type="radio" v-model="paymentMethod" value="cash" class="mr-2" />
      Cash
    </label>
    <label>
      <input type="radio" v-model="paymentMethod" value="upi" class="mr-2" />
      UPI
    </label>
    <label>
      <input type="radio" v-model="paymentMethod" value="card" class="mr-2" />
      Card
    </label>
  </div>

  <!-- Conditionally show text boxes based on selected payment method -->
  <div v-if="paymentMethod === 'cash'">
    <div class="mb-4">
      <label for="receivedAmount" class="block text-sm font-medium">Received Amount</label>
      <input type="number" id="receivedAmount" v-model="receivedAmount" class="w-full px-4 py-2 border rounded-md" placeholder="Enter received amount" />
    </div>
    <div class="mb-4">
      <label for="balanceAmount" class="block text-sm font-medium">Balance Amount</label>
      <input type="number" id="balanceAmount" :value="balanceAmount" class="w-full px-4 py-2 border rounded-md" placeholder="Balance amount" readonly />

    </div>
  </div>

  <div v-if="paymentMethod === 'upi'">
    <div class="mb-4">
      <label for="transactionId" class="block text-sm font-medium">UPI Transaction ID</label>
      <input type="text" id="transactionId" v-model="transactionName" class="w-full px-4 py-2 border rounded-md" placeholder="Enter UPI Transaction ID"  />
    </div>
  </div>

  <div v-if="paymentMethod === 'card'">
    <div class="mb-4">
      <label for="cardNumber" class="block text-sm font-medium">Card Transaction ID</label>
      <input type="text" id="cardNumber" v-model="cardNumber" class="w-full px-4 py-2 border rounded-md" placeholder="Enter Card Transaction ID" />
    </div>
  </div>
</div>

      



      <!-- end new code -->

      <div class="flex items-center justify-center gap-6" v-if="carts.length > 0">
        <button @click.prevent="resetCart"
          class="capitalize text-sm font-medium leading-6 font-client w-full text-center rounded-3xl py-2 text-white bg-[#FB4E4E]">
          {{ $t('button.cancel') }}
        </button>
        <button @click.prevent="orderSubmit"
          class="capitalize text-sm font-medium leading-6 font-client w-full text-center rounded-3xl py-2 text-white bg-[#1AB759]">
          {{ $t('button.order') }}
        </button>
      </div>


    </div>
  </div>

  <button
    class="db-pos-cartBtn fixed md:hidden bottom-0 left-0 z-10 w-full h-14 py-4 text-center flex items-center justify-center shadow-xl-top gap-3 bg-primary text-white">
    <i class="lab-fill-bag text-xl"></i>
    <span class="text-base font-medium"> {{ totalProducts() }} {{ $t('label.products') }} - {{
      currencyFormat((subtotal + totalTax) - posDiscount,
        setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
        setting.site_currency_position)
    }}</span>
  </button>

  <ReceiptComponent :order="order" />


 
 
  <div
      id="variation-modal"
      :class="{'opacity-100 visible': showModal, 'opacity-0 invisible': !showModal}"
      class="fixed inset-0 z-50 p-3 w-screen h-dvh overflow-y-auto bg-black/50 transition-all duration-500"
      v-if="showModal"
    >
      <div class="w-full rounded-xl mx-auto bg-white transition-all duration-500 max-w-4xl">
        <div class="flex items-center justify-between gap-2 py-4 px-4 border-b border-slate-100">
          <h3 class="text-lg font-bold capitalize">{{ $t('label.product_variation') }}</h3>
          <button @click="resetdata" type="button" class="lab-line-circle-cross text-lg text-[#E93C3C]"></button>
        </div>

        <!-- Product Details Component -->
        <ProductDetailsComponent :method="resetdata" :productId="productId" />
      </div>
    </div>

</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import ProductListComponent from "./ProductListComponent";
import sourceEnum from "../../../enums/modules/sourceEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import paymentTypeEnum from "../../../enums/modules/paymentTypeEnum";
import roleEnum from "../../../enums/modules/roleEnum";
import appService from "../../../services/appService";
import discountTypeEnum from "../../../enums/modules/discountTypeEnum";
import alertService from "../../../services/alertService";
import targetService from "../../../services/targetService";
import ReceiptComponent from "./ReceiptComponent";
import ProductDetailsComponent from "./ProductDetailsComponent.vue";
// import ENV from './config/env';
// import { API_URL } from '../../config/env'; 


import axios from 'axios'
export default {
  name: "PosComponent",
  components: {
    ReceiptComponent,
    LoadingComponent,
    ProductListComponent,
    ProductDetailsComponent
  },
  data() {
    return {

      paymentMethod: 'cash', // default to 'cash'
   


      paymentTypeEnum: {
        CASH_ON_DELIVERY: 'cash',
        UPI: 'upi',
        CARD: 'card'
      },

      // paymentMethod: '', // Holds the selected payment method (cash, upi, card)
    receivedAmount: '', // Holds the received amount for cash payment
    balanceAmount: '', // Holds the balance amount for cash payment
    transactionName: '', // Holds the transaction name for UPI payment
    cardNumber: '',

      newCustomer: {
        name: '',
        phone: ''
        // Add additional fields as needed
      },
      errors: {},
      showAddCustomerModal: false,
      productId: "",
      showModal: false,
      loading: {
        isActive: false,
      },
      order: {},
      discount: null,
      checkoutProps: {
        form: {
          customer_id: null,
          category: null,
          brand: null,
          discount: 0,
          total: 0,
        },
      },
      props: {
        search: {
          paginate: 0,
          order_column: "id",
          order_type: "asc",
          name: "",
          product_category_id: "",
          product_brand_id: "",
          status: statusEnum.ACTIVE,
          barcode_id:"",
        },
      },
      searchProps: {
        paginate: 0,
        order_column: "id",
        order_type: "asc",
        status: statusEnum.ACTIVE
      },
      settings: {
        itemsToShow: 6.2,
        wrapAround: false,
        snapAlign: "start"
      },
      statusEnum: statusEnum,
      discountTypeEnum: discountTypeEnum,
      discountType: discountTypeEnum.PERCENTAGE,
      discountErrorMessage: "",
      form: {},
    }
  },
  computed: {
   

    balanceAmount() {
    // Calculate the balance amount by subtracting the total from the received amount
    return this.receivedAmount - this.total;
  },

    setting: function () {
      return this.$store.getters['frontendSetting/lists'];
    },
    categories: function () {
      return this.$store.getters["productCategory/depthTrees"];
    },
    brands: function () {
      return this.$store.getters["productBrand/lists"];
    },
    products: function () {
      return this.$store.getters["product/lists"];
    },
    customers: function () {

      const chcl=this.$store.getters['user/lists']
      console.log("customer",chcl)
    
      return this.$store.getters['user/lists'];
    },
    
    customers_list: function () {

  
    return this.$store.getters['user/lists_customers'];
    },

    carts: function () {
      return this.$store.getters['posCart/lists'];
    },
    subtotal: function () {
      return this.$store.getters['posCart/subtotal'];
    },
    total: function () {
      return this.$store.getters['posCart/total'];
    },
    posCartDiscount: function () {
      return this.$store.getters['posCart/discount'];
    },
    totalTax: function () {
      return this.$store.getters['posCart/totalTax'];
    },
    posCartProducts: function () {
      return this.$store.getters['posCart/lists'];
    },
    posDiscount: function () {
      return this.$store.getters['posCart/discount'];
    }
  },
  mounted() {
    this.productCategories();
    this.productBrands();
    this.productList();
    try {
      this.loading.isActive = true;
      this.$store.dispatch('user/lists_customers', {
        order_column: 'id',
        order_type: 'asc',
        status: statusEnum.ACTIVE,
        role_id: roleEnum.CUSTOMER
      }).then((res) => {

        console.log(res);
        this.checkoutProps.form.customer_id = res.data.data[0].id;
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });

      this.loading.isActive = true;
      this.$store.dispatch("company/lists").then((res) => {
        this.company.name = res.data.data.company_name;
        this.company.email = res.data.data.company_email;
        this.company.phone = res.data.data.company_phone;
        this.company.address = res.data.data.company_address;
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });
    } catch (err) {
      this.loading.isActive = false;
    }
  },

  watch: {
    paymentMethod(newValue) {
      // Map the string value to the correct enum value
      if (newValue === 'cash') {
       
        this.form.payment_method = "Cash";
        this.form.receivedAmount = this.balanceAmount;
        

      } else if (newValue === 'upi') {
      
        this.form.payment_method = "UPI";
        this.form.receivedAmount = this.transactionName;
      } else if (newValue === 'card') {
       
        this.form.payment_method = "Card";
        this.form.receivedAmount = this.cardNumber;
      }
    }
  },


  // new code start

// Helper method to reset the product and temp data
resetTempAndProductArray: function () {
    // Clear the product array and reset the temporary product data to the initial state
    this.productArray = {};
    this.temp = { ...this.initProduct }; // Reset temp to initial product state
    this.selectedVariation = null; // Clear any selected variation
},

// new code end



  methods: {
    addToCart: function (product) {
    // Log product to the console for debugging
    console.log("Product to add:", product);

    // Populate the product data for the cart
    this.productArray = {
        name: product.name,
        product_id: product.id,
        image: product.media && product.media.length > 0
            ? product.media[0].manipulations.preview_url
            : 'default-image-url.png', // Default image if none is available
        variation_names: product.name,
        // variation_id: null,
        sku: product.sku,
        stock: product.stock,
        taxes: product.taxes,
        quantity: 1,
        discount: 0,
        price: product.selling_price,
        old_price: product.selling_price,
        total_price: product.selling_price
    };

    // Helper function to reset state after cart actions
    const resetState = () => {
        this.enableAddToCardButton = false;  // Disable button after action
        this.resetTempAndProductArray();     // Reset the product array
    };

    // Add product variation if available
    const addToCartWithVariation = () => {
        // Dispatch to get variation ancestors as a string
        this.$store.dispatch("posProductVariation/ancestorsToString", this.selectedVariation.id)
            .then((res) => {
                // Update the variation names after dispatch success
                this.productArray.variation_names = res.data.data;

                // Now, add the product to the cart
                return this.$store.dispatch("posCart/lists", this.productArray);
            })
            .then(() => {
                // Hide modal after adding to cart
                targetService.hideTarget("variation-modal", 'modal-active');
                
                // Trigger any additional actions after cart update
                this.method();

                // Show success message
                alertService.success(this.$t('message.add_to_cart'));

                // Reset state after success
                resetState();
            })
            .catch((err) => {
                // Log error for debugging
                console.error('Error during addToCart with variation:', err);

                // Show error message
                alertService.error(this.$t('message.error_add_to_cart'));

                // Reset state on failure
                resetState();
            });
    };

    // Add product to cart without variation direct old code with out barcode search
    const addToCartWithoutVariation = () => {
        // Directly add the product to the cart
        this.$store.dispatch("posCart/lists", this.productArray)
            .then(() => {
                // Hide modal after adding to cart
                targetService.hideTarget("variation-modal", 'modal-active');
                
                // Call any additional method needed after cart update
                this.method();

                // Show success message
                alertService.success(this.$t('message.add_to_cart'));

                // Reset state after success
                resetState();
            })
            .catch((err) => {
                // Log error for debugging
                console.error('Error during addToCart without variation:', err);

                // Show error message
                alertService.error(this.$t('message.error_add_to_cart'));

                // Reset state on failure
                resetState();
            });
    };

    // Check if there is a selected variation and proceed accordingly
    if (this.selectedVariation) {
        addToCartWithVariation();  // Add with variation
    } else {
        addToCartWithoutVariation();  // Add without variation
    }
},



    orderSubmit() {

      console.log('Payment method selected:', this.paymentMethod);

    if (this.paymentMethod === 'cash') {
      console.log('Cash payment selected');
      console.log('Received Amount:', this.receivedAmount);
      console.log('Balance Amount:', this.balanceAmount);
      this.form.receivedAmount = this.balanceAmount;
     

    } else if (this.paymentMethod === 'upi') {
      console.log('UPI payment selected');
      console.log('Transaction Name:', this.transactionName);
      this.form.receivedAmount = this.transactionName;
   

    } else if (this.paymentMethod === 'card') {
      console.log('Card payment selected');
      console.log('Card Number:', this.cardNumber);
      this.form.receivedAmount = this.cardNumber;
    
    }

    // Proceed with order submission logic
  },

    getcustomerlist(){
      try {
      this.loading.isActive = true;
      this.$store.dispatch('user/lists', {
        order_column: 'id',
        order_type: 'asc',
        status: statusEnum.ACTIVE,
        role_id: roleEnum.CUSTOMER
      }).then((res) => {

        console.log(res);
        this.checkoutProps.form.customer_id = res.data.data[0].id;
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });

      
    } catch (err) {
      this.loading.isActive = false;
    }
  },
    

    openAddCustomerModal() {
      this.showAddCustomerModal = true;
      this.$nextTick(() => {
        this.$refs.nameInput.focus();
      });
    },
    closeAddCustomerModal() {
      this.showAddCustomerModal = false;
    },
   
    async submitNewCustomer_old() {
  console.log(this.newCustomer);

  // Clear previous key
  this.errors = {};

  // Define the API URL from the environment variable
  const apiUrl = process.env.MIX_HOST;

  try {
    // Make the API call to add a new customer
    const response = await axios.post(`${apiUrl}/api/add_customer`, this.newCustomer);

    if (response.status === 200) {
      // Successfully added the customer
      const addedCustomer = response.data;

      const addedCustomerId = response.data;

      console.log("new customer details",addedCustomer)

      console.log("new customer details id",addedCustomerId)

      // Add the new customer to the local customers list
      this.customers.push(addedCustomer);

      // Refresh Vuex store to ensure consistency
      await this.$store.dispatch("user/list");

      // Automatically select the newly added customer in the dropdown
      this.checkoutProps.form.customer_id = addedCustomerId.id;

      // Reset the form and close the modal
      this.newCustomer = { name: '', phone: '' };
      this.closeAddCustomerModal();
    } else {
      console.error("Error adding customer:", response);
    }
  } catch (error) {
    if (error.response && error.response.status === 422) {
      // Handle validation errors
      this.errors = error.response.data.errors; // Set validation errors
    } else {
      console.error("Failed to add customer:", error);
    }
  }
},

async submitNewCustomer() {
  console.log(this.newCustomer);

  // Clear previous errors
  this.errors = {};

  // Define the API URL from the environment variable
  const apiUrl = process.env.MIX_HOST;

  try {
    // Make the API call to add a new customer
    const response = await axios.post(`${apiUrl}/api/add_customer`, this.newCustomer);

    if (response.status === 200) {
      // Successfully added the customer
      const addedCustomer = response.data;

      const addedCustomerId = response.data;

      console.log("new customer details",addedCustomer)

      console.log("new customer details id",addedCustomerId)

      // Add the new customer to the local customers list
      this.customers.push(addedCustomer);

      // Refresh Vuex store to ensure consistency
      await this.$store.dispatch("user/list");

      // Automatically select the newly added customer in the dropdown
      this.checkoutProps.form.customer_id = addedCustomerId.id;

      // Reset the form and close the modal
      this.newCustomer = { name: '', phone: '' };
      this.closeAddCustomerModal();
    } else {
      console.error("Error adding customer:", response);
    }
  } catch (error) {
    if (error.response && error.response.status === 422) {
      // Handle validation errors
      this.errors = error.response.data.errors; // Set validation errors
    } else {
      console.error("Failed to add customer:", error);
    }
  }
},



    toggleModal() {
      this.showModal = !this.showModal; // Toggle modal visibility
    },

    resetdata() {
      // Your reset logic here
      console.log("Resetting data...");
      targetService.hideTarget("variation-modal", 'modal-active');
      // For example, to close a modal or reset some values:
      this.showModal = false;
      this.productId = null;
      console.log("showModal:", this.showModal); // Should log "false"
      console.log("productId:", this.productId); // Should log "null"
    },
   
    async searchProduct() {
      // Fetch product data by barcode number (props.search.name1)

      const apiUrl = process.env.MIX_HOST;

      console.log(apiUrl);

     // return false;
      try {
         //`${import.meta.env.VITE_API_URL}
        // const response = await axios.get(`apiUrl/api/search_byid/${this.props.search.name1}`);
        // this.$store.dispatch("posProduct/show", this.props.search).then((res) => {
        const response = await axios.get(`${apiUrl}/api/search_byid/${this.props.search.name1}`);
        
        console.log(response.data);


        if (response.data && response.data.status) {
    const product = Array.isArray(response.data.product) ? response.data.product[0] : response.data.product;
    
    if (product) {
     // alert(product.id);
      this.toggleModal(); 
        this.productId = product.id; // Set the correct product ID to trigger the popup
    } else {
        alert('Product not found');
    }
} else {
    alert('Product not found');
}






      } catch (error) {
        console.error("Error fetching product data:", error);
        alert("There was an error searching for the product.");
      }
    },



    addToCart1(product) {
  // Assuming you have a cart array to store products
  const existingProduct = this.cart.find(item => item.id === product.id);

  if (existingProduct) {
    existingProduct.quantity += 1; // Increment quantity if the product already exists
  } else {
    this.cart.push({ ...product, quantity: 1 }); // Add new product to the cart
  }
  
  console.log("Current Cart:", this.cart);
},



    async searchProduct1() {
  const apiUrl = process.env.MIX_HOST;

  console.log("api url check",apiUrl);

  try {
    
    const response = await axios.get(`${apiUrl}/api/search_byid/${this.props.search.name1}`);
    const product_id=response.data.product[0].id;
    console.log("id",response.data.product[0].id);
   
    const response1 = await axios.get(`${apiUrl}/api/admin/product/pos-product/${response.data.product[0].id}`);
    
console.log("one",response)
console.log("two",response1)

//  return 

    if (response1.data && response1.data.data) {

      console.log("API Response:", response1.data.data);
      const product = response1.data.data;
      // const product = Array.isArray(response1.data.data) ? response1.data.data : response1.data.data;
      console.log("product",product);

      
      if (product) {
        this.addToCart(product); // Directly add the product to the cart
        alert(`Product ${product.name} has been added to the cart!`);
      } else {
        alert('Product not found no');
      }
    } else {
      alert('Product not found wrong');
    }
  } catch (error) {
    console.error("Error fetching product data:", error);
    alert("There was an error searching for the product.");
  }
},


    reset() {
        this.productId = null;
        this.showModal = false; // Close the modal
    },
   



    
    hideTarget: function (id, cClass) {
      targetService.hideTarget(id, cClass);
    },
    onlyNumber: function (e) {
      return appService.onlyNumber(e);
    },
    floatNumber: function (e) {
      return appService.floatNumber(e);
    },
    currencyFormat(amount, decimal, currency, position) {
      return appService.currencyFormat(amount, decimal, currency, position);
    },
    floatFormat(amount, decimal) {
      return appService.floatFormat(amount, decimal);
    },
    resett: function () {
      this.props.search.name = "";
      this.checkoutProps.form.category = null;
      this.props.search.product_category_id = "";
      this.checkoutProps.form.brand = null;
      this.props.search.product_brand_id = "";
      this.productList();
    },
    search: function () {
      this.productList();
    },

    search1: function () {
      this.productList1();
    },
    productCategories: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store.dispatch("productCategory/depthTrees", this.searchProps).then((res) => {
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });
    },
    productBrands: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store.dispatch("productBrand/lists", this.searchProps).then((res) => {
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });
    },
    productList: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store.dispatch("product/lists", this.props.search).then((res) => {
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });
    },

    productList1: function (page = 1) {
      this.loading.isActive = true;
      // this.props.search.page = page;
      this.$store.dispatch("product/lists1", this.props.search.barcode_id).then((res) => {
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });
    },

    setCategory: function (id) {
      this.props.search.product_category_id = id;
      this.productList();
    },
    setBrand: function (id) {
      this.props.search.product_brand_id = id;
      this.productList();
    },
    quantityUp: function (id, product, e) {
      let quantity = e.target.value;

      if (quantity === 0 || quantity < 0 || quantity === "0") {
        quantity = 1;
      }
      if (quantity > product.stock) {
        quantity = product.stock
      }
      this.$store.dispatch('posCart/quantity', { id: id, status: quantity }).then().catch();
    },
    quantityIncrement: function (id, product) {
      let quantity = product.quantity;
      quantity++;
      if (quantity <= 0) {
        quantity = 1;
      }

      if (quantity > product.stock) {
        quantity--;
      }
      this.$store.dispatch('posCart/quantity', { id: id, status: quantity }).then().catch();
    },
    quantityDecrement: function (id, product) {
      let quantity = product.quantity;
      quantity--;
      if (quantity <= 0) {
        quantity = 1;
      }
      this.$store.dispatch('posCart/quantity', { id: id, status: quantity }).then().catch();
    },
    removeProduct: function (id) {
      this.$store.dispatch('posCart/remove', { id: id }).then().catch();
    },
    applyDiscount: function () {
      this.discountErrorMessage = "";
      if (this.discountType === discountTypeEnum.FIXED) {
        if (this.subtotal < this.discount) {
          this.discountErrorMessage = this.$t('message.discount_fixed_error_message');
        } else {
          this.checkoutProps.form.discount = parseFloat(+this.discount).toFixed(this.setting.site_digit_after_decimal_point);
          this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();
        }
      } else {
        if (this.discount > 100) {
          this.discountErrorMessage = this.$t('message.discount_error_message');
        } else {
          this.checkoutProps.form.discount = parseFloat((this.subtotal * this.discount) / 100).toFixed(this.setting.site_digit_after_decimal_point);
          this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();

        }
      }
    },
    resetCart: function () {
      this.$store.dispatch('posCart/resetCart').then(res => {
      }).catch();
    },
    orderSubmit: function () {
      this.loading.isActive = true;
      if (this.paymentMethod === 'cash') {
    this.form = {
      customer_id: this.checkoutProps.form.customer_id,
      subtotal: this.subtotal,
      discount: parseFloat(this.posCartDiscount),
      tax: this.totalTax,
      total: this.total,
      order_type: orderTypeEnum.POS,
      source: sourceEnum.POS,
      payment_method: this.paymentTypeEnum.CASH_ON_DELIVERY, // Mapping to enum
      receivedAmount: this.receivedAmount, // Cash received amount
      products: JSON.stringify(this.posCartProducts)
    };
  } else if (this.paymentMethod === 'upi') {
    this.form = {
      customer_id: this.checkoutProps.form.customer_id,
      subtotal: this.subtotal,
      discount: parseFloat(this.posCartDiscount),
      tax: this.totalTax,
      total: this.total,
      order_type: orderTypeEnum.POS,
      source: sourceEnum.POS,
      receivedAmount: this.transactionName,
      payment_method: this.paymentTypeEnum.UPI, // Mapping to enum
      transaction_name: this.transactionName, // UPI transaction name
      products: JSON.stringify(this.posCartProducts)
    };
  } else if (this.paymentMethod === 'card') {
    this.form = {
      customer_id: this.checkoutProps.form.customer_id,
      subtotal: this.subtotal,
      discount: parseFloat(this.posCartDiscount),
      tax: this.totalTax,
      total: this.total,
      order_type: orderTypeEnum.POS,
      source: sourceEnum.POS,
      receivedAmount: this.cardNumber,
      payment_method: this.paymentTypeEnum.CARD, // Mapping to enum
      card_number: this.cardNumber, // Card number
      products: JSON.stringify(this.posCartProducts)
    };
  }

  // Send the form data to your backend (e.g., via an API request)
  console.log('Form Data:', this.form);
  // Your API call logic here

  this.loading.isActive = false; 
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      // this.form = {
      //   customer_id: this.checkoutProps.form.customer_id,
      //   subtotal: this.subtotal,
      //   discount: parseFloat(this.posCartDiscount),
      //   tax: this.totalTax,
      //   total: this.total,
      //   order_type: orderTypeEnum.POS,
      //   source: sourceEnum.POS,
      //   payment_method: '',
       
      
      
      //   products: JSON.stringify(this.posCartProducts)
      // }

      this.$store.dispatch('posOrder/save', this.form).then(orderResponse => {
        this.$store.dispatch('posCart/resetCart').then(res => {
          this.discount = null;
          this.loading.isActive = false;
        }).catch();
        alertService.success(this.$t('message.pos_order'));
        this.$store.dispatch('posOrder/show', orderResponse.data.data.id).then(res => {
          console.log("order details",res.data.data)
          this.order = res.data.data;
          this.loading.isActive = false;
        }).catch((error) => {
          this.loading.isActive = false;
          alertService.error(error.response.data.message);
        });
        appService.modalShow('#posReceiptModal');
      }).catch((err) => {
        this.loading.isActive = false;
        if (typeof err.response.data.errors === 'object') {
          _.forEach(err.response.data.errors, (error) => {
            alertService.error(error[0]);
          });
        }
      });
    },
    totalProducts: function () {
      if (this.carts.length > 0) {
        let totalProduct = 0;
        this.carts.forEach(cart => {
          totalProduct += cart.quantity;
        });
        return totalProduct;
      }
    }
  }
 }

</script>


<style scoped>
.opacity-100.visible {
  opacity: 1;
  visibility: visible;
}
.opacity-0.invisible {
  opacity: 0;
  visibility: hidden;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
}
</style>