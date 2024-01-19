<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, usePage} from '@inertiajs/vue3';
import {onMounted, ref} from 'vue';
import {StripeElements, StripeElement} from 'vue-stripe-js';
import AppSimpleLogo from "@/Components/AppSimpleLogo.vue";

const props = usePage().props;
const stripeKey = props.stripeKey;

const stripeInputStyle = {
  base: {
    lineHeight: '1.5em',
    fontSize: '16px'
  }
}

const instanceOptions = ref({
  // https://stripe.com/docs/js/initializing#init_stripe_js-options
})
const elementsOptions = ref({
  // https://stripe.com/docs/js/elements_object/create#stripe_elements-options
})
const cardNumberOptions = ref({
  // https://stripe.com/docs/js/elements_object/create#stripe_elements-options
  style: stripeInputStyle,
  placeholder: 'XXX-XXX-XXXX',
})
const postalCodeOptions = ref({
  style: stripeInputStyle,
  placeholder: 'XXXXXX',
})
const cvcOptions = ref({
  style: stripeInputStyle,
  placeholder: 'XXX',
})
const expiryOptions = ref({})

const cardNumber = ref()
const cardExpiry = ref()
const cardCvc = ref()
const stripeElement = ref()

const cardNameError = ref('');
const cardNumberError = ref('');
const cardExpiryError = ref('');
const cardCvcError = ref('');
const postalCodeError = ref('');

// ---------------------

const {data, totalPrice} = defineProps({
  data: Object,
  totalPrice: String
});

const promotionCode = ref();
const cardName = ref('');
const billingState = ref('')
const billingPostalCode = ref('')
const termsAndPrivacyAgreement = ref(false)
const isValidCoupon = ref(true);
const isOrderProcess = ref(false);

function submitPayment() {
  // Get stripe element
  const cardElement = cardNumber.value.stripeElement
  const cardNameCheck = cardName.value
  const postalCardCheck = billingPostalCode.value

  handleStripeElementValidation(cardNumber.value.stripeElement, cardNumberError);
  handleStripeElementValidation(cardExpiry.value.stripeElement, cardExpiryError);
  handleStripeElementValidation(cardCvc.value.stripeElement, cardCvcError);
  handleInputChange(cardNameCheck, cardNameError);
  handleInputChange(postalCardCheck, postalCodeError);

  function handleInputChange(value, errorRef) {
    if (value === '') {
      errorRef.value = 'Field is Required';
    } else {
      errorRef.value = '';
    }
  }

  function handleStripeElementValidation(element, errorRef) {
    element.on('change', (event) => {
      if (event.error) {
        console.log(event.error);
        errorRef.value = event.error.message;
      } else {
        errorRef.value = '';
      }
    });
  }

    // Access instance methods, e.g. createToken()
    stripeElement.value.instance.createToken(cardElement, {
      name: cardName.value,
      address_state: billingState.value,
    }).then((res) => {
      if (res.token.id) {
        isOrderProcess.value = true;
        cardNumberOptions.value = {disabled: true}
        postalCodeOptions.value = {disabled: true}
        cvcOptions.value = {disabled: true}
        expiryOptions.value = {disabled: true}
        router.post('/subscribe-plan', {
          token_id: res.token.id,
          name: cardName.value,
          post_code: billingPostalCode.value,
          checkout_policy_agreement: termsAndPrivacyAgreement.value,
          state: billingState.value,
        }, {
          onSuccess: res => {
            console.log(res);
          }
        })
      }
    })
}

// Function to call the backend and calculate the total price
const calculateTotalPrice = async () => {
  router.visit('/checkout', {
    data: {
      plan_name: data.planName,
      promotion_code: promotionCode.value,
    },
    onSuccess: res => {
      console.log(res);
    }
  })
};

const removePromotionCode = async (code) => {
  router.visit('/checkout', {
    data: {
      remove_promotion_code: code,
    },
    onSuccess: res => {
      console.log(res);
    }
  })
};


</script>

<template>
  <Head title="Checkout"/>

  <AuthenticatedLayout>

    <form @submit.prevent="submitPayment">
      <div class="grid grid-cols-12 h-92vh">
        <div class="col-span-7">
          <div class="bg-white pt-32 px-28 h-full">
            <StripeElements
                ref="stripeElement"
                v-slot="{ elements }"
                :stripe-key="stripeKey"
                :instance-options="instanceOptions"
                :elements-options="elementsOptions"
            >
              <h3 class="text-lg font-bold mb-6 text-gray-900">Payment Details</h3>

              <!--                        <StripeElements-->
              <!--                            v-slot="{ elements, instance }"-->
              <!--                            ref="elms"-->
              <!--                            :stripe-key="stripeKey"-->
              <!--                            :instance-options="instanceOptions"-->
              <!--                            :elements-options="elementsOptions"-->
              <!--                        >-->
              <!--                            <StripeElement-->
              <!--                                ref="card"-->
              <!--                                :elements="elements"-->
              <!--                                :options="cardOptions"-->
              <!--                            />-->
              <!--                        </StripeElements>-->
              <!--                        -->

              <div>
                <label for="card-name" class="text-sm font-medium text-gray-900 mb-2 block">Name on card*</label>
                <input v-model="cardName"
                       :disabled="isOrderProcess"
                       placeholder="Name"
                       class="p-4 rounded-lg border border-gray-300 bg-gray-50 block w-full text-base font-normal text-gray-900">
                <div class="text-red-600" v-if="cardNameError">{{ cardNameError }}</div>

              </div>
              <div class="mt-8">
                <label for="card-number" class="text-sm font-medium text-gray-900 mb-2 block">Card number*</label>
                <StripeElement
                    ref="cardNumber"
                    type="cardNumber"
                    :elements="elements"
                    :options="cardNumberOptions"
                    class="p-4 rounded-lg border border-gray-300 bg-gray-50 block w-full text-base font-normal text-gray-900"
                />
                <div class="text-red-600" v-if="cardNumberError">{{ cardNumberError }}</div>
              </div>
              <div class="mt-8 grid grid-cols-12 gap-4">
                <div class="col-span-4">
                  <label for="card-expiry" class="text-sm font-medium text-gray-900 mb-2 block">Expiration date
                    (MM/YY)*</label>
                  <StripeElement
                      ref="cardExpiry"
                      type="cardExpiry"
                      :elements="elements"
                      :options="expiryOptions"
                      class="p-4 py-[19px] rounded-lg border border-gray-300 bg-gray-50 block w-full text-base font-normal text-gray-900"
                  />
                  <div class="text-red-600" v-if="cardExpiryError">{{ cardExpiryError }}</div>
                </div>
                <div class="col-span-4">
                  <label for="card-cvc" class="text-sm font-medium text-gray-900 mb-2 block">Security Code
                    (CVC)*</label>
                  <StripeElement
                      ref="cardCvc"
                      type="cardCvc"
                      :elements="elements"
                      :options="cvcOptions"
                      class="p-4 rounded-lg border border-gray-300 bg-gray-50 block w-full text-base font-normal text-gray-900"
                  />
                  <div class="text-red-600" v-if="cardCvcError">{{ cardCvcError }}</div>
                </div>
                <div class="col-span-4">
                  <label for="billing-zip" class="text-sm font-medium text-gray-900 mb-2 block">Billing zip
                    code*</label>
                  <StripeElement
                      type="postalCode"
                      v-model="billingPostalCode"
                      :elements="elements"
                      :options="postalCodeOptions"
                      class="p-4 rounded-lg border border-gray-300 bg-gray-50 block w-full text-base font-normal text-gray-900"
                  />
                  <div class="text-red-600" v-if="postalCodeError">{{ postalCodeError }}</div>
                </div>
              </div>
              <div class="mt-8 grid grid-cols-2 gap-8">
                <!--                <div>-->
                <!--                  <label for="state" class="text-sm font-medium text-gray-900 mb-2 block">State*</label>-->
                <!--                  <select v-model="billingState"-->
                <!--                          class="p-4 rounded-lg border border-gray-300 bg-gray-50 block w-full text-base font-normal text-gray-900">-->
                <!--                    <option value="">Select</option>-->
                <!--                    <option value="AL">Alabama</option>-->
                <!--                    <option value="AK">Alaska</option>-->
                <!--                    <option value="AZ">Arizona</option>-->
                <!--                    <option value="AR">Arkansas</option>-->
                <!--                    <option value="CA">California</option>-->
                <!--                    <option value="CO">Colorado</option>-->
                <!--                    <option value="CT">Connecticut</option>-->
                <!--                    <option value="DE">Delaware</option>-->
                <!--                    <option value="FL">Florida</option>-->
                <!--                    <option value="GA">Georgia</option>-->
                <!--                    <option value="HI">Hawaii</option>-->
                <!--                    <option value="ID">Idaho</option>-->
                <!--                    <option value="IL">Illinois</option>-->
                <!--                    <option value="IN">Indiana</option>-->
                <!--                    <option value="IA">Iowa</option>-->
                <!--                    <option value="KS">Kansas</option>-->
                <!--                    <option value="KY">Kentucky</option>-->
                <!--                    <option value="LA">Louisiana</option>-->
                <!--                    <option value="ME">Maine</option>-->
                <!--                    <option value="MD">Maryland</option>-->
                <!--                    <option value="MA">Massachusetts</option>-->
                <!--                    <option value="MI">Michigan</option>-->
                <!--                    <option value="MN">Minnesota</option>-->
                <!--                    <option value="MS">Mississippi</option>-->
                <!--                    <option value="MO">Missouri</option>-->
                <!--                    <option value="MT">Montana</option>-->
                <!--                    <option value="NE">Nebraska</option>-->
                <!--                    <option value="NV">Nevada</option>-->
                <!--                    <option value="NH">New Hampshire</option>-->
                <!--                    <option value="NJ">New Jersey</option>-->
                <!--                    <option value="NM">New Mexico</option>-->
                <!--                    <option value="NY">New York</option>-->
                <!--                    <option value="NC">North Carolina</option>-->
                <!--                    <option value="ND">North Dakota</option>-->
                <!--                    <option value="OH">Ohio</option>-->
                <!--                    <option value="OK">Oklahoma</option>-->
                <!--                    <option value="OR">Oregon</option>-->
                <!--                    <option value="PA">Pennsylvania</option>-->
                <!--                    <option value="RI">Rhode Island</option>-->
                <!--                    <option value="SC">South Carolina</option>-->
                <!--                    <option value="SD">South Dakota</option>-->
                <!--                    <option value="TN">Tennessee</option>-->
                <!--                    <option value="TX">Texas</option>-->
                <!--                    <option value="UT">Utah</option>-->
                <!--                    <option value="VT">Vermont</option>-->
                <!--                    <option value="VA">Virginia</option>-->
                <!--                    <option value="WA">Washington</option>-->
                <!--                    <option value="WV">West Virginia</option>-->
                <!--                    <option value="WI">Wisconsin</option>-->
                <!--                    <option value="WY">Wyoming</option>-->
                <!--                  </select>-->
                <!--                </div>-->
              </div>
            </StripeElements>
          </div>
        </div>
        <div class="col-span-5 bg-gray-50">
          <div class="pt-32 px-14 pb-6 border-b border-gray-200">
            <h3 class="text-lg font-bold mb-6 text-gray-900">Order Summary</h3>
            <div class="flex gap-4">
              <div class="bg-white p-4">
                <AppSimpleLogo/>
              </div>
              <div>
                <h6 class="text-sm font-bold text-gray-900">{{ data.planName }}</h6>
                <p class="text-sm font-normal text-gray-700 w-60">Automatically renews at the then-current
                  yearly price until cancelled.</p>
              </div>
              <div>
                <span class="text-sm font-bold text-gray-900">{{ data.subTotal }}</span>
              </div>
            </div>
          </div>
          <div class="pt-9 px-14">
            <label for="" class="block mb-3 text-sm font-bold text-gray-900 hidden">Discount code</label>
            <div class="grid grid-cols-12 gap-2 mb-10 hidden">
              <div class="col-span-10">
                <input type="text"
                       v-model="promotionCode"
                       :disabled="isOrderProcess"
                       class="px-4 py-2 rounded-sm border border-gray-300 bg-white text-sm font-normal text-gray-500 w-full"
                       placeholder="Enter discount code">
              </div>
              <div class="col-span-2">
                <button type="button"
                        @click="calculateTotalPrice"
                        :disabled="!isValidCoupon || isOrderProcess"
                        class="py-2 px-3 rounded-2 w-full text-sm font-semibold"
                        :class="{'bg-primary-700 text-white': isValidCoupon && !isOrderProcess, 'bg-gray-200 text-gray-400 cursor-not-allowed': !isValidCoupon || isOrderProcess}"
                >Apply
                </button>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <h6 class="text-sm font-bold text-gray-700">Subtotal</h6>
              <h6 class="text-sm font-bold text-gray-700">{{ data.subTotal }}</h6>
            </div>
            <div class="flex justify-between gap-4 mt-6" v-for="(item, i) in data.promotionCodes">
              <div class="">
                <div class="flex">
                  <div
                      class="flex py-1 px-2 bg-primary-50 rounded-sm leading-none text-xs font-bold text-primary-700">
                    <p class="pt-1 uppercase">{{ item.code }}</p>
                    <button
                        class="ml-1"
                        v-if="item.code != 'earlybirdfirsttime'"
                        @click.prevent="removePromotionCode(item.code)">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                           stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                      </svg>
                    </button>
                  </div>
                </div>
                <p class="text-sm mt-2 text-gray-600">${{ item.coupon.amount_off / 100 }} for the first year. After
                  first year, an annual rate of {{ data.subTotal }} will be applied</p>
              </div>
              <div class="text-right">
                <h6 class="text-sm font-normal text-gray-600 w-40">-${{ item.coupon.amount_off / 100 }}.00</h6>
              </div>
            </div>
            <hr class="my-6 bg-gray-200">
            <div class="flex items-center justify-between mb-12">
              <h3 class="text-lg font-bold text-gray-700">Total</h3>
              <h3 class="text-lg font-bold text-gray-700">{{ totalPrice }}</h3>
            </div>
            <div class="flex gap-2 mb-6">
              <label>
                <input id="default-checkbox" type="checkbox" value=""
                       v-model="termsAndPrivacyAgreement"
                       class="w-4 h-4 rounded-sm border-gray-300 bg-gray-50 cursor-pointer accent-primary-700 mr-2">
                <span class="text-gray-500 text-xs font-normal">By checking ths box, I agree to Global Honors'
                                    <a class="font-bold" href="https://staging.globalhonors.biz/terms-of-service"
                                       target="_blank">Terms of Service</a> and
                                    <a class="font-bold" href="https://staging.globalhonors.biz/privacy-policy"
                                       target="_blank">Privacy Policy</a></span>
              </label>
            </div>
            <button type="submit"
                    :disabled="!termsAndPrivacyAgreement"
                    :class="!termsAndPrivacyAgreement ? 'bg-primary-200' : 'bg-primary-700'"
                    class="text-sm font-semibold text-white py-3 w-full rounded-sm mb-2 relative">
              <span v-if="isOrderProcess">Processing order...</span>
              <span v-else>Confirm order</span>
              <span
                  v-if="isOrderProcess"
                  class="absolute top-2 right-2 inline-block h-7 w-7 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                  role="status"></span>
            </button>
          </div>
        </div>
      </div>
    </form>
  </AuthenticatedLayout>
</template>
