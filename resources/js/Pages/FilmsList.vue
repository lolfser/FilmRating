<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CompFilmsList from '@/FilmRatingComponents/FilmsList.vue';
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
                    <CompFilmsList
                        :films="films"
                        :headline="headline"
                        :footerLinks="footerLinks" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
  props: ['films', 'footerLinks'],
  computed: {
    headline: function () {return "Liste aller Filme";},
  }
}
</script>
