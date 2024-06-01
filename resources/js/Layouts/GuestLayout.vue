<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Arrow from '@/Components/arrow.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center pt-6 bg-gray-100 relative">
      <Link href="/" class="absolute top-4 left-4">
        <Arrow class="block h-9 w-auto fill-current text-gray-800" />
      </Link>
  
      <div class="flex flex-col items-center">
        <Link href="/">
          <h1 class="text-4xl font-bold text-blue-600 mb-4">
            Activity Tracker
          </h1>
        </Link>
      </div>
  
      <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <slot />
      </div>
    </div>
  </template>
