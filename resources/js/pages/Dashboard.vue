<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
    CardFooter,
    Button,
    Avatar,
    AvatarFallback,
    AvatarImage,
    Badge
} from '@/components/ui';
import {
    Car,
    Users,
    Shield,
    Bell,
    CreditCard,
    Clock,
    ChevronRight,
    Calendar,
    BarChart4,
    Settings
} from 'lucide-vue-next';

// Define breadcrumbs for navigation context
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: '/dashboard',
    },
];

const { auth } = usePage().props as any;
const user = auth?.user;

// Demo data - in a real app these would come from your backend
const insuranceData = ref({
    activePolicies: 2,
    expiringPolicies: 1
});

const vehicleData = ref({
    totalVehicles: 2,
    insuredVehicles: 1
});

const familyData = ref({
    memberCount: 3,
    invitationsPending: 1
});

const notificationData = ref({
    totalCount: 5,
    unreadCount: 3
});

const recentPolicies = ref([
    {
        id: 1,
        name: 'Comprehensive Auto Insurance',
        type: 'auto',
        status: 'active',
        expiresAt: '2024-12-15'
    },
    {
        id: 2,
        name: 'Family Health Plan',
        type: 'health',
        status: 'active',
        expiresAt: '2024-10-30'
    }
]);

const upcomingEvents = ref([
    {
        id: 1,
        title: 'Auto Insurance Renewal',
        date: '2024-12-15',
        type: 'renewal'
    },
    {
        id: 2,
        title: 'Membership Expires',
        date: '2024-11-01',
        type: 'expiration'
    }
]);

// Function to format dates
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString();
};

// Get initials for avatar
const getInitials = (name: string): string => {
    if (!name) return '';

    const nameParts = name.split(' ');
    if (nameParts.length === 1) {
        return nameParts[0].charAt(0).toUpperCase();
    }

    return (nameParts[0].charAt(0) + nameParts[nameParts.length - 1].charAt(0)).toUpperCase();
};
</script>

<template>

    <Head title="Home" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-4 md:p-6 space-y-6">
            <!-- Welcome Card -->
            <Card class="bg-yellow-200 from-primary/90 to-primary ">
                <CardContent class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex items-center space-x-4 mb-4 md:mb-0">
                            <Avatar class="h-16 w-16 border-2 border-white">
                                <AvatarImage v-if="user?.avatar" :src="user.avatar" :alt="user?.name" />
                                <AvatarFallback class="bg-white text-primary text-xl font-bold">
                                    {{ user?.name ? getInitials(user.name) : 'U' }}
                                </AvatarFallback>
                            </Avatar>
                            <div>
                                <h1 class="text-2xl font-bold">Welcome back, {{ user?.name?.split(' ')[0] || 'User' }}!
                                </h1>
                                <p class="opacity-90">Here's an overview of your account</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <Badge class="bg-amber-900 hover:bg-amber-900/30 transition-colors">
                                <span v-if="user?.membership_tier" class="capitalize">{{ user.membership_tier }}
                                    Member</span>
                                <span v-else>Basic Member</span>
                            </Badge>
                            <Badge class="bg-green-500 hover:bg-green-500/30 transition-colors">
                                {{ user?.happi_coins || 0 }} HappiCoins
                            </Badge>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Quick Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Insurance Stats -->
                <Card class="hover:shadow-md transition-shadow">
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Insurance</CardTitle>
                        <Shield class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ insuranceData.activePolicies }}</div>
                        <p class="text-xs text-muted-foreground">Active policies</p>
                        <div v-if="insuranceData.expiringPolicies > 0" class="mt-2 text-xs text-amber-500">
                            {{ insuranceData.expiringPolicies }} expiring soon
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button variant="ghost" size="sm" class="w-full justify-between" as-child>
                            <Link href="/insurance" class="flex justify-between w-full">
                            View policies
                            <ChevronRight class="h-4 w-4" />
                            </Link>
                        </Button>
                    </CardFooter>
                </Card>

                <!-- Vehicles Stats -->
                <Card class="hover:shadow-md transition-shadow">
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Vehicles</CardTitle>
                        <Car class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ vehicleData.totalVehicles }}</div>
                        <p class="text-xs text-muted-foreground">Registered vehicles</p>
                        <div v-if="vehicleData.insuredVehicles < vehicleData.totalVehicles"
                            class="mt-2 text-xs text-amber-500">
                            {{ vehicleData.totalVehicles - vehicleData.insuredVehicles }} uninsured
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button variant="ghost" size="sm" class="w-full justify-between" as-child>
                            <Link href="/vehicles" class="flex justify-between w-full">
                            Manage vehicles
                            <ChevronRight class="h-4 w-4" />
                            </Link>
                        </Button>
                    </CardFooter>
                </Card>

                <!-- Family Stats -->
                <Card class="hover:shadow-md transition-shadow">
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Family Group</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ familyData.memberCount }}</div>
                        <p class="text-xs text-muted-foreground">Family members</p>
                        <div v-if="familyData.invitationsPending > 0" class="mt-2 text-xs text-amber-500">
                            {{ familyData.invitationsPending }} pending invitation(s)
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button variant="ghost" size="sm" class="w-full justify-between" as-child>
                            <Link href="/family" class="flex justify-between w-full">
                            Manage family
                            <ChevronRight class="h-4 w-4" />
                            </Link>
                        </Button>
                    </CardFooter>
                </Card>

                <!-- Notifications Stats -->
                <Card class="hover:shadow-md transition-shadow">
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Notifications</CardTitle>
                        <Bell class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ notificationData.totalCount }}</div>
                        <p class="text-xs text-muted-foreground">Total notifications</p>
                        <div v-if="notificationData.unreadCount > 0" class="mt-2 text-xs text-amber-500">
                            {{ notificationData.unreadCount }} unread
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button variant="ghost" size="sm" class="w-full justify-between" as-child>
                            <Link href="/notifications" class="flex justify-between w-full">
                            View notifications
                            <ChevronRight class="h-4 w-4" />
                            </Link>
                        </Button>
                    </CardFooter>
                </Card>
            </div>

            <!-- Quick Actions -->
            <Card>
                <CardHeader>
                    <CardTitle>Quick Actions</CardTitle>
                    <CardDescription>Access your most frequent tasks</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                        <Button variant="outline" class="h-auto flex flex-col items-center gap-2 p-4" as-child>
                            <Link href="/insurance" class="flex flex-col items-center">
                            <Shield class="h-8 w-8 mb-1" />
                            <span>Buy Insurance</span>
                            </Link>
                        </Button>

                        <Button variant="outline" class="h-auto flex flex-col items-center gap-2 p-4" as-child>
                            <Link href="/membership" class="flex flex-col items-center">
                            <CreditCard class="h-8 w-8 mb-1" />
                            <span>Membership</span>
                            </Link>
                        </Button>

                        <Button variant="outline" class="h-auto flex flex-col items-center gap-2 p-4" as-child>
                            <Link href="/vehicles/add" class="flex flex-col items-center">
                            <Car class="h-8 w-8 mb-1" />
                            <span>Add Vehicle</span>
                            </Link>
                        </Button>

                        <Button variant="outline" class="h-auto flex flex-col items-center gap-2 p-4" as-child>
                            <Link href="/family/invite" class="flex flex-col items-center">
                            <Users class="h-8 w-8 mb-1" />
                            <span>Invite Family</span>
                            </Link>
                        </Button>

                        <Button variant="outline" class="h-auto flex flex-col items-center gap-2 p-4" as-child>
                            <Link href="/profile" class="flex flex-col items-center">
                            <Settings class="h-8 w-8 mb-1" />
                            <span>Profile</span>
                            </Link>
                        </Button>

                        <Button variant="outline" class="h-auto flex flex-col items-center gap-2 p-4" as-child>
                            <Link href="/contact" class="flex flex-col items-center">
                            <BarChart4 class="h-8 w-8 mb-1" />
                            <span>Support</span>
                            </Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Insurance & Membership Status -->
                <Card>
                    <CardHeader>
                        <CardTitle>Recent Policies</CardTitle>
                        <CardDescription>Your active insurance policies</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="policy in recentPolicies" :key="policy.id"
                                class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="h-10 w-10 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                                        <Shield v-if="policy.type === 'auto'" class="h-5 w-5" />
                                        <Users v-else-if="policy.type === 'health'" class="h-5 w-5" />
                                        <Shield v-else class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ policy.name }}</div>
                                        <div class="text-xs text-muted-foreground flex items-center">
                                            <Clock class="h-3 w-3 mr-1" />
                                            Expires: {{ formatDate(policy.expiresAt) }}
                                        </div>
                                    </div>
                                </div>
                                <Badge :variant="policy.status === 'active' ? 'default' : 'outline'">
                                    {{ policy.status }}
                                </Badge>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button variant="ghost" class="w-full" as-child>
                            <Link href="/profile#insurance">View all policies</Link>
                        </Button>
                    </CardFooter>
                </Card>

                <!-- Upcoming Events -->
                <Card>
                    <CardHeader>
                        <CardTitle>Upcoming Events</CardTitle>
                        <CardDescription>Important dates to remember</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="event in upcomingEvents" :key="event.id"
                                class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="h-10 w-10 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                                        <Calendar class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ event.title }}</div>
                                        <div class="text-xs text-muted-foreground">
                                            {{ formatDate(event.date) }}
                                        </div>
                                    </div>
                                </div>
                                <Badge :variant="event.type === 'renewal' ? 'default' : 'destructive'">
                                    {{ event.type === 'renewal' ? 'Renewal' : 'Expiration' }}
                                </Badge>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button variant="ghost" class="w-full" as-child>
                            <Link href="/calendar">View calendar</Link>
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
