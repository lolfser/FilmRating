<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Dashboard from '@/FilmRatingComponents/Dashboard.vue';
import Welcome from '@/FilmRatingComponents/Welcome.vue';
</script>

<template>
    <AppLayout title="Dashboard" v-if="isLoggedIn">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Dashboard :footerLinks="footerLinks" />
                </div>
            </div>
        </div>
    </AppLayout>
    <Welcome v-else>

    </Welcome>
</template>
<script>
export default {
  props: ['footerLinks', 'isLoggedIn'],
}
</script>
