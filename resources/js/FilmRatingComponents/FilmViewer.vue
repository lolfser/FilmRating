<script setup>
import Headline from './Headline.vue';
import Footer from './Footer.vue';
import FilmRow from "@/FilmRatingComponents/FilmRow.vue";
import PrimaryButton from '../Components/PrimaryButton.vue';

</script>
<template>
    <Headline :headline="headline" />
    <form method="post">
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <input type="hidden" name="_token" :value="_token" />
            <label><input type="radio" name="filter" value="all" v-model="filter"> ohne Einschränkung</label>&nbsp;&nbsp;&nbsp;
            <label><input type="radio" name="filter" value="open" v-model="filter"> nur offene</label>&nbsp;&nbsp;&nbsp;
            <label><input type="radio" name="filter" value="rated" v-model="filter"> nur bewertete</label>&nbsp;&nbsp;&nbsp;
            <PrimaryButton>Filtern</PrimaryButton>
        </div>
    </form>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <table class="table">
                <tr>
                  <th>Nr.</th>
                  <th>Name</th>
                  <th>Globale Einstellungen</th>
                  <th>Wertungen & dein Kommentar</th>
                  <th>Actions</th>
                </tr>
                <FilmRow v-for="film in films" :film="film"
                    :ratings="film.ratings"
                    :grades="grades"
                    :genres="genres"
                    :languages="languages"
                    :filmstatus="filmstatus"
                    :viewerId="viewerId"
                    :film-modifications="filmModifications"
                    :viewers="viewers"
                    :keywords="keywords"
                    :user="user"
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
        'filmstatus',
        'viewerId',
        'active_filter',
        'headline',
        'footerLinks',
        'filmModifications',
        'keywords',
        'viewers',
        'user',
        '_token'
    ],
    data() {
        return {
            filter: this.$props.active_filter,
        }
    }
}
</script>
