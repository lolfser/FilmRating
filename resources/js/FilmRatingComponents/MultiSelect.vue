<script setup>
// https://primevue.org/multiselect/
import MultiSelect from 'primevue/multiselect';
</script>
<template>
  <div>
    <input type="hidden" :name="name" :value="modelValue">
    <MultiSelect
      v-model="vModel"
      filter
      :options="options"
      :optionLabel="optionLabel"
      :optionValue="optionValue"
      :placeholder="placeholder"
      :autoFilterFocus="autoFilterFocus"
      :inputId="name"
      :name="name"
      :maxSelectedLabels="maxSelectedLabels"
      :selection-limit="selectionLimit"
    />
  </div>
</template>
<style>
.p-highlight {
    background-color: #3fa69e;
}
</style>
<script>
export default {
    props: ['options', 'optionLabel', 'optionValue', 'placeholder', 'name', 'autoFilterFocus', 'modelValue', 'maxSelectedLabels', 'selectionLimit'],
    emits: ['update:modelValue'],
    computed: {
        vModel: {
            get() {
                return this.modelValue;
            },
            set(vModel)  {
                this.$emit('update:modelValue', vModel);
            }
        }
    },
    methods: {}
};
</script>
