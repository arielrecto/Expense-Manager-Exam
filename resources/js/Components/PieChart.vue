<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
    labels : Array,
    series  : Array
});



console.log(props)

const chartElement = ref();
let options = {
    series:  props.series ?? [44, 55, 13, 43, 22],
    chart: {
        width: 380,
        type: 'pie',
    },
    labels:  props.labels ?? ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
};


onMounted(() => {
    if (chartElement.value) {
        const chart = new ApexCharts(chartElement.value, options);
        chart.render();
    }
})


const updateSeries = (newSeries) => {
    if (chart.value) {
        chart.value.updateSeries(newSeries);
    }
};

</script>


<template>
    <div ref="chartElement" class="h-full w-full">

    </div>
</template>