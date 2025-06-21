import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { DefineComponent } from "vue";
import upperFirst from "lodash/upperFirst";

const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue');
const modulePages = import.meta.glob<DefineComponent>(`../../Modules/**/resources/js/pages/*.vue`);
const allPages = { ...pages, ...modulePages };

export function resolvePage(name: string) {
    let pageName = `./pages/${name}.vue`;

    if (name.includes('::')) {
        const [moduleName, page] = name.split('::'); 
        pageName = `../../Modules/${upperFirst(moduleName)}/resources/js/pages/${page}.vue`;
    }


    return resolvePageComponent<DefineComponent>(pageName, allPages);
}
