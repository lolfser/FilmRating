<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CompRatingEdit from '@/FilmRatingComponents/RatingEdit.vue';
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
                    <CompRatingEdit :film="film"
                        :rating="rating" headline="Dein Rating" :errors="errors" :languages="languages" :_token="_token" :grades="grades"
                        :genres="genres" :filmModifications="filmModifications"
                     />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
    props: ['film', 'rating', 'errors', 'languages', '_token', 'grades', 'genres', 'filmModifications'],
}
</script>
