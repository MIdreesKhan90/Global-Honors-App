<script setup>
import {computed, onMounted, onUnmounted, ref} from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import {Link, usePage} from "@inertiajs/vue3";

import {BellIcon, XMarkIcon} from "@heroicons/vue/24/solid";

const showingNavigationDropdown = ref(false);
const user = usePage().props.auth.user;

const userInitials = computed(() => {
    const name = user.name;
    return name.split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase();
});

const isMobileView = ref(false);

onMounted(() => {
  if (window.innerWidth < 768) {
    isMobileView.value = true;
  }
  const updateMobileView = () => {
    isMobileView.value = window.innerWidth < 768;
  };

  window.addEventListener('resize', updateMobileView);

  onUnmounted(() => {
    window.removeEventListener('resize', updateMobileView);
  });
});

const hideIsMobileModal = () =>{
  isMobileView.value = false;
}
</script>

<template>
    <div>
        <div class="bg-primary-800 py-3 px-5" v-if="user.user_type == 0">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-base font-semibold text-white">Subscribe for full access</h5>
                    <p class="text-sm font-normal text-white">Your search results are limited for demonstration only. Subscribe today to access the full database of awards.</p>
                </div>
                <div class="flex items-center gap-5">
                    <Link :href="route('plans')" class="text-black text-xs font-medium p-3 rounded-lg bg-secondary-300">Upgrade Now</Link>
                </div>
            </div>
        </div>

        <div class="min-h-body bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('awards.index')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('awards.index')" :active="route().current('awards.index')"> Browse Awards</NavLink>
                                <NavLink :href="route('lists.index')" :active="route().current('lists.index')"> My Lists</NavLink>
                                <NavLink :href="route('searches.index')" :active="route().current('searches.index')"> Saved Searches</NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <button
                                type="button"
                                class="relative p-1 text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800"
                            >
                                <span class="absolute -inset-1.5"/>
                                <span class="sr-only">View notifications</span>
                                <span class="absolute w-2 h-2 bg-error-500 rounded-full bottom-2 right-1 border border-white"></span>
                                <BellIcon class="h-6 w-6" aria-hidden="true"/>
                            </button>
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button
                          type="button"
                          class="items-center w-8 h-8 text-center bg-gray-100 border border-transparent text-xs font-medium rounded-full text-gray-900 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                      >
                         {{ userInitials }}
                      </button>
                    </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile</DropdownLink>
                    <DropdownLink :href="route('contactUs')"> Help</DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button"> Log Out</DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                      hidden: showingNavigationDropdown,
                      'inline-flex': !showingNavigationDropdown,
                    }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                      hidden: !showingNavigationDropdown,
                      'inline-flex': showingNavigationDropdown,
                    }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile</ResponsiveNavLink>
              <ResponsiveNavLink :href="route('contactUs')"> Help</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button"> Log Out</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"/>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot/>
            </main>

      <div v-if="isMobileView">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-95 blur-[10px] transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
              <div class="bg-white px-4 py-4">
                <div class="flex justify-end">
                  <a
                      href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12"
                         fill="none">
                      <g clip-path="url(#clip0_1531_4920)">
                        <path
                            d="M7.20988 6L11.7388 1.47109C11.8205 1.39216 11.8857 1.29775 11.9305 1.19336C11.9754 1.08896 11.999 0.976687 12 0.863076C12.001 0.749465 11.9793 0.636794 11.9363 0.531639C11.8933 0.426484 11.8297 0.33095 11.7494 0.250611C11.669 0.170273 11.5735 0.106739 11.4684 0.0637164C11.3632 0.020694 11.2505 -0.000954946 11.1369 3.23065e-05C11.0233 0.00101956 10.911 0.0246235 10.8066 0.0694668C10.7023 0.11431 10.6078 0.179495 10.5289 0.261217L6 4.79012L1.47109 0.261217C1.30972 0.105355 1.09358 0.0191112 0.869234 0.0210607C0.644888 0.0230102 0.430283 0.112997 0.27164 0.27164C0.112997 0.430283 0.0230102 0.644888 0.0210607 0.869234C0.0191112 1.09358 0.105355 1.30972 0.261217 1.47109L4.79012 6L0.261217 10.5289C0.179495 10.6078 0.11431 10.7023 0.0694668 10.8066C0.0246235 10.911 0.00101956 11.0233 3.23065e-05 11.1369C-0.000954946 11.2505 0.020694 11.3632 0.0637164 11.4684C0.106739 11.5735 0.170273 11.669 0.250611 11.7494C0.33095 11.8297 0.426484 11.8933 0.531639 11.9363C0.636794 11.9793 0.749465 12.001 0.863076 12C0.976687 11.999 1.08896 11.9754 1.19336 11.9305C1.29775 11.8857 1.39216 11.8205 1.47109 11.7388L6 7.20988L10.5289 11.7388C10.6903 11.8946 10.9064 11.9809 11.1308 11.9789C11.3551 11.977 11.5697 11.887 11.7284 11.7284C11.887 11.5697 11.977 11.3551 11.9789 11.1308C11.9809 10.9064 11.8946 10.6903 11.7388 10.5289L7.20988 6Z"
                            fill="#9CA3AF"/>
                      </g>
                      <defs>
                        <clipPath id="clip0_1531_4920">
                          <rect width="12" height="12" fill="white"/>
                        </clipPath>
                      </defs>
                    </svg>
                  </a>
                </div>
                <div class="sm:flex sm:items-start">
                  <div class="px-4 py-4">
                    <h3 class="text-lg font-bold font-mulish text-gray-700">This site is not available</h3>
                    <p class="text-sm font-normal font-mulish text-gray-500">
                      Sorry, but this site is temporarily not available for mobile. Please switch to desktop to
                      interact and view this site.
                    </p>
                  </div>
                </div>
              </div>
              <div class="flex justify-between gap-2 mx-8 pb-7">
                <a
                    href="#"
                    class="border border-gray-200 text-white bg-primary-700 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                  Ok
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
        </div>
    </div>
</template>
