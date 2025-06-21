<script setup lang="ts">
import Table from '../components/Table.vue';
import AppLayout from '../layouts/AppLayout/AppLayout.vue';
import { BreadcrumbItem } from '../layouts/AppLayout/AppLayoutHeader.vue';
import AppLayoutPage from '../layouts/AppLayout/AppLayoutPage.vue';

interface Props {
    item: any[];
    breadcrumbs?: BreadcrumbItem[];
}

defineProps<Props>();

</script>

<template>
    <AppLayout :breadcrumbs>
        <AppLayoutPage>
            {{  item }}
        </AppLayoutPage>
    </AppLayout>
</template>