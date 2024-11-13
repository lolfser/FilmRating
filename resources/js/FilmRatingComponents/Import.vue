<script setup>
import PrimaryButton from '../Components/PrimaryButton.vue';
import Headline from './Headline.vue';
import Footer from './Footer.vue';

</script>
<template>
<Headline :headline="headline" />
<div>
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <br>TODO: Ist ziemlich customized, generalisieren<br><br><br>
        <form method="post">
            <input type="hidden" name="_token" v-model="token" />
            <table>
                <tbody>
                    <tr><td>Jahr:</td><td><input type="text" name="year" value="49" /></td></tr>
                    <tr><td>Title-Header:</td><td><input type="text" name="title" value="Originalfilmtitel" /></td></tr>
                    <tr><td>Film-Nr.</td><td><input type="text" name="film-id" value="Schlüssel Nr." /></td></tr>
                    <tr><td>Duration-Header:</td><td><input type="text" name="duration" value="Laufzeit" /></td></tr>
                    <tr><td>Separator:</td><td><input type="text" name="separator" value="," /></td></tr>
                    <tr><td>Wert-Abgrenzung:</td><td><input type="text" name="enclosure" value='"' /></td></tr>
                    <tr><td>Daten:</td><td><textarea name="importdata" style="width: 800px" /></td></tr>
                </tbody>
            </table>
            In "Daten" müssen die zu importierenden Daten im CSV-Format sein: UTF-8, Semikolon (;) als Separator und &quot als Texttrenner.
            <br>Bpsw.:
            <pre style="border: 1px solid black; padding: 10px; background-color: #eee">
Schlüssel Nr.;Originalfilmtitel;Laufzeit
3017;Şöyle Anlatayım;00:19:32
3022;"""Bears - Quiet Breaks The Dawn""";00:59:27
</pre>
            Existiert bereits eine Filmnummer, wird dieser Film beim Importieren übersprungen.
            <br><br><PrimaryButton>Importieren</PrimaryButton>
        </form>
    </div>
</div>
<Footer :footerLinks="footerLinks" />
</template>
<script>
export default {
  props: ['_token', 'headline', 'errors', 'footerLinks'],
  methods: {
  },
  computed: {
    token: function () {
      return this._token
    }
  }
}
</script>
