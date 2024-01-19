<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import {Head, Link, router, useForm} from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {EyeSlashIcon, EyeIcon} from "@heroicons/vue/24/outline";
import {ref, onMounted} from "vue";
import axios from "axios";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const showCurrentPassword = ref(false);

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
onMounted(async () => {
    try {
        const response = await axios.get('/auth-check');
        if (response.data.auth) {
            router.visit(route('awards.index')); // Redirect to the awards page
        }
    } catch (error) {
        console.error('Error checking auth status:', error);
    }
});
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <Link href="/">
                <ApplicationLogo class="h-12 fill-current text-gray-500"/>
            </Link>
        </div>
        <h4 class="text-3xl font-bold mt-4 text-gray-900">Login in to your account</h4>
        <p class="text-sm text-gray-600">Welcome back! Please enter your details.</p>
        <div class="w-full sm:max-w-md p-8 mt-4">
            <Head title="Log in"/>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email"/>
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-2 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email"/>
                </div>

                <div class="mt-5">
                    <InputLabel for="password" value="Password"/>
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
                            class="mt-2 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />
                    </div>

                    <InputError class="mt-2" :message="form.errors.password"/>
                </div>

                <div class="w-full flex justify-between items-center mt-5">
                    <div class="block">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember"/>
                            <span class="leading-none text-sm font-medium ml-2 text-gray-500">Remember me</span>
                        </label>
                    </div>
                    <div class="flex items-center justify-end">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-primary-600 text-xs font-medium"
                        >
                            Forgot password?
                        </Link>
                    </div>
                </div>
                <PrimaryButton class="w-full mb-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> Log in</PrimaryButton>
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
                <p class="text-sm font-medium text-gray-900 text-center">
                    Don’t have an account? <a href="/register" class="text-primary-600">Sign up</a>
                </p>
            </form>
        </div>
    </div>
</template>
