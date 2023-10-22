<script setup>
</script>

<template>
    <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
        <div>
            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                <a href="/films" class="inline-flex items-center font-semibold text-indigo-700">
                    Liste aller Filme
                </a>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="/rating" class="inline-flex items-center font-semibold text-indigo-700">
                    Filmbewertungen
                </a>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="/films/0/cu" class="inline-flex items-center font-semibold text-indigo-700" v-if="PERMISSION_ADD_FILMS">
                    Film erstellen
                </a>
            </p>
        </div>
    </div>
</template>
<script>
export default {
  props: [
    'PERMISSION_ADD_FILMS',
  ]
}

</script>
