<script setup>
import NavLink from '@/Components/NavLink.vue';
</script>

<template>
    <div class="grid gap-6 lg:gap-8 p-6">
        <div>
            <span v-for="link in headerLinks" >
                <NavLink :href="link.href" class="ml-3" :active="link.active" v-if="link.label != 'Statistiken'">
                    <!-- "route().current('rating.index') -->
                    {{ link.label }}
                </NavLink>
                <a :href="link.href" v-else class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out ml-3">
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
