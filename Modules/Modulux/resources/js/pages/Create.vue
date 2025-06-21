<script setup lang="ts">
import Form from '../components/Form.vue';
import AppLayout from '../layouts/AppLayout/AppLayout.vue';
import { BreadcrumbItem } from '../layouts/AppLayout/AppLayoutHeader.vue';
import AppLayoutPage from '../layouts/AppLayout/AppLayoutPage.vue';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
    alerts?: any[];
    form: any
}

defineProps<Props>();

</script>

<template>
    <AppLayout :breadcrumbs :alerts>
        <AppLayoutPage>
            <Form v-bind="form" />
        </AppLayoutPage>
    </AppLayout>
</template>