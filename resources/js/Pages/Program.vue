<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Programm from '@/Components/Program.vue';
</script>

<template>
    <AppLayout title="Filme">
        <div class="py-12">
            <div class="">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Programm
                        :films="films"
                        :programmetas="programmetas"
                        headline="Programm"
                        :footerLinks="footerLinks"
                        :_token="_token"
                     />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
  props: [
      'films', 'programmetas', 'footerLinks', '_token'
  ]
}
</script>
