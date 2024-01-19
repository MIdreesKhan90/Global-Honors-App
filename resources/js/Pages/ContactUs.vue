<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm, usePage} from '@inertiajs/vue3';
import {onMounted, ref} from 'vue';
import {StripeElements, StripeElement} from 'vue-stripe-js';
import AppSimpleLogo from "@/Components/AppSimpleLogo.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";


const form = useForm({
    first_name: "",
    last_name: "",
    email: "",
    phone_number: "",
    message: "",
    policy_agreement: "",
});

const sendMail = () => {
  form.post(route('sendMail'));
}
</script>

<template>
    <Head title="Contact Us"/>

    <AuthenticatedLayout>
        <div class="bg-white py-32 px-28 h-full">
            <h1 class="text-center text-6xl leading-tight text-gray-900 font-dmSerif-display">We’re here for you</h1>
            <p class="text-center text-xl text-gray-500">Have a question? Send us a message and we'll be in touch soon.</p>
            <form class="mt-12  max-w-2xl mx-auto" @submit.prevent="sendMail">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <InputLabel for="first_name" value="First Name *" class="text-gray-700"/>
                        <TextInput
                            id="first_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.first_name"
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.first_name"/>
                    </div>
                    <div class="sm:col-span-3">
                        <InputLabel for="last_name" value="Last Name *" class="text-gray-700"/>
                        <TextInput
                            id="last_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.last_name"
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.last_name"/>
                    </div>
                </div>
                <div class="mt-4">
                    <InputLabel for="email" value="Email *" class="text-gray-700"/>
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="phone_number" value="Phone Number" class="text-gray-700"/>
                    <TextInput
                        id="phone_number"
                        type="tel"
                        class="mt-1 block w-full"
                        v-model="form.phone_number"
                    />
                    <InputError class="mt-2" :message="form.errors.phone_number"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="message"
                                value="Message *"
                                class="text-gray-700"
                                v-model="form.message"
                    />
                    <textarea id="message" name="message" rows="7"
                              v-model="form.message"
                              class="mt-1 block w-full rounded-lg bg-gray-50 border border-gray-300 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    <InputError class="mt-2" :message="form.errors.message"/>
                </div>
                <div class="block mt-4">
                    <label class="flex">
                        <Checkbox name="offers_notification" v-model="form.policy_agreement"/>
                        <span class="leading-normal text-xs font-normal text-gray-500 ml-2">By submitting this form, you confirm that you have read and agree to Global Honor’s <a
                            class="text-primary-700 underline" href="https://staging.globalhonors.biz/terms-of-service" target="_blank">Terms of Service</a> and
                            <a class="text-primary-700 underline" href="https://staging.globalhonors.biz/privacy-policy" target="_blank">Privacy Policy</a>.</span>
                    </label>
                </div>
              <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Submit</PrimaryButton>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
