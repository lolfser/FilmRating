<style scoped>
    a {color: white; padding: 10px; background-color: darkgoldenrod}
    a:hover {color: white; background-color: chocolate}
</style>
<template>
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center mt-16 px-6 sm:items-center sm:justify-between">
                <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-left">
                    <div class="flex items-center gap-4">
                        Willkommen
                    </div>
                    <div class="flex items-center gap-4">
                        <a :href="route('login')">Login</a>
                        <a :href="route('register')">Registrieren</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
