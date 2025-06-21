<script setup lang="ts">
import {
  SidebarProvider,
  SidebarInset,
} from '@/components/ui/sidebar';
import AppLayoutHeader,  { type BreadcrumbItem } from './AppLayoutHeader.vue';
import { useLocalStorage } from '@vueuse/core';
import AppLayoutSidebar from './AppLayoutSidebar.vue';

defineProps({
    breadcrumbs: {
        type: Array as () => BreadcrumbItem[],
        default: () => [],
    },
})

const open = useLocalStorage('layout:sidebar-open', true);
</script>

<template>
    <SidebarProvider
        v-model:open="open"
    >
        <AppLayoutSidebar />

        <SidebarInset variant="sidebar">
            <AppLayoutHeader :breadcrumbs="breadcrumbs" />
            <div 
                class="h-[calc(100dvh-5rem)] overflow-hidden"
            >
                <div
                    class="
                        h-full
                        overflow-auto
                        lg:max-w-[calc(100dvw-8px-var(--sidebar-width))]
                        group-has-data-[collapsible=icon]/sidebar-wrapper:max-w-[calc(100dvw-var(--sidebar-width-icon))]
                    "
                >
                    <slot />
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
