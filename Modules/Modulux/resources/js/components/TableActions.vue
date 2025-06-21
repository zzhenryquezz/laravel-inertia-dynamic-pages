<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import Icon from './Icon.vue';
import DropdownMenuContent from '@/components/ui/dropdown-menu/DropdownMenuContent.vue';
import { computed, PropType } from 'vue';
import type { TableActionItem as TableActionItemDefintion } from './TableActionItem.vue';
import TableActionItem from './TableActionItem.vue';

const props = defineProps({
    items: {
        type: Array as PropType<TableActionItemDefintion[]>,
        default: () => [],
    },
    row: {
        type: Object as PropType<Record<string, any>>,
        default: () => ({}),
    },
})

const dropdownItems = computed(() => {
    return props.items.filter(i => i.type === 'dropdown');
});
</script>

<template>
    <div class="relative text-right">
        <TableActionItem
            v-for="(item, index) in items.filter(i => i.type !== 'dropdown')"
            :key="index"
            :row="row"
            v-bind="item"
            type="icon"
        />       

        <DropdownMenu v-if="dropdownItems.length">
            <DropdownMenuTrigger as-child>
                <Button
                    variant="ghost"
                    class="size-8 p-0"
                >
                    <Icon
                        name="MoreHorizontal"
                        class="size-4"
                    />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                <TableActionItem
                    v-for="(item, index) in dropdownItems"
                    :key="index"
                    :row="row"
                    v-bind="item"
                    type="dropdown"
                />
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>
