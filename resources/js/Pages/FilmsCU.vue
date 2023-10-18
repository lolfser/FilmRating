<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CompFilmsCU from '@/Components/FilmsCU.vue';
</script>

<template>
    <AppLayout title="Filme">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <CompFilmsCU :film="film" :_token="_token" :errors="errors" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
  props: ['film', '_token', "errors"],
}
</script>
