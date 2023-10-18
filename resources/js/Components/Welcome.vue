<template>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h1 class="mt-8 text-2xl font-medium text-gray-900">
                Willkommen!
            </h1>
            <p class="mt-6 text-gray-500 leading-relaxed">
                Vielen Dank, dass du uns bei der Sichtung von Filmen bewerten möchtest. Für die Sichtung hast du verschiedene Möglichkeiten.
            </p>
        </div>

        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
            <div>
                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                    <a href="/films" class="inline-flex items-center font-semibold text-indigo-700">
                        Liste aller Filme
                    </a>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="/films/0/cu" class="inline-flex items-center font-semibold text-indigo-700">
                        Film erstellen
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>
