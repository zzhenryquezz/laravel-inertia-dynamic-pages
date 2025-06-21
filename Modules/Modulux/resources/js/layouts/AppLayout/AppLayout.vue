<script setup lang="ts">
import {
  SidebarProvider,
  SidebarInset,
} from '@/components/ui/sidebar';
import AppLayoutHeader,  { type BreadcrumbItem } from './AppLayoutHeader.vue';
import { useLocalStorage } from '@vueuse/core';
import AppLayoutSidebar from './AppLayoutSidebar.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import Alert from '@/components/ui/alert/Alert.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';

interface Alert {
    type: any;
    title: string;
    description?: string;
}

defineProps({
    breadcrumbs: {
        type: Array as () => BreadcrumbItem[],
        default: () => [],
    },
    alerts: {
        type: Array as () => Alert[],
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

                    <div class="p-4 pb-0" v-if="alerts.length">
                        <Alert v-for="(a, key) in alerts" :key="key" :variant="a.type == 'error' ? 'destructive' : a.type" class="mb-4">
                            <AlertTitle> 
                                {{ a.title }}
                            </AlertTitle>
                            <AlertDescription v-if="a.description">
                                {{ a.description }}
                            </AlertDescription>
                        </Alert>
                    </div>

                    <slot />
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
