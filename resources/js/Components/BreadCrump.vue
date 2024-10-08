<script setup>
    defineProps({
        labels : Array
    })


    const defaultLabels = [
        'Home',
        'Documents'
    ];
</script>



<template>
      <div class="breadcrumbs text-sm">
                <ul>

                    <template v-for="label, index in labels" :key="labels">
                        <li><a>{{ label }}</a></li>
                    </template>
                        <li>
                            <slot />
                        </li>
                    
                    <!-- <li>Add Document</li> -->
                </ul>
            </div>
</template>