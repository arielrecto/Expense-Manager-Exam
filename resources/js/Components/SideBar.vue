<script setup>

import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps({
    user: Object
})


const isAdmin = props.user.roles[0].name === 'admin';


console.log(isAdmin);

const routes = ref([
    {
        'name': 'dashboard',
        'url': '/dashboard',
        'is_admin': false,
        'children': []
    },
    {
        'name': 'user management',
        'url': null,
        'is_admin': true,
        'children': [
            {
                'name': 'Roles',
                'url': '/admin/roles',
                'is_admin': true
            },
            {
                'name': 'User',
                'url': '/admin/user',
                'is_admin': true
            },
        ]
    },
    {
        'name': 'Expenses Management',
        'url': null,
        'is_admin': false,
        'children': [
            {
                'name': 'Expenses Category',
                'url': '/admin/categories',
                'is_admin': true
            },
            {
                'name': 'expenses',
                'url': '/expenses',
                'is_admin': false
            }
        ]
    },
])


const collapseId = ref();


const collapseOpen = (index) => {
    if (collapseId.value === index) {
        collapseId.value = null
        return;
    }

    collapseId.value = index;
}

</script>


<template>

    <div class="w-1/6 bg-sky-950 h-screen p-5 text-white capitalize">

        <div class="capitalize flex items-center flex-col w-full h-auto gap-2 mb-10">
            <img src="/logo.jpg" alt="" srcset="" class="aspect-square rounded-full h-16">
            <h1 class="text-lg">{{ user?.name ?? 'admin 2' }}</h1>
        </div>


        <ul class="flex flex-col gap-2">

            <template v-for="route, index in routes" :key="index">
                <li v-if="isAdmin && route.is_admin" class="p-1  rounded-lg w-full">
                    <template v-if="!route.url">
                        <button @click="collapseOpen(index)">{{ route.name }}</button>
                    </template>
                    <template v-else>
                        <Link :href="`${route.url ?? '#'}`">{{ route.name }} </Link>
                    </template>

                    <template v-if="route.children.length !== 0">
                        <ul v-if="collapseId === index">
                            <template v-for="children, index in route.children" :key="index">
                                <li class="p-1 ml-3 hover:bg-white hover:text-sky-950 rounded-lg w-full duration-700">
                                    <Link :href="`${children.url}`">{{ children.name }} </Link>
                                </li>
                            </template>
                        </ul>
                    </template>
                </li>
                <li v-else-if="!route.is_admin" class="p-1  rounded-lg w-full">
                    <template v-if="!route.url">
                        <button @click="collapseOpen(index)">{{ route.name }}</button>
                    </template>
                    <template v-else>
                        <Link :href="`${route.url ?? '#'}`">{{ route.name }} </Link>
                    </template>

                    <template v-if="route.children.length !== 0">
                        <ul v-if="collapseId === index">
                            <template v-for="children, index in route.children" :key="index">

                                <template v-if="isAdmin && children.is_admin">
                                    <li
                                        class="p-1 ml-3 hover:bg-white hover:text-sky-950 rounded-lg w-full duration-700">
                                        <Link :href="`${children.url}`">{{ children.name }} </Link>
                                    </li>
                                </template>
                                <template v-else-if="!children.is_admin">
                                    <li
                                        class="p-1 ml-3 hover:bg-white hover:text-sky-950 rounded-lg w-full duration-700">
                                        <Link :href="`${children.url}`">{{ children.name }} </Link>
                                    </li>
                                </template>
                            </template>
                        </ul>
                    </template>
                </li>
            </template>

        </ul>
    </div>
</template>
