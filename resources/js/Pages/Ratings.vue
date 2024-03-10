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
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <CompFilmViewer
                        :films="films"
                        :headline="headline"
                        :viewerId="viewerId"
                        :grades="grades"
                        :genres="genres"
                        :languages="languages"
                        :active_filter="active_filter"
                        :filmModifications="filmModifications"
                        :_token="_token"
                        :footerLinks="footerLinks" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
  props: ['films', 'grades', 'viewerId', 'footerLinks', 'genres', '_token', 'languages', 'active_filter', 'filmModifications'],
  computed: {
    headline: function () {return "Deine Bewertungen";},
  }
}
</script>
