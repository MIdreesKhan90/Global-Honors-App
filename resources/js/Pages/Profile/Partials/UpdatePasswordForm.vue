<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import {EyeSlashIcon, EyeIcon} from "@heroicons/vue/24/outline";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value.focus();
      }
      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value.focus();
      }
    },
  });
};

const toggleVisibility = (type) => {
  if (type === 'current') {
    showCurrentPassword.value = !showCurrentPassword.value;
  } else if (type === 'new') {
    showNewPassword.value = !showNewPassword.value;
  } else if (type === 'confirm') {
    showConfirmPassword.value = !showConfirmPassword.value;
  }
};
</script>

<template>
  <section>
    <form @submit.prevent="updatePassword">
      <div>
        <InputLabel for="current_password" value="Current Password" class="text-sm font-medium text-gray-900 mb-2"/>

        <div class="relative">
          <div class="absolute inset-y-0 right-0 flex items-center pr-3">
        <span class="text-gray-500 sm:text-sm">
            <button type="button" @click.prevent="() => toggleVisibility('current')">
                <template v-if="showCurrentPassword">
                    <EyeSlashIcon class="w-4 h-4" />
                </template>
                <template v-else>
                    <EyeIcon class="w-4 h-4" />
                </template>
            </button>
        </span>
          </div>
          <TextInput
              id="current_password"
              ref="currentPasswordInput"
              v-model="form.current_password"
              :type="showCurrentPassword ? 'text' : 'password'"
              class="w-full text-base font-normal text-gray-900 px-4 py-3"
              autocomplete="current-password"/>
          <InputError :message="form.errors.current_password"/>
        </div>
      </div>


        <div class="flex gap-8 my-8">
          <div class="w-full">
            <InputLabel for="password" value="New Password" class="text-sm font-medium text-gray-900 mb-2"/>

            <div class="relative">
              <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <span class="text-gray-500 sm:text-sm">
                    <button type="button" @click.prevent="() => toggleVisibility('new')">
                        <template v-if="showNewPassword">
                            <EyeSlashIcon class="w-4 h-4" />
                        </template>
                        <template v-else>
                            <EyeIcon class="w-4 h-4" />
                        </template>
                    </button>
                </span>
              </div>
              <TextInput id="password"
                         ref="passwordInput"
                         v-model="form.password"
                         :type="showNewPassword ? 'text' : 'password'"
                         class="w-full text-base font-normal text-gray-900 px-4 py-3"
                         autocomplete="new-password"/>
            </div>
            <InputError v-if="form.errors.password" :message="form.errors.password"/>
            <p v-else class="text-sm font-normal">Your password must be at least 8 characters</p>
          </div>

          <div class="w-full">
            <InputLabel for="password_confirmation" value="Confirm Password" class="text-sm font-medium text-gray-900 mb-2"/>

            <div class="relative">
              <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            <span class="text-gray-500 sm:text-sm">
                <button type="button" @click.prevent="() => toggleVisibility('confirm')">
                    <template v-if="showConfirmPassword">
                        <EyeSlashIcon class="w-4 h-4" />
                    </template>
                    <template v-else>
                        <EyeIcon class="w-4 h-4" />
                    </template>
                </button>
            </span>
              </div>
              <TextInput id="password_confirmation"
                         v-model="form.password_confirmation"
                         :type="showConfirmPassword ? 'text' : 'password'"
                         class="w-full text-base font-normal text-gray-900 px-4 py-3"
                         autocomplete="new-password"/>
              <InputError :message="form.errors.password_confirmation"/>
            </div>
          </div>
        </div>

      <div class="flex items-center gap-4">
          <PrimaryButton :disabled="form.processing">Update
              Password
          </PrimaryButton>

        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
          <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
        </Transition>
      </div>
    </form>
  </section>
</template>
