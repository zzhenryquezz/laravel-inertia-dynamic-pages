<script lang="ts">
export interface TableActionItem {
    type: 'icon' | 'dropdown';
    icon?: string;
    label?: string;
    href?: string;
    request?: string;
    confirm?: boolean;
}
</script>
<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import DropdownMenuItem from '@/components/ui/dropdown-menu/DropdownMenuItem.vue';
import Tooltip from '@/components/ui/tooltip/Tooltip.vue';
import TooltipContent from '@/components/ui/tooltip/TooltipContent.vue';
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import TooltipTrigger from '@/components/ui/tooltip/TooltipTrigger.vue';
import { Link, router } from '@inertiajs/vue3';
import get from 'lodash/get';
import Icon from './Icon.vue';

interface Props extends TableActionItem {
    row: any
}

const props = defineProps<Props>()

// replace all {row.} with get(row, key) for nested properties
function replaces(value: string) {
    return value.replace(/{row\.(.*?)}/g, (_, key) => {
        return get(props.row, key) || '';
    });
}


function onItemRequest() {
    if (!props.request) {
        return;
    }

    const request = replaces(props.request);

    const args = request.split(':');
    const method = args.length > 1 ? args[0] : 'get';
    const url = args.length > 1 ? args[1] : args[0];

    if (method === 'get') {
        return router.get(url);
    }
    if (method === 'post') {
        return router.post(url);
    }
    if (method === 'put') {
        return router.put(url);
    }
    if (method === 'delete') {
        return router.delete(url);
    }
    if (method === 'patch') {
        return router.patch(url);
    }
}

function onItemClick() {
    if (props.href) {
        return 
    }
    
    if (props.confirm) {
        if (!confirm('Are you sure you want to perform this action?')) {
            return;
        }
    }

    if (props.request) {
        return onItemRequest();
    }
}
</script>

<template>
    <TooltipProvider v-if="type === 'icon'">
        <Tooltip>
            <TooltipTrigger>
                <Button
                    variant="ghost"
                    class="size-8 cursor-pointer"
                    :as-child="!!href"
                    @click="onItemClick"
                >
                    <Link
                        v-if="href"
                        :href="replaces(href)"
                    >
                        <Icon
                            v-if="icon"
                            :name="icon"
                            class="size-4"
                        />
                    </Link>
                    <Icon
                        v-else-if="icon"
                        :name="icon"
                        class="size-4 block"
                        
                    />
                </Button>
            </TooltipTrigger>
            <TooltipContent side="left">
                {{ label || icon }}
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>

    <DropdownMenuItem
        v-else-if="type === 'dropdown'"
        class="cursor-pointer"
        :href="href ? replaces(href) : undefined"
        @click="onItemClick"
    >
        <Icon
            v-if="icon"
            :name="icon"
            class="size-4"
        />
        <span>
            {{ label }}
        </span>
    </DropdownMenuItem>
</template>
