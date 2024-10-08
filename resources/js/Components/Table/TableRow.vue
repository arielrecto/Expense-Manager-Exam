<script setup>


defineProps({
    rowData: Object,
})




</script>



<template>




    <tr class="odd:bg-white  even:bg-gray-50  border-b ">

        <slot />

    </tr>

</template>
