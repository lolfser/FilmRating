<script setup>
import NavLink from '@/Components/NavLink.vue';
</script>

<template>
    <div class="grid gap-6 lg:gap-8 p-6">
        <div>
            <span v-for="link in headerLinks" >
                <NavLink :href="link.href" class="ml-3" :active="link.active"> <!-- "route().current('rating.index') -->
                    {{ link.label }}
                </NavLink>
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
