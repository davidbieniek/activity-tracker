<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { Bar } from "vue-chartjs";

// Consider moveing it to separate component
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
);

const props = defineProps({
    categories: Array,
});

const chartDataActivitiesCount = computed(() => {
    return {
        labels: props.categories.map((category) => category.name),
        datasets: [
            {
                label: "Ilość aktywności dla zadanej kategorii",
                backgroundColor: "#f87979",
                borderColor: "#f06262",
                borderWidth: 1,
                hoverBackgroundColor: "#f06262",
                hoverBorderColor: "#f87979",
                data: props.categories.map(
                    (category) => category.activities_count
                ),
            },
        ],
    };
});

// Chart options
const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: "rgba(200, 200, 200, 0.3)",
            },
            ticks: {
                color: "#4a4a4a",
                font: {
                    size: 14,
                },
            },
        },
        x: {
            grid: {
                color: "rgba(200, 200, 200, 0.3)",
            },
            ticks: {
                color: "#4a4a4a",
                font: {
                    size: 14,
                },
            },
        },
    },
    plugins: {
        legend: {
            labels: {
                color: "#4a4a4a",
                font: {
                    size: 16,
                },
            },
        },
        tooltip: {
            backgroundColor: "#f87979",
            titleFont: {
                size: 16,
                weight: "bold",
            },
            bodyFont: {
                size: 14,
            },
            borderColor: "#f06262",
            borderWidth: 1,
        },
    },
});

// Define the form
const form = useForm({
    name: "",
});
</script>

<template>
    <Head title="Category" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Kategorie
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gray-100 text-gray-900 rounded-lg">
                        <form
                            @submit.prevent="
                                form.post('/category', {
                                    preserveState: false,
                                })
                            "
                            class="flex items-center space-x-4"
                        >
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Wpisz nazwę kategorii"
                                required
                                class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            />
                            <button
                                type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg"
                            >
                                Dodaj kategorię
                            </button>
                        </form>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-8"
                            v-if="categories.length"
                        >
                            <div
                                v-for="category in categories"
                                :key="category.id"
                                class="p-4 border border-black rounded-lg hover:bg-gray-200 flex items-center justify-between"
                            >
                                <Link
                                    :href="route('category.show', { category })"
                                    class="text-lg font-semibold text-blue-600 hover:underline"
                                >
                                    {{ category.name }}
                                </Link>
                                <button
                                    class="mt-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg"
                                    @click="
                                        router.delete(
                                            route('category.destroy', {
                                                id: category.id,
                                            })
                                        )
                                    "
                                >
                                    Usuń
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-8 flex flex-col items-center" v-if="categories.length">
            <h3 class="text-xl font-semibold text-gray-800">
                Statystyki kategorii:
            </h3>
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-7xl mt-4 px-4"
            >
                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="w-full p-6 border rounded-lg bg-white shadow-lg"
                >
                    <h4 class="text-lg font-semibold text-gray-800">
                        {{ category.name }}
                    </h4>
                    <p class="mt-2 text-gray-600">
                        Ilość aktywności: {{ category.activities_count }}
                    </p>
                    <p class="text-gray-600">
                        Średni czas trwania:
                        <span v-if="category.activities_avg_spent_time">
                            {{ category.activities_avg_spent_time }}
                        </span>
                        <span v-else>Brak aktywności</span>
                    </p>
                </div>
            </div>
        </div>
        <div
            v-else
            class="flex items-center justify-center py-12 bg-red-100 text-red-700 rounded-lg border border-red-400"
        >
            Brak danych
        </div>
        <div
            class="max-w-4xl rounded px-8 py-8 mx-auto bg-white"
            v-if="categories.length"
        >
            <Bar :data="chartDataActivitiesCount" :options="chartOptions" />
        </div>

        <!-- Xd -->
        <br />
        <br />
    </AuthenticatedLayout>
</template>
