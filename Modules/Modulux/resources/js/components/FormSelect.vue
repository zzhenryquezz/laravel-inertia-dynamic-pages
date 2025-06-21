<script setup lang="ts">
const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: 'text',
    },
    label: {
        type: String,
        required: true,
    },
    hint: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: null,
    },
    options: {
        type: Array,
        default: () => [],
    },
    labelKey: {
        type: String,
        default: 'label',
    },
    valueKey: {
        type: String,
        default: 'value',
    },
})

function findLabel(option: any) {
    return option[props.labelKey] || option[props.valueKey] || option;
}

function findValue(option: any) {
    return option[props.valueKey] || option
}
</script>
<template>
    <ui-form-field
        v-slot="{ componentField }"
        :name
    >
        <ui-form-item>
            <ui-form-label>{{ label }}</ui-form-label>
            <ui-form-control>
                <ui-select v-bind="componentField">
                    <ui-select-trigger class="w-full">
                        <ui-select-value :placeholder="placeholder" />
                    </ui-select-trigger>
                    <ui-select-content>
                        <ui-select-group>
                            <ui-select-label v-if="!options.length">
                                {{ $t('no_results') }}
                            </ui-select-label>
                            <ui-select-item
                                v-for="option in options"
                                :key="findValue(option)"
                                :value="findValue(option)"
                            >
                                {{ findLabel(option) }}
                            </ui-select-item>
                        </ui-select-group>
                    </ui-select-content>
                </ui-select>
            </ui-form-control>
            <ui-form-description v-if="hint">
                {{ hint }}
            </ui-form-description>
            <ui-form-message />
        </ui-form-item>
    </ui-form-field>
</template>
