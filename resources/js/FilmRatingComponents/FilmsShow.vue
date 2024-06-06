<script setup>
import FilmsList from './FilmsList.vue';
</script>
<template>
    <FilmsList :films="[film]" headline="Filmansicht" />
</template>
<script>
export default {
  props: ['film'],
}
</script>
