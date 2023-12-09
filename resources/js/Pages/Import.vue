<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CompImport from '@/Components/Import.vue';
</script>

<template>
    <AppLayout title="Filme">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Filmimport
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <CompImport :headline="headline" :_token="_token" :filmsources="filmsources" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
  props: ['films', 'PERMISSION_ADD_FILMS', '_token', 'filmsources'],
  computed: {
    headline: function () {return "Filmeimport";},
  }
}
</script>
