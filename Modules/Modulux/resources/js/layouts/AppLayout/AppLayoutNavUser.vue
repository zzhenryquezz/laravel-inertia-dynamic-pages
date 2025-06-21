<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import DropdownMenuGroup from '@/components/ui/dropdown-menu/DropdownMenuGroup.vue';
import DropdownMenuItem from '@/components/ui/dropdown-menu/DropdownMenuItem.vue';
import DropdownMenuLabel from '@/components/ui/dropdown-menu/DropdownMenuLabel.vue';
import DropdownMenuSeparator from '@/components/ui/dropdown-menu/DropdownMenuSeparator.vue';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '@/components/ui/sidebar';
import { type User } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ChevronsUpDown, LogOut, Settings } from 'lucide-vue-next';

const page = usePage<any>();
const user = page.props.auth.user as User;
const { isMobile, state } = useSidebar();

const handleLogout = () => {
    router.flushAll();
};

</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <SidebarMenuButton
                        size="lg"
                        class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                    >
                        <UserInfo :user="user" />
                        <ChevronsUpDown class="ml-auto size-4" />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>
                <DropdownMenuContent
                    class="w-(--reka-dropdown-menu-trigger-width) min-w-56 rounded-lg"
                    :side="isMobile ? 'bottom' : state === 'collapsed' ? 'left' : 'bottom'"
                    align="end"
                    :side-offset="4"
                >
                    <DropdownMenuLabel class="p-0 font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <UserInfo
                                :user="user"
                                :show-email="true"
                            />
                        </div>
                    </DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuGroup>
                        <DropdownMenuItem :as-child="true">
                            <Link
                                class="block w-full"
                                :href="route('profile.edit')"
                                prefetch
                                as="button"
                            >
                                <Settings class="mr-2 h-4 w-4" />
                                Settings
                            </Link>
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem :as-child="true">
                        <Link
                            class="block w-full"
                            method="post"
                            :href="route('logout')"
                            as="button"
                            @click="handleLogout"
                        >
                            <LogOut class="mr-2 h-4 w-4" />
                            Log out
                        </Link>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
