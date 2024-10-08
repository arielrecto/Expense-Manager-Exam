<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import SideBar from '@/Components/SideBar.vue';
import { Link } from '@inertiajs/vue3';


defineProps({
    user : Object
});
const showingNavigationDropdown = ref(false);


</script>

<template>
    <div class="min-h-screen w-full max-w-screen overflow-x-hidden bg-gray-100 h-screen flex">
        <SideBar :user="user"/>
        <div class="flex flex-col gap-2 w-full">

            <nav class="w-full flex items-center justify-end shadow-lg shadow-gray-300 p-5 text-sky-950">
                <div class="flex items-center gap-5">
                    <p class="text-sm">{{ user?.email  ?? 'Admin 2'}}</p>
                    <Link href="/logout" method="POST" class="capitalize text-sm"> log out </Link>
                </div>
            </nav>
            <main class="p-5 flex flex-col gap-5">
                <slot />
            </main>
        </div>
    </div>
</template>
