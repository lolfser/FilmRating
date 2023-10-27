<script setup>
import Headline from './Headline.vue';
import Footer from './Footer.vue';
import FilmRow from "@/Components/FilmRow.vue";

</script>
<template>
    <Headline :headline="headline" />
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <table class="table">
                <tr>
                    <th>Nr.</th>
                    <th>Name</th>
                    <th>Sprache</th>
                    <th>Genre</th>
                    <th>Wertungen von anderen</th>
                    <th>deine Wertung</th>
                    <th>dein Kommentar</th>
                    <th>Actions</th>
                </tr>
               <FilmRow v-for="film in films" :film="film" :ratings="film.ratings" :grades="grades" :viewerId="viewerId" />
            </table>

        </div>

        <Footer :PERMISSION_ADD_FILMS="PERMISSION_ADD_FILMS" />
    </div>
</template>
<script>

export default {

    props: [
        'films',
        'grades',
        'viewerId',
        'headline',
        'PERMISSION_ADD_FILMS'
    ]

}
</script>
