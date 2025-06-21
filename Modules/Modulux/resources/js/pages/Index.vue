<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Table from '../components/Table.vue';
import AppLayout from '../layouts/AppLayout/AppLayout.vue';
import { BreadcrumbItem } from '../layouts/AppLayout/AppLayoutHeader.vue';
import AppLayoutPage from '../layouts/AppLayout/AppLayoutPage.vue';
import { Link } from '@inertiajs/vue3';

interface TableProp {
    columns: any[];
}

interface Props {
    table: TableProp;
    breadcrumbs?: BreadcrumbItem[];
    addNewHref?: string;
}

defineProps<Props>();

</script>

<template>
    <AppLayout :breadcrumbs>
        <AppLayoutPage>
            <div class="flex items-center justify-between ">

                <div class="flex-1" />

                <Button
                    v-if="addNewHref"
                    class="mb-4"
                    variant="default"
                    as-child
                >
                    <Link :href="addNewHref">
                        add new     
                    </Link>
                </Button>

            </div>
            <Table v-bind="table" />
        </AppLayoutPage>
    </AppLayout>
</template>