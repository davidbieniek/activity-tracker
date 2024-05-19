<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Welcome" />
    <div class="w-full h-screen">
        <header class="bg-gray-50 text-black flex justify-end w-full p-4">
            <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('category.index')"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                >
                    Strona główna
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        Logowanie
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        Rejestracja
                    </Link>
                </template>
            </nav>
        </header>

        <main
            class="mt-6 h-1/2 flex flex-col items-center justify-center text-center"
        >
            <h1 class="text-4xl font-bold text-blue-600 mb-4">
                Activity Tracker
            </h1>
            <p class="text-lg text-gray-700">
                Aplikacja dzięki której możesz śledzić swoje codzienne
                aktywności
            </p>
        </main>
    </div>
</template>
