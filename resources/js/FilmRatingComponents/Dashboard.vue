<script setup>
  import Footer from './Footer.vue';
</script>
<template>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h1 class="mt-8 text-2xl font-medium text-gray-900">
                Willkommen!
            </h1>
            <p class="mt-6 text-gray-500 leading-relaxed">
                Vielen Dank, dass du uns bei der Sichtung von Filmen bewerten möchtest. Für die Sichtung hast du verschiedene Möglichkeiten.
            </p>
            <ul>
                <li>
                    <a href="/rating/list" style="color: blue">Zu den Filmbewertungen</a>
                </li>
                <li>
                    <a href="/rating/export" style="color: blue">Deine Filmbewertungen exportieren</a>
                </li>
                <li>
                    <a href="/program" style="color: blue">Zur Programmplanung</a>
                </li>
            </ul>
        </div>
        <Footer :footerLinks="footerLinks" />
    </div>
</template>
<script>
export default {
  props: ['footerLinks']
}
</script>
