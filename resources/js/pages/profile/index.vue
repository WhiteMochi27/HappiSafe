// resources/js/Pages/Profile/Index.vue
<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Tabs,
    TabsContent,
    TabsList,
    TabsTrigger,
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
    CardFooter,
    Button,
    Badge,
    Progress,
    Avatar,
    AvatarFallback,
    AvatarImage,
    Separator
} from '@/components/ui';
import {
    User,
    Shield,
    Car,
    UserPlus,
    History,
    Settings,
    LogOut,
    CreditCard,
    Award,
    Clock,
    Copy,
    Check
} from 'lucide-vue-next';

const props = defineProps<{
    user: any;
    insurances: any[];
    vehicles: any[];
    familyGroups: any[];
    familyInvitations: any[];
    transactions: any[];
}>();

// For demonstration, we'll use some hardcoded data
// In production, this would come from the backend

const user = computed(() => props.user || {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    phone_number: '+60123456789',
    account_type: 'personal',
    membership_tier: 'gold',
    happi_coins: 1250,
    membership_expires_at: '2024-12-31',
    avatar: null
});

const membershipInfo = computed(() => {
    const tierInfo = {
        basic: { color: 'neutral', next: 'silver', nextPrice: 99 },
        silver: { color: 'zinc', next: 'gold', nextPrice: 199 },
        gold: { color: 'yellow', next: 'platinum', nextPrice: 349 },
        platinum: { color: 'violet', next: null, nextPrice: null }
    };

    // const tierData = tierInfo[user.value.membership_tier] || tierInfo.basic;
    const tierData = tierInfo[user.value.membership_tier as keyof typeof tierInfo] || tierInfo.basic;

    return {
        tier: user.value.membership_tier,
        color: tierData.color,
        expires: user.value.membership_expires_at,
        next: tierData.next,
        nextPrice: tierData.nextPrice
    };
});

const membershipProgress = computed(() => {
    const tiers = ['basic', 'silver', 'gold', 'platinum'];
    const currentIndex = tiers.indexOf(user.value.membership_tier);
    return (currentIndex / (tiers.length - 1)) * 100;
});

const insurances = computed(() => props.insurances || [
    {
        id: 1,
        name: 'Comprehensive Auto Insurance',
        policy_number: 'HAUTO-123456',
        status: 'active',
        expires_at: '2024-12-15',
        price_paid: 299,
        vehicle: { make: 'Toyota', model: 'Camry', year: '2020' }
    },
    {
        id: 2,
        name: 'Family Health Plan',
        policy_number: 'HEALTH-456789',
        status: 'active',
        expires_at: '2024-10-30',
        price_paid: 599
    }
]);

const vehicles = computed(() => props.vehicles || [
    {
        id: 1,
        make: 'Toyota',
        model: 'Camry',
        year: '2020',
        license_plate: 'ABC1234',
        color: 'Silver'
    }
]);

const familyGroups = computed(() => props.familyGroups || [
    {
        id: 1,
        name: 'Doe Family',
        members: [
            { id: 2, name: 'Jane Doe', relationship: 'spouse', email: 'jane@example.com' },
            { id: 3, name: 'Jake Doe', relationship: 'child', email: 'jake@example.com' }
        ]
    }
]);

const familyInvitations = computed(() => props.familyInvitations || [
    {
        id: 1,
        email: 'sarah@example.com',
        status: 'pending',
        expires_at: '2024-06-30'
    }
]);

const transactions = computed(() => props.transactions || [
    {
        id: 1,
        transaction_type: 'membership_purchase',
        amount: 199,
        payment_method: 'credit_card',
        payment_status: 'completed',
        created_at: '2023-12-15'
    },
    {
        id: 2,
        transaction_type: 'insurance_purchase',
        amount: 299,
        payment_method: 'credit_card',
        payment_status: 'completed',
        created_at: '2023-12-20'
    }
]);

// Function to copy referral link
const referralLink = `https://happiinsurance.com/ref/${user.value.id}`;
const copied = ref(false);

const copyReferralLink = () => {
    navigator.clipboard.writeText(referralLink);
    copied.value = true;
    setTimeout(() => {
        copied.value = false;
    }, 2000);
};

// Tab state
const activeTab = ref('overview');
</script>

<template>

    <Head title="My Profile | HappiInsurance" />

    <AppLayout>
        <div class="container mx-auto px-4 py-8">
            <!-- Profile Header -->
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center">
                    <div class="flex items-center mb-4 md:mb-0">
                        <Avatar class="h-20 w-20 mr-4">
                            <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="user.name" />
                            <AvatarFallback class="bg-primary/10 text-primary text-xl">
                                {{ user.name.charAt(0) }}{{ user.name.split(' ')[1]?.charAt(0) || '' }}
                            </AvatarFallback>
                        </Avatar>
                        <div>
                            <h1 class="text-2xl font-bold">{{ user.name }}</h1>
                            <p class="text-neutral-500">{{ user.email }}</p>
                            <p class="text-sm text-neutral-500">{{ user.phone_number }}</p>
                            <div class="flex items-center mt-1">
                                <Badge :variant="user.account_type === 'personal' ? 'default' : 'outline'" class="mr-2">
                                    {{ user.account_type === 'personal' ? 'Personal Account' : 'Business Account' }}
                                </Badge>
                                <Badge :variant="membershipInfo.color">
                                    {{ user.membership_tier.charAt(0).toUpperCase() + user.membership_tier.slice(1) }}
                                    Member
                                </Badge>
                            </div>
                        </div>
                    </div>

                    <div class="md:ml-auto mt-4 md:mt-0 flex items-center space-x-4">
                        <div class="bg-neutral-100 dark:bg-neutral-700 p-3 rounded-lg text-center">
                            <div class="text-yellow-500 font-bold text-xl">{{ user.happi_coins }}</div>
                            <div class="text-xs text-neutral-500">HappiCoins</div>
                        </div>

                        <Button variant="outline" as-child>
                            <Link href="/settings">
                            <Settings class="mr-2 h-4 w-4" />
                            Settings
                            </Link>
                        </Button>

                        <Button variant="destructive" as-child>
                            <Link href="/logout" method="post" as="button">
                            <LogOut class="mr-2 h-4 w-4" />
                            Logout
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Main Tabs Navigation -->
            <Tabs v-model="activeTab" class="space-y-4">
                <TabsList class="grid w-full grid-cols-5">
                    <TabsTrigger value="overview">
                        <Shield class="mr-2 h-4 w-4" />
                        Overview
                    </TabsTrigger>
                    <TabsTrigger value="insurance">
                        <Shield class="mr-2 h-4 w-4" />
                        Insurance
                    </TabsTrigger>
                    <TabsTrigger value="vehicles">
                        <Car class="mr-2 h-4 w-4" />
                        Vehicles
                    </TabsTrigger>
                    <TabsTrigger value="family">
                        <UserPlus class="mr-2 h-4 w-4" />
                        Family
                    </TabsTrigger>
                    <TabsTrigger value="history">
                        <History class="mr-2 h-4 w-4" />
                        History
                    </TabsTrigger>
                </TabsList>

                <!-- Overview Tab -->
                <TabsContent value="overview" class="space-y-6">
                    <!-- Membership Status -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Membership Status</CardTitle>
                            <CardDescription>Your current membership plan benefits and status</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="flex flex-col md:flex-row md:items-center mb-4">
                                <div class="mb-4 md:mb-0 md:mr-8">
                                    <div class="flex items-center">
                                        <Award class="text-primary h-8 w-8 mr-2" />
                                        <div>
                                            <div class="text-lg font-medium capitalize">{{ user.membership_tier }}
                                                Membership</div>
                                            <div class="text-sm text-neutral-500 flex items-center">
                                                <Clock class="h-4 w-4 mr-1" />
                                                Expires: {{ new Date(membershipInfo.expires).toLocaleDateString() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>Basic</span>
                                        <span>Silver</span>
                                        <span>Gold</span>
                                        <span>Platinum</span>
                                    </div>
                                    <Progress :value="membershipProgress" class="h-2 mb-2" />
                                </div>
                            </div>

                            <div v-if="membershipInfo.next"
                                class="mt-4 bg-neutral-50 dark:bg-neutral-800 p-4 rounded-lg">
                                <div class="text-sm">
                                    Upgrade to <span class="font-medium capitalize">{{ membershipInfo.next }}</span> for
                                    just
                                    <span class="font-bold">${{ membershipInfo.nextPrice }}</span>/year to get more
                                    benefits!
                                </div>
                                <Button as-child variant="outline" class="mt-2">
                                    <Link :href="`/membership/${membershipInfo.next}`">
                                    Upgrade Membership
                                    </Link>
                                </Button>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Quick Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Insurance Summary -->
                        <Card>
                            <CardHeader>
                                <CardTitle>Insurance Policies</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="text-3xl font-bold">{{ insurances.length }}</div>
                                <p class="text-sm text-neutral-500">Active policies</p>
                            </CardContent>
                            <CardFooter>
                                <Button as-child variant="ghost" class="w-full">
                                    <Link href="#insurance" @click="activeTab = 'insurance'">
                                    View All Policies
                                    </Link>
                                </Button>
                            </CardFooter>
                        </Card>

                        <!-- Vehicles Summary -->
                        <Card>
                            <CardHeader>
                                <CardTitle>Registered Vehicles</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="text-3xl font-bold">{{ vehicles.length }}</div>
                                <p class="text-sm text-neutral-500">Registered vehicles</p>
                            </CardContent>
                            <CardFooter>
                                <Button as-child variant="ghost" class="w-full">
                                    <Link href="#vehicles" @click="activeTab = 'vehicles'">
                                    Manage Vehicles
                                    </Link>
                                </Button>
                            </CardFooter>
                        </Card>

                        <!-- Family Summary -->
                        <Card>
                            <CardHeader>
                                <CardTitle>Family Members</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="text-3xl font-bold">
                                    {{ familyGroups.length > 0
                                        ? familyGroups[0].members.length
                                        : 0 }}
                                </div>
                                <p class="text-sm text-neutral-500">Family members</p>
                            </CardContent>
                            <CardFooter>
                                <Button as-child variant="ghost" class="w-full">
                                    <Link href="#family" @click="activeTab = 'family'">
                                    Manage Family
                                    </Link>
                                </Button>
                            </CardFooter>
                        </Card>
                    </div>

                    <!-- Referral Section -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Refer Friends & Family</CardTitle>
                            <CardDescription>Share your referral link and earn HappiCoins for each successful referral
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="flex items-center space-x-2">
                                <div class="relative flex-1">
                                    <input type="text" readonly :value="referralLink"
                                        class="w-full p-2 pr-10 border rounded-md bg-neutral-50" />
                                    <button @click="copyReferralLink"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2">
                                        <Copy v-if="!copied" class="h-5 w-5 text-neutral-500 hover:text-primary" />
                                        <Check v-else class="h-5 w-5 text-green-500" />
                                    </button>
                                </div>
                                <Button @click="copyReferralLink">
                                    {{ copied ? 'Copied!' : 'Copy Link' }}
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Insurance Tab -->
                <TabsContent value="insurance" class="space-y-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">My Insurance Policies</h2>
                        <Button as-child>
                            <Link href="/insurance">
                            Browse New Policies
                            </Link>
                        </Button>
                    </div>

                    <div v-if="insurances.length === 0"
                        class="text-center py-8 bg-white dark:bg-neutral-800 rounded-lg shadow-sm">
                        <p class="text-lg text-neutral-500">You don't have any insurance policies yet.</p>
                        <Button as-child class="mt-4">
                            <Link href="/insurance">
                            Browse Insurance Plans
                            </Link>
                        </Button>
                    </div>

                    <div v-else class="space-y-4">
                        <Card v-for="insurance in insurances" :key="insurance.id">
                            <CardHeader>
                                <div class="flex justify-between">
                                    <div>
                                        <CardTitle>{{ insurance.name }}</CardTitle>
                                        <CardDescription>Policy #: {{ insurance.policy_number }}</CardDescription>
                                    </div>
                                    <Badge :variant="insurance.status === 'active' ? 'success' : 'destructive'">
                                        {{ insurance.status.charAt(0).toUpperCase() + insurance.status.slice(1) }}
                                    </Badge>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <div class="text-sm text-neutral-500">Price Paid</div>
                                        <div class="font-medium">${{ insurance.price_paid }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-neutral-500">Expiration Date</div>
                                        <div class="font-medium">{{ new Date(insurance.expires_at).toLocaleDateString()
                                        }}</div>
                                    </div>
                                    <div v-if="insurance.vehicle">
                                        <div class="text-sm text-neutral-500">Vehicle</div>
                                        <div class="font-medium">{{ insurance.vehicle.make }} {{ insurance.vehicle.model
                                        }} ({{ insurance.vehicle.year }})</div>
                                    </div>
                                </div>
                            </CardContent>
                            <CardFooter class="flex justify-between">
                                <Button as-child variant="outline">
                                    <Link :href="`/insurance/policy/${insurance.id}`">
                                    View Details
                                    </Link>
                                </Button>
                                <Button v-if="insurance.status === 'active'" as-child>
                                    <Link :href="`/insurance/renew/${insurance.id}`">
                                    Renew Policy
                                    </Link>
                                </Button>
                            </CardFooter>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Vehicles Tab -->
                <TabsContent value="vehicles" class="space-y-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">My Vehicles</h2>
                        <Button as-child>
                            <Link href="/vehicles/add">
                            Add New Vehicle
                            </Link>
                        </Button>
                    </div>

                    <div v-if="vehicles.length === 0"
                        class="text-center py-8 bg-white dark:bg-neutral-800 rounded-lg shadow-sm">
                        <p class="text-lg text-neutral-500">You don't have any registered vehicles yet.</p>
                        <Button as-child class="mt-4">
                            <Link href="/vehicles/add">
                            Add Vehicle
                            </Link>
                        </Button>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <Card v-for="vehicle in vehicles" :key="vehicle.id">
                            <CardHeader>
                                <CardTitle>{{ vehicle.make }} {{ vehicle.model }}</CardTitle>
                                <CardDescription>{{ vehicle.year }}</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-sm text-neutral-500">License Plate</div>
                                        <div class="font-medium">{{ vehicle.license_plate }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-neutral-500">Color</div>
                                        <div class="font-medium">{{ vehicle.color }}</div>
                                    </div>
                                </div>

                                <!-- Vehicle Insurance Status -->
                                <div class="mt-4 p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                                    <div class="text-sm font-medium mb-1">Insurance Status</div>
                                    <div v-if="insurances.some(i => i.vehicle?.make === vehicle.make && i.status === 'active')"
                                        class="text-green-500 text-sm flex items-center">
                                        <Shield class="h-4 w-4 mr-1" />
                                        Insured
                                    </div>
                                    <div v-else class="text-red-500 text-sm flex items-center">
                                        <Shield class="h-4 w-4 mr-1" />
                                        Not Insured
                                    </div>
                                </div>
                            </CardContent>
                            <CardFooter class="flex justify-between">
                                <Button as-child variant="outline">
                                    <Link :href="`/vehicles/${vehicle.id}`">
                                    Edit
                                    </Link>
                                </Button>
                                <Button
                                    v-if="!insurances.some(i => i.vehicle?.make === vehicle.make && i.status === 'active')"
                                    as-child>
                                    <Link :href="`/insurance/auto?vehicle=${vehicle.id}`">
                                    Get Insurance
                                    </Link>
                                </Button>
                            </CardFooter>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Family Tab -->
                <TabsContent value="family" class="space-y-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">Family Group</h2>
                        <div class="space-x-2">
                            <Button v-if="familyGroups.length === 0" as-child>
                                <Link href="/family/create">
                                Create Family Group
                                </Link>
                            </Button>
                            <Button v-else as-child>
                                <Link href="/family/invite">
                                Invite Member
                                </Link>
                            </Button>
                        </div>
                    </div>

                    <div v-if="familyGroups.length === 0"
                        class="text-center py-8 bg-white dark:bg-neutral-800 rounded-lg shadow-sm">
                        <p class="text-lg text-neutral-500">You haven't created a family group yet.</p>
                        <Button as-child class="mt-4">
                            <Link href="/family/create">
                            Create Family Group
                            </Link>
                        </Button>
                    </div>

                    <div v-else>
                        <Card v-for="group in familyGroups" :key="group.id">
                            <CardHeader>
                                <CardTitle>{{ group.name }}</CardTitle>
                                <CardDescription>Family Group Members</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="member in group.members" :key="member.id"
                                        class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <Avatar class="h-10 w-10 mr-3">
                                                <AvatarFallback class="bg-primary/10 text-primary">
                                                    {{ member.name.charAt(0) }}
                                                </AvatarFallback>
                                            </Avatar>
                                            <div>
                                                <div class="font-medium">{{ member.name }}</div>
                                                <div class="text-sm text-neutral-500">{{ member.email }}</div>
                                            </div>
                                        </div>
                                        <Badge>{{ member.relationship }}</Badge>
                                    </div>
                                </div>

                                <!-- Pending Invitations -->
                                <div v-if="familyInvitations.length > 0" class="mt-6">
                                    <Separator class="my-4" />
                                    <h3 class="text-sm font-medium mb-2">Pending Invitations</h3>
                                    <div class="space-y-2">
                                        <div v-for="invitation in familyInvitations" :key="invitation.id"
                                            class="flex items-center justify-between">
                                            <div>
                                                <div class="font-medium">{{ invitation.email }}</div>
                                                <div class="text-xs text-neutral-500">
                                                    Expires: {{ new Date(invitation.expires_at).toLocaleDateString() }}
                                                </div>
                                            </div>
                                            <Badge variant="outline">Pending</Badge>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                            <CardFooter>
                                <Button as-child variant="outline">
                                    <Link :href="`/family/${group.id}`">
                                    Manage Family Group
                                    </Link>
                                </Button>
                            </CardFooter>
                        </Card>
                    </div>
                </TabsContent>

                <!-- History Tab -->
                <TabsContent value="history" class="space-y-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">Purchase History</h2>
                    </div>

                    <div v-if="transactions.length === 0"
                        class="text-center py-8 bg-white dark:bg-neutral-800 rounded-lg shadow-sm">
                        <p class="text-lg text-neutral-500">You don't have any transactions yet.</p>
                    </div>

                    <div v-else>
                        <Card>
                            <CardHeader>
                                <CardTitle>Transaction History</CardTitle>
                                <CardDescription>Your recent purchases and payments</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="transaction in transactions" :key="transaction.id"
                                        class="flex items-center justify-between p-3 border-b last:border-b-0">
                                        <div>
                                            <div class="font-medium">
                                                {{ transaction.transaction_type === 'membership_purchase'
                                                    ? 'Membership Purchase'
                                                    : 'Insurance Purchase' }}
                                            </div>
                                            <div class="text-sm text-neutral-500">
                                                {{ new Date(transaction.created_at).toLocaleDateString() }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold">${{ transaction.amount }}</div>
                                            <Badge
                                                :variant="transaction.payment_status === 'completed' ? 'success' : 'destructive'"
                                                class="text-xs">
                                                {{ transaction.payment_status }}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                            <CardFooter>
                                <Button as-child variant="outline" class="w-full">
                                    <Link href="/transactions">
                                    View All Transactions
                                    </Link>
                                </Button>
                            </CardFooter>
                        </Card>
                    </div>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>
