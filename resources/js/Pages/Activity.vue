<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, router, usePage, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    category: Object,
});

const maxDate = () => {
    return new Date().toISOString().slice(0, 10);
};

const form = useForm({
    name: "",
    spent_time: "",
    created_date: maxDate(),
});

const addActivity = () => {
    form.post(route("activity.store", { category: props.category }), {
        preserveState: false,
    });
};

const deleteActivity = (activity) => {
    router.delete(route("activity.destroy", { activity }));
};

// No jakby było wiecej query paramsów to by się mogło psuć, a tak to będzie git
const page = usePage();
const query = ref(page.url.split("=")[1]);
const currentFilter = computed(() => query.value || "all");

const isActiveFilter = (filter) => currentFilter.value === filter;
</script>

<template>
    <Head :title="category.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ category.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white shadow-sm sm:rounded-lg p-6 max-w-4xl mx-auto"
                >
                    <form @submit.prevent="addActivity" class="space-y-4">
                        <div class="mb-4">
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Nazwa aktywności
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                id="name"
                                placeholder="Wprowadź aktywność"
                                required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-400 focus:border-blue-400"
                            />
                        </div>

                        <div class="mb-4">
                            <label
                                for="spent_time"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Czas trwania
                            </label>
                            <input
                                v-model="form.spent_time"
                                type="time"
                                id="spent_time"
                                required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-400 focus:border-blue-400"
                            />
                        </div>

                        <div class="mb-4">
                            <label
                                for="created_date"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Data dodania
                            </label>
                            <input
                                v-model="form.created_date"
                                type="date"
                                id="created_date"
                                :max="maxDate()"
                                required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-400 focus:border-blue-400"
                            />
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400"
                            >
                                Dodaj aktywność
                            </button>
                        </div>
                    </form>
                </div>

                <div
                    class="flex items-center justify-center space-x-4 my-4"
                    v-if="category.activities.length"
                >
                    <Link
                        :href="
                            route('category.show', {
                                category,
                                maxCreatedDate: 'today',
                            })
                        "
                        :class="[
                            'px-4 py-2 rounded',
                            isActiveFilter('today')
                                ? 'bg-green-500 text-white'
                                : 'bg-blue-500 text-white hover:bg-blue-700',
                        ]"
                        >Dziś</Link
                    >
                    <Link
                        :href="
                            route('category.show', {
                                category,
                                maxCreatedDate: '7days',
                            })
                        "
                        :class="[
                            'px-4 py-2 rounded',
                            isActiveFilter('7days')
                                ? 'bg-green-500 text-white'
                                : 'bg-blue-500 text-white hover:bg-blue-700',
                        ]"
                        >7 dni</Link
                    >
                    <Link
                        :href="
                            route('category.show', {
                                category,
                                maxCreatedDate: '30days',
                            })
                        "
                        :class="[
                            'px-4 py-2 rounded',
                            isActiveFilter('30days')
                                ? 'bg-green-500 text-white'
                                : 'bg-blue-500 text-white hover:bg-blue-700',
                        ]"
                        >30 dni</Link
                    >
                    <Link
                        :href="route('category.show', { category })"
                        :class="[
                            'px-4 py-2 rounded',
                            isActiveFilter('all')
                                ? 'bg-green-500 text-white'
                                : 'bg-blue-500 text-white hover:bg-blue-700',
                        ]"
                        >Wszystkie</Link
                    >
                </div>

                <!-- Activity Tiles -->
                <div
                    class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                    <div
                        v-for="activity in category.activities"
                        :key="activity.id"
                        class="relative bg-white shadow sm:rounded-lg p-4"
                    >
                        <button
                            @click="deleteActivity(activity)"
                            class="absolute bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                        >
                            X
                        </button>
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ activity.name }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            Czas trwania: {{ activity.spent_time }}
                        </p>
                        <p class="text-sm text-gray-600">
                            Dodane dnia:
                            {{
                                new Date(
                                    activity.created_date
                                ).toLocaleDateString("pl-PL")
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>
.absolute {
    right: 10px !important;
    top: 10px !important;
}
</style>
