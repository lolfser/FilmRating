<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Programm from '@/FilmRatingComponents/Program.vue';
</script>

<template>
    <AppLayout title="Filme">
        <div class="py-12">
            <div class="">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Programm
                        :films="films"
                        :programmetas="programmetas"
                        :filmstatus="filmstatus"
                        headline="Programm"
                        :footerLinks="footerLinks"
                        :_token="_token"
                        :filter="filter"
                        :years="years"
                        :keywords="keywords"
                        :genres="genres"
                        :filmmodifications="filmmodifications"
                     />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
  props: [
      'films', 'programmetas', 'footerLinks', '_token', 'filmstatus', 'filter', 'years', 'keywords', 'genres', 'filmmodifications'
  ]
}
</script>
