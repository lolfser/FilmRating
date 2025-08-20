<script setup>
</script>
<template>
<div :title="receiveTitle(element.description)" style="cursor: pointer; padding-bottom: 4px">
    <div>
    {{ element.film_identifier }}:
    {{ element.name }} {{defineAudioString(element)}}
    </div>
    <div style="line-height: 10px">
        <span style="font-size: 10px">Status: {{ element.filmstatusName }}</span>
        &nbsp;&nbsp;
        <span style="font-size: 10px">Länge: {{ receiveDuration(element) }}min.</span>
        &nbsp;&nbsp;
        <span v-html="defineGenreString(element)"></span>
        &nbsp;&nbsp;
        <span style="font-size: 10px">{{ defineModificationString(element) }}</span>
    </div>
</div>
</template>
<script>

export default {
    props: [
        'element'
    ],
    methods: {
        receiveTitle(description) {
            return description == '' ? 'keine Beschreibung' : description
        },
        defineAudioString: function (film) {
            let audioString = '';
            let subtitleString = '';
            film.languages.forEach(function (language) {
               if (language?.type === "audio") {
                   audioString += (audioString === '' ? '' : '_') + language.language
               } else if (language?.type === "subtitle") {
                   subtitleString += (subtitleString === '' ? '' : '_') + language.language
               }
            });
            if (audioString !== "" || subtitleString !== "") {
                if (subtitleString !== "") {
                    return " (" + audioString.toUpperCase() + "_" + subtitleString.toLowerCase() + ")";
                } else {
                    return " (" + audioString.toUpperCase() + ")";
                }
            }
            return '';
        },
        defineGenreString: function (film) {
            let genreString = '';
            for (let genre of film.genres) {
                if (genreString !== '') {
                    genreString += ' ';
                }
                genreString += '<span ' +
                    'style="' +
                        'background-color: ' + genre.bgcolor + ';' +
                        'color: ' + genre.fontcolor + ';' +
                        'font-size: 12px;' +
                    '">' + genre.name + '</span>'
            }
            return genreString;
        },
        defineModificationString: function(film) {
            let resultString = '';
            for (let filmModification of film.filmmodifications) {
                if (resultString !== '') {
                    resultString += ', ';
                }
                resultString += filmModification.name
            }
            return resultString;
        },
        receiveDuration: function(film) {
            return this.receiveDurationFromSecs(film.duration);
        },
        receiveDurationFromSecs: function(seconds) {
            return (seconds / 60).toFixed(2);
        },

    }
}
</script>
<style>
    .green {
        background-color: green;
    }
    .red {
        background-color: red;
    }
    .list-group-item:nth-child(2n +2) {
        background-color: lightgray;
    }
    .list-group-item:nth-child(2n +1) {
        background-color: peachpuff;
    }
</style>
