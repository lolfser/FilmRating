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
                  <th>Globale Einstellungen</th>
                  <th>Wertungen von anderen</th>
                  <th>deine Wertung</th>
                  <th>dein Kommentar</th>
                  <th>Actions</th>
                </tr>
                <FilmRow v-for="film in films" :film="film"
                    :ratings="film.ratings"
                    :grades="grades"
                    :genres="genres"
                    :languages="languages"
                    :viewerId="viewerId"
                    :_token="_token" />
            </table>

        </div>
        <Footer :footerLinks="footerLinks" />
    </div>
</template>
<script>

export default {
    props: [
        'films',
        'grades',
        'genres',
        'languages',
        'viewerId',
        'headline',
        'footerLinks',
        '_token'
    ]
}
</script>
