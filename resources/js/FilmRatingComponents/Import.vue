<script setup>
import PrimaryButton from '../Components/PrimaryButton.vue';
import Headline from './Headline.vue';
import Footer from './Footer.vue';

</script>
<template>
<Headline :headline="headline" />
<div>
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" v-model="token" />
            <table>
                <tbody>
                    <tr><td>Jahr:</td><td><input type="text" name="year" value="49" /></td></tr>
                    <tr><td>Title-Header:</td><td><input type="text" name="title" value="Originalfilmtitel" /></td></tr>
                    <tr><td>Film-Nr.</td><td><input type="text" name="film-id" value="Schlüssel Nr." /></td></tr>
                    <tr><td>Duration-Header:</td><td><input type="text" name="duration" value="Laufzeit" /></td></tr>
                    <tr><td>Separator:</td><td><input type="text" name="separator" value="," /></td></tr>
                    <tr><td>Wert-Abgrenzung:</td><td><input type="text" name="enclosure" value='"' /></td></tr>
                    <tr><td>int-cast auf Film-Identifier:</td><td><input type="text" name="identifierIntCast" value="true" /></td></tr>
                    <tr>
                        <td style="vertical-align: top;">Daten:</td>
                        <td>
                            Die Datei muss im CSV-Format sein und den Zeichensatz UTF-8 haben. Der Separator und Texttrenner müssen
                            den Felder oben entsprechen.
                            <br>Bpsw.:
                            <pre style="border: 1px solid black; padding: 10px; background-color: #eee">
Schlüssel Nr.,Originalfilmtitel,Laufzeit
3017,"Şöyle Anlatayım","00:19:32"
3022,"""Bears - Quiet Breaks The Dawn""","59:27Min"</pre>
                            <br>
                            <input type="file" name="importdata" accept=".csv" />
                            <br>
                            Existiert bereits eine Filmnummer, wird dieser Film beim Importieren übersprungen.
                        </td>
                    </tr>
                </tbody>
            </table>
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
