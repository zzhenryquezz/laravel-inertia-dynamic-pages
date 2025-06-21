<script lang="ts">
export interface BreadcrumbItem {
    label: string;
    href?: string;
    icon?: string;
}
</script>
<script setup lang="ts">
import { SidebarTrigger } from '@/components/ui/sidebar';
import { Breadcrumb, BreadcrumbItem as BreadcrumbItemComponent, BreadcrumbLink, BreadcrumbList, BreadcrumbPage, BreadcrumbSeparator } from '@/components/ui/breadcrumb';
import { Link } from '@inertiajs/vue3';

defineProps({
    breadcrumbs: {
        type: Array as () => BreadcrumbItem[],
        default: () => [],
    },
});
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                 <Breadcrumb>
                    <BreadcrumbList>
                        <template
                            v-for="(item, index) in breadcrumbs"
                            :key="index"
                        >
                            <BreadcrumbItemComponent>
                                <template v-if="index === breadcrumbs.length - 1">
                                    <BreadcrumbPage>{{ item.label }}</BreadcrumbPage>
                                </template>
                                <template v-else>
                                    <BreadcrumbLink as-child>
                                        <Link :href="item.href ?? '#'">
                                            {{ item.label }}
                                        </Link>
                                    </BreadcrumbLink>
                                </template>
                            </BreadcrumbItemComponent>
                            <BreadcrumbSeparator v-if="index !== breadcrumbs.length - 1" />
                        </template>
                    </BreadcrumbList>
                </Breadcrumb>
            </template>
        </div>
    </header>
</template>
