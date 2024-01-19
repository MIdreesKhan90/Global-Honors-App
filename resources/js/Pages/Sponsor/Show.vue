<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ChevronLeftIcon } from "@heroicons/vue/24/solid";


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <section class="bg-white pt-14 pb-2 h-92vh overflow-x-auto">
            <div class="container mx-auto">
                <a href="">
                    <p class="text-sm font-medium text-gray-700 flex items-center">
                        <ChevronLeftIcon class="h-4 w-4 font-extrabold text-gray-500" aria-hidden="true"/>
                        Back</p>
                </a>
                <h1 class="text-gray-800 text-4xl font-mulish font-bold mt-8">Forbes</h1>
                <div class="mt-8 pb-5 border-b border-gray-200">
                    <h3 class="text-gray-900 text-xl font-bold font-mulish">About</h3>
                </div>
                <div class="grid grid-cols-2 gap-24 mt-5">
                    <div>
                        <h6 class="text-gray-900 text-sm font-bold font-mulish">Description</h6>
                        <p class="text-gray-500 text-sm font-normal font-mulish">
                            To find out which companies have made progress on this front, Forbes partnered with market
                            research firm Statista to create the sixth annual list of America’s Best Employers for
                            Diversity. To determine the rankings, Statista surveyed more than 45,000 Americans working for
                            businesses with at least 1,000 employees. Participants were asked to anonymously rate their
                            organizations based on criteria such as age, gender, ethnicity, disability and LGBTQ+ equality,
                            as well as general diversity.
                            <br>
                            <br>
                            Respondents belonging to underrepresented groups were also asked to recommend businesses other
                            than their own. In addition, each company’s diversity-related best practices—such as the
                            presence of employee resource groups, the publication of diversity data and the percentage of
                            women in board and executive roles—were reviewed and incorporated into the rankings. The top 500
                            companies made the final list.
                        </p>
                        <h6 class="mt-5 text-gray-900 text-sm font-bold font-mulish">Website</h6>
                        <a href="" class="text-sm font-mulish text-primary-700 underline">https://www.forbes.com/lists</a>
                    </div>
                    <div>
                        <h6 class="text-gray-900 text-sm font-bold font-mulish">Awards</h6>
                        <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Forbes- America’s Best
                            Employers For New Grads</a>
                        <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Forbes- America’s Best
                            Employers For Women</a>
                        <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                            Management Consulting Firms</a>
                        <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                            Management Consulting Firms</a>
                        <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                            Management Consulting Firms</a>
                        <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                            Management Consulting Firms</a>
                        <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                            Management Consulting Firms</a>
                        <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                            Management Consulting Firms</a>
                        <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                            Management Consulting Firms</a>
                        <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                            Management Consulting Firms</a>
                        <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                            Management Consulting Firms</a>
                    <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                        Management Consulting Firms</a>
                    <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                        Management Consulting Firms</a>
                    <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                        Management Consulting Firms</a>
                    <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                        Management Consulting Firms</a>
                    <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                        Management Consulting Firms</a>
                    <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                        Management Consulting Firms</a>
                    <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                        Management Consulting Firms</a>
                    <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                        Management Consulting Firms</a>
                    <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                        Management Consulting Firms</a>
                    <a href="" class="text-primary-700 font-mulish text-sm mt-3 block underline">Americas Best
                        Management Consulting Firms</a>
                </div>
            </div>
        </div>
    </section>

</AuthenticatedLayout></template>
