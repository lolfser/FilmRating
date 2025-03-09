<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import DropdownLink from '@/Components/DropdownLink.vue';

defineProps({
    title: String,
});

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />
        <header style="background-color: #f7f7f7; padding: 10px;">
            <div style="display: flex;
                        justify-content: center;
                        gap: 10px 30px;
                        align-items: center;
                        color: unset;
                        font-size: 14px;
            ">
                <a href="/">
                    <img src="/images/logo.jpg" alt="Logo" style="height: 60px;">
                </a>

                <span v-for="link in $page.props.headerLinks">
                    <a :href="link.href" class="active" v-if="link.active">{{ link.label }}</a>
                    <a :href="link.href" v-else>{{ link.label }}</a>
                </span>

                <span v-if="$page.props.auth.user.name"></span>
                <span v-if="$page.props.auth.user.name"></span>
                <span v-if="$page.props.auth.user.name" style="position: relative;bottom: 2px;">
                    <a href="/user/profile/">
                        {{ $page.props.auth.user.name }}
                    </a>
                    <br>
                    <form method="post" @submit.prevent="logout">
                        <DropdownLink as="button">
                            Logout
                        </DropdownLink>
                    </form>
                </span>
            </div>
        </header>
        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </div>
</template>
<style>
    header {
        margin: 0 !important;
        padding: 10px !important;
        font-family: Arial, sans-serif !important;
        color: rgb(55, 65, 81) !important;
    }
    header a {
        text-decoration: none !important;
    }
    header a.active {
        font-weight: bold !important;
        text-decoration: underline !important;
    }
    header button:hover,
    header a:hover {
        text-decoration: underline !important;
        color: blue !important;
    }
    header button {
        margin: 0 !important;
        padding: 0 !important;
        font-size: 9px !important;
        line-height: 6px !important;
    }
</style>
