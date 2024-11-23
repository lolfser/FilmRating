<script setup>
</script>

<template>
    <div class="grid gap-6 lg:gap-8 p-6">
        <div>
            <span v-for="link in headerLinks" >
                <a :href="link.href" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out ml-3">
                    {{ link.label }}
                </a>
            </span>
        </div>
    </div>
</template>
<script>
export default {
  props: [
    'headerLinks',
  ]
}

</script>
