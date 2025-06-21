<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip';
import Button from '@/components/ui/button/Button.vue';
import MComponent from './MComponent.vue';
import Icon from './Icon.vue';

defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    href: {
        type: String,
        default: null
    },
    tooltip: {
        type: String,
        default: null,
    },
    tooltipSide: {
        type: String as () => 'top' | 'right' | 'bottom' | 'left',
        default: 'top',
    },
});
</script>

<template>
    <Tooltip v-if="tooltip">
        <TooltipTrigger>
            <MComponent
                :is="href ? Link : null"
                :href="href"
            >
                <Button
                    v-bind="$attrs"
                    :loading="loading"
                    :disabled="disabled || loading"
                    class="cursor-pointer"
                >
                    <Icon
                        v-if="loading"
                        name="Loader2"
                        class="animate-spin"
                    />
                    <slot v-else />
                </Button>
            </MComponent>
        </TooltipTrigger>
        <TooltipContent :side="tooltipSide">
            {{ tooltip }}
        </TooltipContent>
    </Tooltip>
    <MComponent
        :is="href ? Link : null"
        v-else
        :href="href"
    >
        <Button
            v-bind="$attrs"
            :loading="loading"
            :disabled="disabled || loading"
            class="cursor-pointer"
        >
            <Icon
                v-if="loading"
                name="Loader2"
                class="animate-spin"
            />
            <slot v-else />
        </Button>
    </MComponent>
</template>
