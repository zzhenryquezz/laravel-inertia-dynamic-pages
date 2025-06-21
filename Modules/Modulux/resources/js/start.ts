import { App } from "vue";
import TableActions from "./components/TableActions.vue";

export default function init(app: App) {
    app.component('TableColumnActions', TableActions)
}