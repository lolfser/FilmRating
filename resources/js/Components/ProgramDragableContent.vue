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
            let lang1 = film.languages[0]?.language;
            let type1 = film.languages[0]?.type;
            let lang2 = film.languages[1]?.language;
            let type2 = film.languages[1]?.type;
            if (typeof lang1 !== "undefined") {
                if (type1 === 'audio') {
                    audioString = lang1.toUpperCase();
                } else {
                    subtitleString = lang1.toLowerCase();
                }
            }
            if (typeof lang2 !== "undefined") {
                if (type2 === 'audio') {
                    audioString = lang2.toUpperCase();
                } else {
                    subtitleString = lang2.toLowerCase();
                }
            }
            if (audioString !== "" || subtitleString !== "") {
                if (subtitleString !== "") {
                    return " (" + audioString + "_" + subtitleString + ")";
                } else {
                    return " (" + audioString + ")";
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
