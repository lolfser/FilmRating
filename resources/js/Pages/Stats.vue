<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CompStats from '@/FilmRatingComponents/Stats.vue';
</script>

<template>
    <AppLayout title="Filme">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Filme
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <CompStats
                        :stats="stats"
                        :statsGlobalRatingCount="statsGlobalRatingCount"
                        :noDurationStats="noDurationStats"
                        :keywordStats="keywordStats"
                        :genreStats="genreStats"
                        :footerLinks="footerLinks" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
  props: [
    'stats',
    'statsGlobalRatingCount',
    'noDurationStats',
    'genreStats',
    'keywordStats',
    'footerLinks'
  ],
  computed: {
    headline: function () {return "Statistiken";},
  }
}
</script>
