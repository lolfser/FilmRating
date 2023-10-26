<script setup>
// https://primevue.org/multiselect/
import MultiSelect from 'primevue/multiselect';
</script>
<template>
  <div>
    <MultiSelect
      v-model="vModel"
      filter
      :options="options"
      :optionLabel="optionLabel"
      :optionValue="optionValue"
      :placeholder="placeholder"
      :autoFilterFocus="autoFilterFocus"
      :name="name"
      @change="$emit('update:modelValue', vModel)"
    />
  </div>
</template><script>
export default {
  props: ['options', 'optionLabel', 'optionValue', 'placeholder', 'name', 'autoFilterFocus', 'modelValue'],
    emits: ['update:modelValue'],
    mounted() {
      this.vModel = this.modelValue
    },
    data() {
    return {
        vModel: null
    };
  },
  methods: {},
};
</script>
