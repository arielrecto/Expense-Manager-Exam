<script setup>


defineProps({
    columnLabels: Array,
    isHideAction: false
})



const defaultLabels = [
    'Product Name',
    'Color',
    'Category',
    'price'
]

</script>



<template>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-[20rem]">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                <tr>

                    <template v-for="column, index in columnLabels ?? defaultLabels" :key="index">
                        <th scope="col" class="px-6 py-3">
                            {{ column }}
                        </th>
                    </template>
                    <template v-if="!isHideAction">
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <slot />
            </tbody>
        </table>
    </div>

</template>
