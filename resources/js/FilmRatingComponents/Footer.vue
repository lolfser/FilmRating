<script setup>
</script>

<template>
    <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 gap-2 p-6">
        <div>
            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                <span v-for="footerLink in footerLinks">
                    <a :href="footerLink.href" class="inline-flex items-center font-semibold text-indigo-700">
                        {{ footerLink.label }}
                    </a>
                    &nbsp;&nbsp;&nbsp;
                </span>
            </p>
        </div>
    </div>
</template>
<script>
export default {
  props: [
    'footerLinks',
  ]
}

</script>
