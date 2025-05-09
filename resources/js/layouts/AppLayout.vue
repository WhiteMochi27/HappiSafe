// resources/js/Layouts/AppLayout.vue
<!-- <script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    // Navbar,
    // NavbarBrand,
    // NavbarContent,
    // NavbarItem,
    NavigationMenu,
    NavigationMenuTrigger,
    NavigationMenuItem,
    Button,
    // Dropdown,
    DropdownMenu,
    DropdownMenuItem,
    Avatar
} from '@/components/ui';
import { Bell, User, Settings, LogOut, ChevronDown } from 'lucide-vue-next';

const { auth } = usePage().props as any;
const user = auth.user;
const showMobileMenu = ref(false);

const notifications = ref([
    { id: 1, message: 'Your motor insurance is expiring soon!', read: false },
    { id: 2, message: 'Your 1,000 HappiCoins will expire in 7 days', read: false },
]);

onMounted(() => {
    // Fetch user notifications
});
</script> -->

<!-- <template>
    <div class="min-h-screen bg-neutral-50 dark:bg-neutral-900">
        <Navbar class="border-b">
            <NavbarBrand>
                <Link href="/" class="flex items-center space-x-2">
                <img src="/logo.svg" alt="HappiInsurance" class="h-8 w-auto" />
                <span class="font-semibold text-xl text-primary">HappiInsurance</span>
                </Link>
            </NavbarBrand>

            <NavbarContent class="hidden sm:flex gap-4">
                <NavbarItem>
                    <Link href="/insurance" class="px-2 py-1 hover:text-primary transition-colors">
                    Insurance
                    </Link>
                </NavbarItem>
                <NavbarItem>
                    <Link href="/membership" class="px-2 py-1 hover:text-primary transition-colors">
                    Membership
                    </Link>
                </NavbarItem>
                <NavbarItem>
                    <Link href="/contact" class="px-2 py-1 hover:text-primary transition-colors">
                    Contact
                    </Link>
                </NavbarItem>
            </NavbarContent>

            <NavbarContent justify="end">
                <template v-if="user">

                    <Dropdown>
                        <DropdownTrigger>
                            <Button variant="ghost" size="icon" class="relative">
                                <Bell class="h-5 w-5" />
                                <span v-if="notifications.filter(n => !n.read).length > 0"
                                    class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500">
                                </span>
                            </Button>
                        </DropdownTrigger>
                        <DropdownMenu>
                            <DropdownMenuItem v-for="notification in notifications" :key="notification.id">
                                {{ notification.message }}
                            </DropdownMenuItem>
                            <DropdownMenuItem>
                                <Link href="/notifications" class="w-full text-sm">
                                View all notifications
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenu>
                    </Dropdown>


                    <Dropdown>
                        <DropdownTrigger>
                            <div class="flex items-center space-x-1 cursor-pointer">
                                <Avatar>
                                    <div
                                        class="flex items-center justify-center h-full w-full bg-primary/10 text-primary">
                                        {{ user.name[0] }}
                                    </div>
                                </Avatar>
                                <ChevronDown class="h-4 w-4" />
                            </div>
                        </DropdownTrigger>
                        <DropdownMenu>
                            <DropdownMenuItem>
                                <div class="px-2 py-1 flex flex-col">
                                    <span class="font-medium">{{ user.name }}</span>
                                    <span class="text-sm text-neutral-500">{{ user.email }}</span>
                                </div>
                            </DropdownMenuItem>
                            <DropdownMenuItem>
                                <div class="flex items-center space-x-2 px-2 py-1">
                                    <div class="flex items-center space-x-1">
                                        <span class="text-yellow-500">🪙</span>
                                        <span>{{ user.happi_coins || 0 }} HappiCoins</span>
                                    </div>
                                </div>
                            </DropdownMenuItem>
                            <DropdownMenuItem>
                                <div class="flex items-center space-x-2 px-2 py-1">
                                    <div class="flex items-center space-x-1">
                                        <span class="text-violet-500">👑</span>
                                        <span class="capitalize">{{ user.membership_tier }} Member</span>
                                    </div>
                                </div>
                            </DropdownMenuItem>
                            <DropdownMenuItem>
                                <Link href="/profile" class="flex items-center space-x-2 px-2 py-1 w-full">
                                <User class="h-4 w-4" />
                                <span>Profile</span>
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem>
                                <Link href="/settings" class="flex items-center space-x-2 px-2 py-1 w-full">
                                <Settings class="h-4 w-4" />
                                <span>Settings</span>
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem>
                                <Link href="/logout" method="post" as="button"
                                    class="flex items-center space-x-2 px-2 py-1 w-full">
                                <LogOut class="h-4 w-4" />
                                <span>Logout</span>
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenu>
                    </Dropdown>
                </template>
                <template v-else>
                    <NavbarItem>
                        <Link href="/login" class="px-4 py-2">
                        Login
                        </Link>
                    </NavbarItem>
                    <NavbarItem>
                        <Link href="/register"
                            class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                        Register
                        </Link>
                    </NavbarItem>
                </template>
            </NavbarContent>

            <NavigationMenuTrigger @click="showMobileMenu = !showMobileMenu" class="sm:hidden" />

            <NavigationMenu :open="showMobileMenu">
                <NavigationMenuItem>
                    <Link href="/insurance" class="w-full block py-2">
                    Insurance
                    </Link>
                </NavigationMenuItem>
                <NavigationMenuItem>
                    <Link href="/membership" class="w-full block py-2">
                    Membership
                    </Link>
                </NavigationMenuItem>
                <NavigationMenuItem>
                    <Link href="/contact" class="w-full block py-2">
                    Contact
                    </Link>
                </NavigationMenuItem>
                <NavigationMenuItem v-if="!user">
                    <Link href="/login" class="w-full block py-2">
                    Login
                    </Link>
                </NavigationMenuItem>
                <NavigationMenuItem v-if="!user">
                    <Link href="/register" class="w-full block py-2">
                    Register
                    </Link>
                </NavigationMenuItem>
            </NavigationMenu>
        </Navbar>


        <main>
            <slot />
        </main>


        <footer class="bg-white dark:bg-neutral-800 py-12 mt-12 border-t">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-lg font-medium mb-4">HappiInsurance</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">
                            Making insurance simple, affordable, and rewarding for everyone.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-neutral-400 hover:text-primary">
                                <span class="sr-only">Facebook</span>

                            </a>
                            <a href="#" class="text-neutral-400 hover:text-primary">
                                <span class="sr-only">Twitter</span>

                            </a>
                            <a href="#" class="text-neutral-400 hover:text-primary">
                                <span class="sr-only">Instagram</span>

                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium mb-4">Insurance</h3>
                        <ul class="space-y-2">
                            <li>
                                <Link href="/insurance/auto"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                Auto Insurance
                                </Link>
                            </li>
                            <li>
                                <Link href="/insurance/health"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                Health Insurance
                                </Link>
                            </li>
                            <li>
                                <Link href="/insurance/home"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                Home Insurance
                                </Link>
                            </li>
                            <li>
                                <Link href="/insurance/travel"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                Travel Insurance
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium mb-4">Company</h3>
                        <ul class="space-y-2">
                            <li>
                                <Link href="/about"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                About Us
                                </Link>
                            </li>
                            <li>
                                <Link href="/careers"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                Careers
                                </Link>
                            </li>
                            <li>
                                <Link href="/blog"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                Blog
                                </Link>
                            </li>
                            <li>
                                <Link href="/contact"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                Contact
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium mb-4">Support</h3>
                        <ul class="space-y-2">
                            <li>
                                <Link href="/faqs"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                FAQs
                                </Link>
                            </li>
                            <li>
                                <Link href="/terms"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                Terms & Conditions
                                </Link>
                            </li>
                            <li>
                                <Link href="/privacy"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                Privacy Policy
                                </Link>
                            </li>
                            <li>
                                <Link href="/cookie-policy"
                                    class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-primary">
                                Cookie Policy
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-neutral-200 dark:border-neutral-700 mt-8 pt-8 text-center">
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                        &copy; {{ new Date().getFullYear() }} HappiInsurance. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template> -->

<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
