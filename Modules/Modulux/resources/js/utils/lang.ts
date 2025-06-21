import { reactive } from "vue";
import en from '../../../lang/en.json'

export const $translator = reactive({
    locale: 'en',
    loaded: false,
    messages: {
        en
    } as Record<string, Record<string, string>>
})

export function $t(key: string, replacements: Record<string, string> | string[] = {}): string {
    const messages = $translator.messages[$translator.locale] || {};
    
    let translation = messages[key];

    if (!messages[key]) {
        console.warn(`Translation not found for key: ${key}`);
        return key; // Return the key if translation is not found
    }

    for (const [placeholder, value] of Object.entries(replacements)) {
        translation = translation.replace(`:${placeholder}`,  String(value).toLowerCase());
    }

    return translation;
}