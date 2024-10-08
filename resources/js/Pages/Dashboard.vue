<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PieChart from '@/Components/PieChart.vue'
import TableColumn from '@/Components/Table/TableColumn.vue'
import TableRow from '@/Components/Table/TableRow.vue'


defineProps({
    auth: Object,
    categories: Object
});


</script>

<template>

    <Head title="Dashboard" />
    <AuthenticatedLayout :user="auth.user">

        <div class="flex items-center justify-between text-sky-950">
            <h1 class="text-lg font-bold">
                My Expenses
            </h1>
            <BreadCrumps>
                Dashboard
            </BreadCrumps>
        </div>

        <div class="w-ful flex gap-2 ">

            <div class="w-1/2 flex flex-col gap-2 capitalize">
                <TableColumn :columnLabels="[
                    'expenses categories',
                    'total'
                ]" :isHideAction="true">
                    <template v-for="item, index in categories" :key="index">
                        <TableRow>
                            <th class="p-2">{{ item.category }}</th>
                            <th class="p-2"> ₱{{ item.total_expenses }}</th>
                        </TableRow>
                    </template>




                </TableColumn>

            </div>
            <div class="w-1/2 h-full">
                <PieChart :labels="categories.map((item) => item.category)"
                    :series="categories.map((item) => item.total_expenses)" />
            </div>


        </div>

        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template> -->


    </AuthenticatedLayout>
</template>
