<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 duration-700 ease-in-out text-black/50 dark:text-black/50">
       
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
        <div class="relative w-full min-h-screen ">
 
  <div class="flex items-center justify-center min-h-screen space-x-6">
  
    <div class="duration-1000 ease-in-out  min-h-screen pl-3 text-white [clip-path:polygon(0_0,_100%_0,_64%_100%,_0%_100%)] w-2/3 flex bg-blue-800 justify-left items-center">
      <h1 class="text-5xl font-bold ">Welcome to Guest Manager 2.0</h1>
    </div>

    
    <nav v-if="canLogin" class=" min-h-screen flex justify-start w-1/3 pr-30 items-center space-x-2">
      <Link
        v-if="$page.props.auth.user"
        :href="route('dashboard')"
        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent duration-700 ease-in-out hover:text-white-500 "
      >
        Dashboard
      </Link>

      <template v-else>
        <Link
          :href="route('login')"
          class="rounded-md px-3 py-2 text-black ring-1 ring-transparent duration-700 ease-in-out hover:px-5 hover:py-4 hover:text-white-500 bg-blue-800 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-black/80 dark:focus-visible:ring-white"
        >
          Log in
        </Link>

        <Link
          v-if="canRegister"
          :href="route('register')"
          class="rounded-md px-3 py-2 text-black ring-1 ring-transparent bg-blue-800 duration-700 ease-in-out hover:px-5 hover:py-4 hover:text-white-500 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-black/80 dark:focus-visible:ring-white"
        >
          Register
        </Link>
      </template>
    </nav>
  </div>

  <main class="mt-6">
    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
     
    </div>
  </main>
</div>


        </div>
    </div>
</template>
