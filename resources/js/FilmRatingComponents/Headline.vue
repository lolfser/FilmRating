<template>
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <h1 class="mt-4 text-2xl font-medium text-gray-900">
            {{ headline }}
        </h1>
    </div>
</template>
<script>
export default {
  props: ['headline'],
}
</script>
