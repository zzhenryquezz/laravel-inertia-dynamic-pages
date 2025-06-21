import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { resolvePage } from './resolve-page';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const booters = import.meta.glob('../../Modules/**/resources/js/start.ts', {
    eager: true
})

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePage(name),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)

        Object.values(booters).forEach((booter: any) => {
                if (booter.default) {
                    booter.default(app);
                }
            }
        )

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
