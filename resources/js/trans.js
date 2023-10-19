export const translate = (key) => {
    let translation, translationNotFound = true

    try {
        translation = key.split('.').reduce((t, i) => t[i] || null, window._translations[window._locale].php)

        if (translation) {
            translationNotFound = false
        }
    } catch (e) {
        translation = key
    }

    if (translationNotFound) {
        translation = window._translations[window._locale]['json'][key]
            ? window._translations[window._locale]['json'][key]
            : key
    }

    return translation
}

