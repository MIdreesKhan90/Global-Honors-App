<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import {ref} from "vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

const {mustVerifyEmail, status, customer, subscriptionDetails} = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    customer: {
        type: Object
    },
    subscriptionDetails: {
        type: Array
    }
});

const isAddedSuccess = ref(false);
const successMessage = ref(null);

//const user = usePage().props.auth.user;

const form = useForm({
    first_name: customer.first_name,
    last_name: customer.last_name,
    email: customer.email,
    phone_number: customer.phone_number,
    offers_notification: customer.offers_notification === 1,
    deadlines_notification: customer.deadlines_notification === 1,
    awards_notification: customer.awards_notification === 1,
});

const submitPersonalInfo = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            successMessage.value = 'Changes to your <strong>Profile</strong> have been saved.';
            isAddedSuccess.value = true;

            setTimeout(() => {
                isAddedSuccess.value = false;
            }, 5000);
        }
    })
}

const closeSuccessMessage = () => {
    isAddedSuccess.value = false;
}

const isProfile = ref(true);
const isBilling = ref(false);
const isPushNotification = ref(false);
const isDeleteAccount = ref(false);

const profileEdit = () => {
    isProfile.value = true;
    isBilling.value = false;
}

const billingEdit = () => {
    isBilling.value = true;
    isProfile.value = false;
}

const capitalizeFirstLetter = (value) => {
    return value.charAt(0).toUpperCase() + value.slice(1);
}
</script>

<template>
    <Head title="Profile"/>

    <AuthenticatedLayout>
        <!-- <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">Account</h2>
        </template> -->

        <div class="bg-white">
            <div class="mx-28 pt-10">
                <h2 class="font-bold text-4xl text-gray-800">Account</h2>
                <hr class="mt-5 bg-gray-200">
            </div>

            <div class="grid grid-cols-10 gap-24 mx-28 mt-8">
                <div class="col-span-2">
                    <div>
                        <button @click.prevent="profileEdit" class="border-primary-700 px-3 py-2 w-36 text-left text-sm font-bold"
                                :class="{ 'border-l-2 font-bold': isProfile }">
                            Profile
                        </button>
                    </div>
                    <div v-if="customer.stripe_customer_id" class="mt-2">
                        <button @click.prevent="billingEdit" class="border-primary-700 px-3 py-2 w-36 text-left text-sm font-bold"
                                :class="{ 'border-l-2 font-bold': isBilling }">
                            Billing
                        </button>
                    </div>
                </div>
                <div class="w-full col-span-8">
                    <div>
                        <div v-if="isProfile">
                            <div class="flex justify-between items-center mb-5">
                                <div>
                                    <h2 class="text-lg font-bold text-black">Your Profile</h2>
                                    <p class="text-sm font-normal text-gray-600">
                                        View and update your account information.
                                    </p>
                                </div>
                                <div class="flex gap-3">
                                    <Link :href="route('awards.index')"
                                          class="rounded-sm border border-primary-700 px-5 py-3 text-sm font-semibold text-primary-700">
                                        Cancel
                                    </Link>
                                    <button
                                        type="button"
                                        @click.prevent="submitPersonalInfo"
                                        :disabled="form.processing"
                                        class="bg-primary-700 text-white px-5 py-3 text-sm font-semibold rounded-sm">
                                        Save
                                    </button>
                                </div>
                            </div>
                            <hr class="bg-gray-200">
                            <div class="my-6 flex gap-8">
                                <div class="w-72">
                                    <h2 class="text-sm font-bold text-gray-900">Account Type</h2>
                                    <p class="text-sm font-normal text-gray-600">
                                        View your account type. You can make changes to your account type on the Billing page.
                                    </p>
                                </div>
                                <div class="">
                                    <p class="px-4 py-2 bg-primary-50 rounded-sm leading-none text-sm font-bold text-primary-700">
                                        {{ customer.user_type }}
                                    </p>
                                </div>
                            </div>
                            <hr class="bg-gray-200">
                            <div class="grid grid-cols-11 my-6">
                                <div class="col-span-4">
                                    <div class="w-72">
                                        <h2 class="text-sm font-bold text-gray-900">Personal Information</h2>
                                        <p class="text-sm font-normal text-gray-600">
                                            Update the personal information associated with your account.
                                        </p>
                                    </div>
                                </div>
                                <div class=" col-span-7">
                                    <form>
                                        <div class="flex gap-8 mb-8">
                                            <div class="w-full">
                                                <InputLabel for="name" value="First Name" class="text-sm font-medium text-gray-900 mb-2"/>
                                                <TextInput id="first_name" type="text"
                                                           class="w-full text-base font-normal text-gray-900 px-4 py-3"
                                                           v-model="form.first_name" required
                                                           autofocus autocomplete="name"/>
                                                <InputError :message="form.errors.first_name"/>
                                            </div>

                                            <div class="w-full">
                                                <InputLabel for="email" value="Last Name" class="text-sm font-medium text-gray-900 mb-2"/>
                                                <TextInput id="last_name" type="email"
                                                           class="w-full text-base font-normal text-gray-900 px-4 py-3" v-model="form.last_name" required
                                                           autocomplete="username"/>
                                                <InputError :message="form.errors.last_name"/>
                                            </div>
                                        </div>
                                        <div class="mb-8">
                                            <InputLabel for="name" value="Email" class="text-sm font-medium text-gray-900 mb-2"/>
                                            <TextInput id="email" type="text" class="w-full text-base font-normal text-gray-900 px-4 py-3"
                                                       v-model="form.email" required autofocus autocomplete="name"/>
                                            <InputError :message="form.errors.email"/>
                                        </div>
                                        <div class="">
                                            <InputLabel for="name" value="Phone Number" class="text-sm font-medium text-gray-900 mb-2"/>
                                            <TextInput id="name" type="text" class="w-full text-base font-normal text-gray-900 px-4 py-3"
                                                       v-model="form.phone_number" required autofocus autocomplete="name"/>
                                            <InputError :message="form.errors.phone_number"/>
                                        </div>

                                        <div v-if="mustVerifyEmail && user.email_verified_at === null">
                                            <p class="text-sm mt-2 text-gray-800">
                                                Your email address is unverified.
                                                <Link :href="route('verification.send')" method="post" as="button"
                                                      class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Click here to re-send the verification email.
                                                </Link>
                                            </p>
                                            <div v-show="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
                                                A new verification link has been sent to your email address.
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr class="bg-gray-200">
                            <div class="grid grid-cols-11 my-6">
                                <div class="col-span-4">
                                    <div class="w-72">
                                        <h2 class="text-sm font-bold text-gray-900">Change password</h2>
                                        <p class="text-sm font-normal text-gray-600">
                                            Update the password associated with your account.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-span-7">
                                    <UpdatePasswordForm class="w-full"/>
                                </div>
                            </div>
                            <hr class="bg-gray-200">
                            <div class="grid grid-cols-11 my-6">
                                <div class="col-span-4">
                                    <div class="w-72">
                                        <h2 class="text-sm font-bold text-gray-900">Notifications</h2>
                                        <p class="text-sm font-normal text-gray-600">
                                            Set your communication and notification preferences.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-span-7">
                                    <h2 class="text-sm font-bold text-gray-900">By Email</h2>
                                    <div class="flex mt-6">
                                        <div>
                                            <input v-model="form.offers_notification" class="border border-gray-300 rounded bg-gray-50 w-4 h-4" type="checkbox" name="" id="">
                                        </div>
                                        <div class="ml-2">
                                            <h3 class="leading-none text-sm font-medium text-gray-900">Offers/Marketing</h3>
                                            <p class="text-gray-500 text-xs font-normal">
                                                Get notified about our special offers and product updates.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex mt-8">
                                        <div>
                                            <input v-model="form.deadlines_notification" class="border border-gray-300 rounded bg-gray-50 w-4 h-4" type="checkbox" name="" id="">
                                        </div>
                                        <div class="mx-2">
                                            <h3 class="leading-none text-sm font-medium text-gray-900">Deadlines</h3>
                                            <p class="text-gray-500 text-xs font-normal">
                                                Get notified when a deadline on your list has changed or is approaching.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex mt-8">
                                        <div>
                                            <input v-model="form.awards_notification" class="border border-gray-300 rounded bg-gray-50 w-4 h-4" type="checkbox" name="" id="">
                                        </div>
                                        <div class="mx-2">
                                            <h3 class="leading-none text-sm font-medium text-gray-900">Awards</h3>
                                            <p class="text-gray-500 text-xs font-normal">
                                                Get notified when a new award has been added to our database.
                                            </p>
                                        </div>
                                    </div>
                                    <div v-if="isPushNotification">
                                        <div class="mt-8">
                                            <h3 class="text-sm font-bold text-gray-900">Push Notifications</h3>
                                            <p class="text-gray-500 text-sm font-normal">
                                                Delivered via SMS to your mobile phone
                                            </p>
                                        </div>
                                        <div class="flex mt-6 gap-2 items-center">
                                            <input class="border border-gray-300 rounded bg-gray-50 w-4 h-4" type="checkbox" name="" id="">
                                            <h3 class="leading-none text-sm font-medium">Everything</h3>
                                        </div>
                                        <div class="flex mt-6 gap-2 items-center">
                                            <input class="border border-gray-300 rounded bg-gray-50 w-4 h-4" type="checkbox" name="" id="">
                                            <h3 class="leading-none text-sm font-medium">Same as email</h3>
                                        </div>
                                        <div class="flex mt-6 gap-2 items-center">
                                            <input class="border border-gray-300 rounded bg-gray-50 w-4 h-4" type="checkbox" name="" id="">
                                            <h3 class="leading-none text-sm font-medium">No push notifications</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="bg-gray-200">
                            <div v-if="isDeleteAccount" class="grid grid-cols-11 my-6">
                                <div class="col-span-4">
                                    <div class="w-72">
                                        <h2 class="text-sm font-bold text-gray-900">Delete Account</h2>
                                        <p class="text-sm font-normal text-gray-600">
                                            This action is not reversible. All information related to this account will be deleted permanently.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-span-7">
                                    <DeleteUserForm class="w-full"/>
                                </div>
                            </div>
                            <div class="flex justify-end my-6">
                                <Link :href="route('awards.index')"
                                      class="rounded-sm border border-primary-700 px-5 py-3 text-sm font-semibold text-primary-700 mr-3">
                                    Cancel
                                </Link>
                                <button
                                    type="button"
                                    @click.prevent="submitPersonalInfo"
                                    :disabled="form.processing"
                                    class="bg-primary-700 text-white px-5 py-3 text-sm font-semibold rounded-sm">
                                    Save
                                </button>
                            </div>
                        </div>
                        <div v-if="isBilling">
                            <div class="flex justify-between items-center mb-5">
                                <div>
                                    <h2 class="text-lg font-bold text-black">Billing</h2>
                                    <p class="text-sm font-normal text-gray-600">
                                        Manage your billing and payment methods.
                                    </p>
                                </div>
                            </div>
                            <hr class="bg-gray-200">
                            <div class="my-6 flex gap-8">
                                <div class="w-72">
                                    <h2 class="text-sm font-bold text-gray-900">Current subscription</h2>
                                    <p class="text-sm font-normal text-gray-600">
                                        Manage your subscription.
                                    </p>
                                </div>
                                <div class="">
                                    <h1 class="text-xl font-bold text-gray-900">{{ customer.user_type }}</h1>
                                    <p class="py-1 text-sm font-bold text-gray-500">
                                        Your next payment will be charged on <span class="font-bold text-gray-600">{{ subscriptionDetails.next_Billing_Date }}</span> to the
                                        {{ capitalizeFirstLetter(subscriptionDetails.cardDetail.brand) }} ending in
                                        {{ subscriptionDetails.cardDetail.last4 }}
                                    </p>
                                    <div class="mt-7">
                                        <Link v-if="customer.user_type !== 'Premier'"
                                              class="bg-primary-700 rounded-sm items-center text-white text-sm font-normal p-3 font-mulish"
                                              :href="route('plans')">
                                            See plans
                                        </Link>
                                        <a v-else
                                           disabled
                                           class="bg-gray-200 rounded-sm cursor-not-allowed items-center text-gray-400 text-sm font-normal p-3 font-mulish">
                                            See plans
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="bg-gray-200">
                            <div class="my-6 flex gap-8">
                                <div class="w-72">
                                    <h2 class="text-sm font-bold text-gray-900">Payment Method</h2>
                                    <p class="text-sm font-normal text-gray-600">
                                        Manage how you pay for your plan.
                                    </p>
                                </div>
                                <div class="">
                                    <h1 class="text-sm font-bold text-gray-700">
                                        {{ capitalizeFirstLetter(subscriptionDetails.cardDetail.brand) }} ending in
                                        {{ subscriptionDetails.cardDetail.last4 }}
                                    </h1>
                                    <p class="py-1 text-sm font-normal text-gray-600">
                                        Exp {{ subscriptionDetails.cardDetail.exp_month }}/{{ subscriptionDetails.cardDetail.exp_year }}
                                    </p>
                                    <div class="mt-1">
                                        <a href="/"
                                            class="text-primary-700 underline font-semibold text-sm font-normal font-mulish">
                                            Update payment method
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                v-if="isAddedSuccess"
                class="fixed bottom-5 flex left-4 z-50  justify-start items-center gap-3 w-72 bg-gray-800 border shadow text-white px-4 py-3 rounded-lg"
                role="alert">
                <div class="bg-primary-900 w-7 h-6 rounded-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 20 20" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M16.707 5.29279C16.8945 5.48031 16.9998 5.73462 16.9998 5.99979C16.9998 6.26495 16.8945 6.51926 16.707 6.70679L8.70698 14.7068C8.51945 14.8943 8.26514 14.9996 7.99998 14.9996C7.73482 14.9996 7.48051 14.8943 7.29298 14.7068L3.29298 10.7068C3.11082 10.5182 3.01003 10.2656 3.01231 10.0034C3.01458 9.74119 3.11975 9.49038 3.30516 9.30497C3.49057 9.11956 3.74138 9.01439 4.00358 9.01211C4.26578 9.00983 4.51838 9.11063 4.70698 9.29279L7.99998 12.5858L15.293 5.29279C15.4805 5.10532 15.7348 5 16 5C16.2651 5 16.5195 5.10532 16.707 5.29279Z"
                              fill="#529397"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-normal font-mulish" v-html="successMessage"></p>
                </div>
                <div class="">
                    <button @click.prevent="closeSuccessMessage">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <g clip-path="url(#clip0_1554_1282)">
                                <path
                                    d="M7.20988 6L11.7388 1.47109C11.8205 1.39216 11.8857 1.29775 11.9305 1.19336C11.9754 1.08896 11.999 0.976687 12 0.863076C12.001 0.749465 11.9793 0.636794 11.9363 0.531639C11.8933 0.426484 11.8297 0.33095 11.7494 0.250611C11.669 0.170273 11.5735 0.106739 11.4684 0.0637164C11.3632 0.020694 11.2505 -0.000954946 11.1369 3.23065e-05C11.0233 0.00101956 10.911 0.0246235 10.8066 0.0694668C10.7023 0.11431 10.6078 0.179495 10.5289 0.261217L6 4.79012L1.47109 0.261217C1.30972 0.105355 1.09358 0.0191112 0.869234 0.0210607C0.644888 0.0230102 0.430283 0.112997 0.27164 0.27164C0.112997 0.430283 0.0230102 0.644888 0.0210607 0.869234C0.0191112 1.09358 0.105355 1.30972 0.261217 1.47109L4.79012 6L0.261217 10.5289C0.179495 10.6078 0.11431 10.7023 0.0694668 10.8066C0.0246235 10.911 0.00101956 11.0233 3.23065e-05 11.1369C-0.000954946 11.2505 0.020694 11.3632 0.0637164 11.4684C0.106739 11.5735 0.170273 11.669 0.250611 11.7494C0.33095 11.8297 0.426484 11.8933 0.531639 11.9363C0.636794 11.9793 0.749465 12.001 0.863076 12C0.976687 11.999 1.08896 11.9754 1.19336 11.9305C1.29775 11.8857 1.39216 11.8205 1.47109 11.7388L6 7.20988L10.5289 11.7388C10.6903 11.8946 10.9064 11.9809 11.1308 11.9789C11.3551 11.977 11.5697 11.887 11.7284 11.7284C11.887 11.5697 11.977 11.3551 11.9789 11.1308C11.9809 10.9064 11.8946 10.6903 11.7388 10.5289L7.20988 6Z"
                                    fill="#9CA3AF"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_1554_1282">
                                    <rect width="12" height="12" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
