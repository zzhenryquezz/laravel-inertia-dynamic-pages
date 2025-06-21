<script lang="ts">
interface ItemWithoutChildren {
    title: string;
    icon: string;
    href: string;
}
interface ItemWithChildren {
    title: string;
    icon: string;
    children: Omit<ItemWithoutChildren, 'icon'>[];
}

export type SidebarItem = ItemWithChildren | ItemWithoutChildren;
</script>
<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import {
  Sidebar,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuItem,
  SidebarMenuButton,
  SidebarContent,
  SidebarGroup,
  SidebarGroupLabel,
  SidebarFooter,
  SidebarMenuSub,
  SidebarMenuSubItem
} from '@/components/ui/sidebar';
import {
  Collapsible,
  CollapsibleTrigger,
  CollapsibleContent
} from '@/components/ui/collapsible';
import AppLayoutNavUser from './AppLayoutNavUser.vue';
import { useBreakpoints, useLocalStorage } from '@vueuse/core';
import Logo from '../../components/Logo.vue';
import Icon from '../../components/Icon.vue';

const breakpoints = useBreakpoints({ lg: 1024 });
const isLg = breakpoints.greater('lg');

const page = usePage<any>();

const open = useLocalStorage('layout:sidebar', true);
const links: SidebarItem[] = [];

page.props.modulux?.sidebar?.links.forEach((item: any) => {
    links.push(item);
});

</script>
<template>
    <Sidebar
            collapsible="icon"
            variant="inset"
        >
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            size="lg"
                            as-child
                        >
                        <Link :href="route('dashboard')">
                            <Logo />
                        </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <SidebarGroup class="px-2 py-0">
                    <SidebarGroupLabel>platform</SidebarGroupLabel>
                    <SidebarMenu>
                        <template
                            v-for="item in links"
                            :key="item.title"
                        >
                            <Collapsible
                                v-if="'children' in item"
                                default-open
                                class="group/collapsible"
                            >
                                <SidebarMenuItem>
                                    <template v-if="open || !isLg">
                                        <CollapsibleTrigger as-child>
                                            <SidebarMenuButton :tooltip="item.title">
                                                <Icon :name="item.icon" />
                                                <span>{{ item.title }}</span>
                                            </SidebarMenuButton>
                                        </CollapsibleTrigger>
                                        <CollapsibleContent>
                                            <SidebarMenuSub>
                                                <SidebarMenuSubItem>
                                                    <SidebarMenuButton
                                                        v-for="child in item.children"
                                                        :key="child.title"
                                                        as-child
                                                        :is-active="child.href === page.url"
                                                        :tooltip="child.title"
                                                    >
                                                        <Link :href="child.href">
                                                            {{ child.title }}
                                                        </Link>
                                                    </SidebarMenuButton>
                                                </SidebarMenuSubItem>
                                            </SidebarMenuSub>
                                        </CollapsibleContent>
                                    </template>
                                    <template v-if="!open && isLg">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <SidebarMenuButton>
                                                    <Icon :name="item.icon" />
                                                    <span>{{ item.title }}</span>
                                                </SidebarMenuButton>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent 
                                                side="right" 
                                                align="start"
                                            >
                                                <DropdownMenuItem
                                                    v-for="child in item.children"
                                                    :key="child.title"
                                                    as-child
                                                >
                                                    <Link :href="child.href">
                                                        {{ child.title }}
                                                    </Link>
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </template>
                                </SidebarMenuItem>
                            </Collapsible>

                            <SidebarMenuItem v-else>
                                <SidebarMenuButton
                                    as-child
                                    :is-active="item.href === page.url"
                                    :tooltip="item.title"
                                >
                                    <Link :href="item.href">
                                        <Icon :name="item.icon" />
                                        <span>{{ item.title }}</span>
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </template>
                    </SidebarMenu>
                </SidebarGroup>
            </SidebarContent>

            <SidebarFooter>
                <AppLayoutNavUser />
            </SidebarFooter>
        </Sidebar>
</template>