<script setup lang="ts">
import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form'
defineProps({
    name: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    hint: {
        type: String,
        default: '',
    },
})
</script>
<template>
    <FormField
        v-slot="{ componentField }"
        :name
    >
        <FormItem>
            <FormLabel>{{ label }}</FormLabel>
            <FormControl>
                <UiTextarea
                    v-bind="componentField"
                />
            </FormControl>
            <FormDescription v-if="hint">
                {{ hint }}
            </FormDescription>
            <FormMessage />
        </FormItem>
    </FormField>
</template>
