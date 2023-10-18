<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CompFilmsCreate from '@/Components/FilmsCreate.vue';
</script>

<template>
    <AppLayout title="Filme">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <CompFilmsCreate :film="film" :_token="_token" :headline="headline" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
  props: ['film', '_token'],
  computed: {
    headline: function() {return "Film bearbeiten";},
  },
}
</script>
