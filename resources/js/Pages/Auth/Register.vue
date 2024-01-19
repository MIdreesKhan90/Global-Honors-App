<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {EyeSlashIcon, EyeIcon} from "@heroicons/vue/24/outline";
import {ref} from "vue";

const form = useForm({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  password_confirmation: "",
  policy_agreement: "",
  offers_notification: "",
});

const showCurrentPassword = ref(false)
const showConfirmPassword = ref(false)

const submit = () => {
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};
</script>

<template>
  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div class="mt-3">
      <Link href="/">
        <ApplicationLogo class="h-12 fill-current text-gray-500"/>
      </Link>
    </div>
    <h4 class="text-4xl font-normal font-dmSerif-display mt-4 text-gray-900">Create an Account</h4>
    <p class="text-sm text-gray-600">Start your free trial now!</p>
    <div class="w-full sm:max-w-md p-8 mt-4">
      <Head title="Register"/>

      <form @submit.prevent="submit">
        <div>
          <div class="flex">
            <InputLabel for="first_name" value="First name" class="text-gray-900 text-sm font-medium font-mulish"/>
            <span>*</span>
          </div>
          <TextInput
              id="first_name"
              type="text"
              class="mt-1 block w-full px-[0.4rem] py-3 text-[0.9rem] leading-[1.4rem]"
              v-model="form.first_name"
              required
              autofocus
              autocomplete="name"
              placeholder="first name"
          />
          <InputError class="mt-2" :message="form.errors.first_name"/>
        </div>
        <div class="mt-4">
          <div class="flex">
            <InputLabel for="first_name" value="Last name" class="text-gray-900 text-sm font-medium font-mulish"/>
            <span>*</span>
          </div>
          <TextInput
              id="last_name"
              type="text"
              class="mt-1 block w-full px-[0.4rem] py-3 text-[0.9rem] leading-[1.4rem]"
              v-model="form.last_name"
              required
              autofocus
              autocomplete="name"
              placeholder="last name"
          />
          <InputError class="mt-2" :message="form.errors.last_name"/>
        </div>
        <div class="mt-4">
          <div class="flex">
            <InputLabel for="first_name" value="Email" class="text-gray-900 text-sm font-medium font-mulish"/>
            <span>*</span>
          </div>
          <TextInput
              id="email"
              type="email"
              class="mt-1 block w-full px-[0.4rem] py-3 text-[0.9rem] leading-[1.4rem]"
              v-model="form.email"
              required
              autocomplete="username"
              placeholder="name@example.com"
          />

          <InputError class="mt-2" :message="form.errors.email"/>
        </div>
        <div class="mt-5">
          <div class="flex">
            <InputLabel for="first_name" value="Password" class="text-gray-900 text-sm font-medium font-mulish"/>
            <span>*</span>
          </div>
          <div class="relative">
            <div class="cursor-pointer absolute inset-y-0 right-0 flex items-center pr-3">
                          <span class="text-gray-500 sm:text-sm" @click="showCurrentPassword = !showCurrentPassword">
                              <template v-if="showCurrentPassword">
                                <EyeIcon class="w-4 h-4"/>
                              </template>
                                <template v-else>
                                    <EyeSlashIcon class="w-4 h-4"/>
                                </template>
                          </span>
            </div>
            <TextInput
                id="password"
                :type="showCurrentPassword ? 'text' : 'password'"
                class="mt-2 block w-full px-[0.4rem] py-3 text-[0.9rem] leading-[1.4rem]"
                v-model="form.password"
                required
                autocomplete="current-password"
                placeholder="password"
            />
          </div>
          <p class="leading-tight text-sm font-normal text-gray-500 mt-3">Must be at least 8 characters.</p>

          <InputError class="mt-2" :message="form.errors.password"/>
        </div>
        <div class="mt-4">
          <div class="flex">
            <InputLabel for="first_name" value="Confirm password" class="text-gray-900 text-sm font-medium font-mulish"/>
            <span>*</span>
          </div>

          <div class="relative">
            <div class="cursor-pointer absolute inset-y-0 right-0 flex items-center pr-3">
                          <span class="text-gray-500 sm:text-sm" @click="showConfirmPassword = !showConfirmPassword">
                              <template v-if="showConfirmPassword">
                                <EyeIcon class="w-4 h-4"/>
                              </template>
                                <template v-else>
                                    <EyeSlashIcon class="w-4 h-4"/>
                                </template>
                          </span>
            </div>
            <TextInput
                id="password"
                :type="showConfirmPassword ? 'text' : 'password'"
                class="mt-2 block w-full px-[0.4rem] py-3 text-[0.9rem] leading-[1.4rem]"
                v-model="form.password_confirmation"
                required
                autocomplete="current-password"
                placeholder="confirm password"
            />
          </div>
          <InputError
              class="mt-2"
              :message="form.errors.password_confirmation"
          />
        </div>

        <div class="block mt-5">
          <label class="flex">
            <Checkbox
                name="policy_agreement"
                v-model="form.policy_agreement"
            />
            <span class="leading-none text-xs font-normal text-gray-500 ml-2"
            >By signing up, I agree to Global Honor’s <a href="https://staging.globalhonors.biz/terms-of-service"
                                                         target="_blank" class="underline text-primary-700">Terms of Service</a> and <a
                href="https://staging.globalhonors.biz/privacy-policy" target="_blank" class="underline text-primary-700">Privacy Policy</a>.</span
            >
          </label>
        </div>
        <InputError class="mt-2" :message="form.errors.policy_agreement"/>

        <div class="block mt-3">
          <label class="flex">
            <Checkbox name="offers_notification" v-model="form.offers_notification"/>
            <span class="leading-none text-xs font-normal text-gray-500 ml-2">Email me about marketing emails.</span>
          </label>
        </div>

        <button type="submit"
                :disabled="!form.policy_agreement || form.processing"
                class="py-2 px-5 text-center mt-5 w-full text-white bg-primary-700 rounded-sm text-sm font-semibold">
          Sign Up
        </button>
        <!-- <button
          class="btn w-full border border-gray-300 rounded-sm bg-white flex justify-center mt-4 py-2 px-6 text-sm text-black items-center mb-4"
          type="button"
        >
          <img
            src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQzSPxRFpZtgDHGiHcRa8LjXijJ-1UL_fdAhQ&usqp=CAU"
            class="w-6 h-6 mr-4"
            alt=""
          />
          Sign In with Google
        </button> -->
      </form>
    </div>
    <p class="text-xs font-light text-gray-500 font-mulish mb-4">(The Global Honors Database is best experienced from a desktop computer.)</p>
  </div>
</template>
