
<script lang="ts" >
export interface TableColumn<TData, TValue> extends Omit<ColumnDef<TData, TValue>, 'cell'> {
    cell: {
        component: any;
        props?: Record<string, any>;
    };
}

</script>
<script setup lang="ts" generic="TData, TValue">
import { FlexRender, getCoreRowModel, useVueTable, type ColumnDef } from '@tanstack/vue-table'
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { h, resolveComponent } from 'vue';

const props = defineProps({
    items: {
        type: Array as () => any[],
        default: () => [],
    },
    columns: {
        type: Array as () => TableColumn<TData, TValue>[],
        required: true,
    },
    meta: {
        type: Object,
        default: () => ({
            total: 0,
            per_page: 20,
            current_page: 1,
            last_page: 1,
        }),
    },
    path: {
        type: String,
        default: '',
    },
    title: {
        type: String,
        default: '',
    },
    addNewHref: {
        type: String,
        default: '',
    },
})

const formatedColumns = JSON.parse(JSON.stringify(props.columns))
    .map((c: TableColumn<TData, TValue>) => {
        if (!c.cell) {
            return {
                ...c,
                cell: ({ getValue }) => {
                    const cellValue = getValue() as string;

                    return h('span', { class: 'text-sm' }, cellValue);
                },
            } as ColumnDef<TData, TValue>;
        }

        return {
            ...c,
            cell: ({ row, getValue }) => {
                const cellProps = c.cell.props || {};

                cellProps.row = row.original;
                cellProps.column = c.id;
                cellProps.value = getValue();
                
                return h(resolveComponent(c.cell.component), cellProps);
            },
        } as ColumnDef<TData, TValue>;
    })
const table = useVueTable({
    get data() { return props.items },
    get columns() { return formatedColumns },
    getCoreRowModel: getCoreRowModel(),
})
</script>

<template>
    <div class="border rounded-md">
        <Table>
            <TableHeader>
                <TableRow
                    v-for="headerGroup in table.getHeaderGroups()"
                    :key="headerGroup.id"
                >
                    <TableHead
                        v-for="header in headerGroup.headers"
                        :key="header.id"
                        :style="{
                            width: header.getSize() + 'px',
                        }"
                    >
                        <FlexRender
                            v-if="!header.isPlaceholder"
                            :render="header.column.columnDef.header"
                            :props="header.getContext()"
                        />
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <template v-if="table.getRowModel().rows?.length">
                    <TableRow
                        v-for="row in table.getRowModel().rows"
                        :key="row.id"
                        :data-state="row.getIsSelected() ? 'selected' : undefined"
                    >
                        <TableCell
                            v-for="cell in row.getVisibleCells()"
                            :key="cell.id"
                        >
                            <FlexRender
                                :render="cell.column.columnDef.cell"
                                :props="cell.getContext()"
                            />
                        </TableCell>
                    </TableRow>
                </template>
                <template v-else>
                    <TableRow>
                        <TableCell
                            :colspan="columns.length"
                            class="h-24 text-center"
                        >
                            No data available
                        </TableCell>
                    </TableRow>
                </template>
            </TableBody>
        </Table>
    </div>
</template>

