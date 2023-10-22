<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CompFilmViewer from '@/Components/FilmViewer.vue';
</script>

<template>
    <AppLayout title="Filme">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Filme
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <CompFilmViewer :films="films" :headline="headline" :viewerId="viewerId" :grades="grades" :PERMISSION_ADD_FILMS="PERMISSION_ADD_FILMS"/>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
  props: ['films', 'grades', 'viewerId', 'PERMISSION_ADD_FILMS'],
  computed: {
    headline: function () {return "Deine Bewertungen";},
  }
}
</script>
