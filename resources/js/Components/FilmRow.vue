<script setup>
import MultiSelect from "@/Components/MultiSelect.vue";
</script>

<template>

    <tr>
        <td>{{ film.film_identifier }}</td>
        <td>{{ film.name }}</td>
        <td>{{ calculateLanguage(film) }}</td>
        <td>{{ film.genre }}</td>
        <td>{{ otherGrade(film) }}</td>
        <td>
            <MultiSelect :options="grades" :optionLabel="dropdownGrade" :optionValue="dropdownGradeValue"
                         placeholder="wähle"
                         :maxSelectedLabels="2" :selectionLimit="2" autoFilterFocus="true" v-model="selectedOptions"
            />
        </td>
        <td>{{ viewerComment(film) }}</td>
        <td>
            <div v-html="generateCULink(film)"/>
        </td>
    </tr>
</template>
<script>
export default {

    props: [
        'film',
        'grades',
        'viewerId',
        'rating',
        'selectedOptions'
    ],
    mounted() {
        this.selectedOptions =  [parseInt(this.viewerGrade())];
    },
    data() {
        return {
            'selectedOptions': []
        }
    },
    methods: {
        dropdownGradeValue: function (grade) {
            return grade.id;
        },
        dropdownGrade: function (grade) {
            return grade.value + "" + grade.trend;
        },
        calculateLanguage: function (film) {
            let result = '';
            film.languages.every(function (language) {
                if (result !== '') result = result + ' / ';
                result += language.type + ' ' + language.language;
                return true;
            });
            return result;
        },
        viewerComment: function (film) {
            const viewerId = this.viewerId
            let returnValue = "";
            film.ratings.every(function (rating) {
                if (rating.viewers_id == viewerId) {
                    returnValue = rating.comment;
                    return false; // break
                }
                return true; // continue;
            });
            return returnValue;
        },
        generateCULink: function (film) {
            const reviewId = this.getReviewId(film);
            return "<a href='/rating/" + film.film_identifier + "/cu'>" + (reviewId != 0 ? "edit" : "neu") + "</a>";
        },
        getReviewId: function (film) {
            const viewerId = this.viewerId;
            let returnValue = "";
            film.ratings.every(function (rating) {
                if (rating.viewers_id == viewerId) {
                    returnValue = rating.id;
                    return false; // break;
                }
                return true; // continue;
            });
            return returnValue;
        },
        viewerGrade: function () {
            let film = this.film;
            const viewerId = this.viewerId;
            const grades = this.grades;
            let returnValue = "";
            film.ratings.every(function (rating) {
                if (rating.viewers_id != viewerId) {
                    return true; // continue;
                }
                const gradeId = rating.grades_id;
                grades.every(function (gradeFromList) {
                    if (gradeId == gradeFromList.id) {
                        returnValue = gradeFromList.value + "" + gradeFromList.trend;
                        return false; // break;
                    }
                    return true; // continue;
                });
                if (returnValue != "") {
                    return false; // break;
                }
                return true; // continue;
            });
            return returnValue;
        },
        otherGrade: function (film) {
            const viewerId = this.viewerId;
            const grades = this.grades;
            let returnValue = "";

            film.ratings.every(function (rating) {
                if (rating.viewers_id == viewerId) {
                    return true; // continue;
                }

                const gradeId = rating.grades_id;
                grades.every(function (gradeFromList) {

                    if (gradeId == gradeFromList.id) {
                        if (returnValue !== "") returnValue = returnValue + " / ";
                        returnValue += gradeFromList.value + "" + gradeFromList.trend;
                        return false; // break;
                    }
                    return true; // break;
                });
                return true; // continue;
            });
            return returnValue;
        },
    }
}

</script>
