<script setup>
import PrimaryButton from './PrimaryButton.vue';
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
                <tr><td>Jahr:</td><td><input type="text" name="year" value="48" /></td></tr>
                <tr><td>Film-Source:</td><td>
                    <span v-for="filmsource in filmsources">
                        <label><input type="radio" name="filmsources_id" :value="filmsource.id" /> {{filmsource.name}}</label>
                        &nbsp;&nbsp;&nbsp;
                    </span>
                </td></tr>
                <tr><td>Title-Header:</td><td><input type="text" name="title" value="Titel" /></td></tr>
                <tr><td>Film-Nr.</td><td><input type="text" name="film-id" value="Film-Nr." /></td></tr>
                <tr><td>Duration-Header:</td><td><input type="text" name="duration" value="Dauer" /></td></tr>
                <tr><td>Genre-Header:</td><td><input type="text" name="info-col" value="Infos" /></td></tr>
                <tr><td>Sprache-Header:</td><td><input type="text" name="language-col" value="Sprache Untertitel" /></td></tr>
                <tr><td>Modifikation-Queer-Header:</td><td><input type="text" name="queer-col" value="⚥" /></td></tr>
                <tr><td>Modifikation-Jugend (9)-Header:</td><td><input type="text" name="child9-col" value="K (9)" /></td></tr>
                <tr><td>Modifikation-Jugend (13)-Header:</td><td><input type="text" name="child13-col" value="P (13)" /></td></tr>
                <tr><td>Modifikation-Jugend (17)-Header:</td><td><input type="text" name="child17-col" value="J (17)" /></td></tr>
                <tr><td>Daten:</td><td><textarea name="importdata" /></td></tr>
            </table>
            <br><br><PrimaryButton>Speichern</PrimaryButton>
        </form>
    </div>
</div>
<Footer :footerLinks="footerLinks" />
</template>
<script>
export default {
  props: ['film', 'filmsources', 'languages', '_token', 'headline', 'errors', 'footerLinks'],
  methods: {
  },
  computed: {
    token: function () {
      return this._token
    }
  }
}
</script>
