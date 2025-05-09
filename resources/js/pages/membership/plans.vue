// resources/js/Pages/Membership/Plans.vue
<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
    CardFooter,
    Button,
    Badge,
    Tabs,
    TabsList,
    TabsTrigger,
    TabsContent
} from '@/components/ui';
import {
    CheckCircle2,
    XCircle,
    Shield,
    Award,
    Sparkles,
    Crown,
    Clock
} from 'lucide-vue-next';

defineProps<{
    plans: any[];
    currentPlan?: string;
}>();

const { auth } = usePage().props as any;
const user = auth.user;

// For demonstration, we'll use hardcoded data
// In production, this would come from the backend
const membershipPlans = ref([
    {
        id: 1,
        name: 'Basic',
        tier: 'basic',
        price: 0,
        duration_days: 365,
        happi_coins_reward: 0,
        description: 'Basic membership with standard benefits',
        benefits: [
            { name: '10% discount on insurance products', included: true },
            { name: 'Access to online portal', included: true },
            { name: 'Basic customer support', included: true },
            { name: 'Priority customer support', included: false },
            { name: 'HappiCoins rewards', included: false },
            { name: 'Access to exclusive deals', included: false },
            { name: 'Free policy upgrades', included: false },
            { name: 'Family discount', included: false },
            { name: 'Annual health check-up', included: false }
        ],
        icon: Shield,
        iconClass: 'text-neutral-500',
        color: 'neutral',
        featured: false
    },
    {
        id: 2,
        name: 'Silver',
        tier: 'silver',
        price: 99,
        duration_days: 365,
        happi_coins_reward: 200,
        description: 'Enhanced membership with premium benefits',
        benefits: [
            { name: '15% discount on insurance products', included: true },
            { name: 'Access to online portal', included: true },
            { name: 'Basic customer support', included: true },
            { name: 'Priority customer support', included: true },
            { name: '2x HappiCoins rewards', included: true },
            { name: 'Access to exclusive deals', included: true },
            { name: 'Free policy upgrades', included: false },
            { name: 'Family discount', included: false },
            { name: 'Annual health check-up', included: false }
        ],
        icon: Award,
        iconClass: 'text-zinc-400',
        color: 'zinc',
        featured: false
    },
    {
        id: 3,
        name: 'Gold',
        tier: 'gold',
        price: 199,
        duration_days: 365,
        happi_coins_reward: 500,
        description: 'Premium membership with exclusive benefits',
        benefits: [
            { name: '20% discount on insurance products', included: true },
            { name: 'Access to online portal', included: true },
            { name: 'Basic customer support', included: true },
            { name: 'Priority customer support', included: true },
            { name: '3x HappiCoins rewards', included: true },
            { name: 'Access to exclusive deals', included: true },
            { name: 'Free policy upgrades', included: true },
            { name: 'Family discount', included: true },
            { name: 'Annual health check-up', included: false }
        ],
        icon: Sparkles,
        iconClass: 'text-yellow-500',
        color: 'yellow',
        featured: true
    },
    {
        id: 4,
        name: 'Platinum',
        tier: 'platinum',
        price: 349,
        duration_days: 365,
        happi_coins_reward: 1000,
        description: 'Ultimate membership with all premium benefits',
        benefits: [
            { name: '25% discount on insurance products', included: true },
            { name: 'Access to online portal', included: true },
            { name: 'Basic customer support', included: true },
            { name: 'Priority customer support', included: true },
            { name: '5x HappiCoins rewards', included: true },
            { name: 'Access to exclusive deals', included: true },
            { name: 'Free policy upgrades', included: true },
            { name: 'Family discount', included: true },
            { name: 'Annual health check-up', included: true }
        ],
        icon: Crown,
        iconClass: 'text-violet-500',
        color: 'violet',
        featured: false
    }
]);

// User's current membership tier (would come from backend)
const currentTier = computed(() => user?.membership_tier || 'basic');

// Billing period
const billingPeriod = ref('yearly');

// Computed plans based on billing period (for demonstration)
const displayPlans = computed(() => {
    if (billingPeriod.value === 'monthly') {
        // For monthly billing, we'll just show a simple conversion
        return membershipPlans.value.map(plan => ({
            ...plan,
            price: Math.round(plan.price / 12),
            duration_days: 30,
            happi_coins_reward: Math.round(plan.happi_coins_reward / 12)
        }));
    }
    return membershipPlans.value;
});

// Determine if plan is current or upgradable
const isPlanCurrent = (tier: string) => {
    return tier === currentTier.value;
};

const isPlanUpgrade = (tier: string) => {
    const tiers = ['basic', 'silver', 'gold', 'platinum'];
    const currentIndex = tiers.indexOf(currentTier.value);
    const planIndex = tiers.indexOf(tier);
    return planIndex > currentIndex;
};

const isPlanDowngrade = (tier: string) => {
    const tiers = ['basic', 'silver', 'gold', 'platinum'];
    const currentIndex = tiers.indexOf(currentTier.value);
    const planIndex = tiers.indexOf(tier);
    return planIndex < currentIndex && planIndex >= 0;
};

// Get button text based on plan and current membership
const getButtonText = (tier: string) => {
    if (isPlanCurrent(tier)) {
        return 'Current Plan';
    } else if (isPlanUpgrade(tier)) {
        return 'Upgrade Plan';
    } else if (isPlanDowngrade(tier)) {
        return 'Downgrade Plan';
    } else {
        return 'Select Plan';
    }
};
</script>

<template>

    <Head title="Membership Plans | HappiInsurance" />

    <AppLayout>
        <!-- Hero Banner -->
        <section class="relative bg-gradient-to-r from-primary/90 to-primary py-12">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-4 text-white">
                    HappiMembership Plans
                </h1>
                <p class="text-lg text-white/90 mb-6 max-w-2xl mx-auto">
                    Join our membership program to unlock exclusive benefits, earn rewards, and save on insurance
                    products.
                </p>

                <div class="flex justify-center">
                    <Tabs v-model="billingPeriod" class="inline-flex bg-white/10 backdrop-blur-sm rounded-lg p-1">
                        <TabsList class="grid w-64 grid-cols-2">
                            <TabsTrigger value="monthly"
                                class="text-white data-[state=active]:bg-white data-[state=active]:text-primary">
                                Monthly
                            </TabsTrigger>
                            <TabsTrigger value="yearly"
                                class="text-white data-[state=active]:bg-white data-[state=active]:text-primary">
                                Yearly
                            </TabsTrigger>
                        </TabsList>
                    </Tabs>
                </div>

                <div v-if="billingPeriod === 'yearly'"
                    class="mt-4 inline-block bg-green-500/20 text-white text-sm py-1 px-3 rounded-full">
                    Save up to 16% with yearly billing
                </div>
            </div>
        </section>

        <section class="py-12">
            <div class="container mx-auto px-4">
                <!-- User's Current Plan -->
                <div v-if="user" class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm p-6 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div class="mb-4 md:mb-0 md:mr-auto">
                            <h2 class="font-medium text-lg">Your Current Membership</h2>
                            <div class="flex items-center mt-1">
                                <component :is="membershipPlans.find(p => p.tier === currentTier)?.icon"
                                    :class="`h-5 w-5 mr-2 ${membershipPlans.find(p => p.tier === currentTier)?.iconClass}`" />
                                <span class="capitalize font-bold">{{ currentTier }} Membership</span>
                                <Badge class="ml-2" :variant="membershipPlans.find(p => p.tier === currentTier)?.color">
                                    Active
                                </Badge>
                            </div>
                            <div class="text-sm text-neutral-500 flex items-center mt-1"
                                v-if="user.membership_expires_at">
                                <Clock class="h-4 w-4 mr-1" />
                                Expires: {{ new Date(user.membership_expires_at).toLocaleDateString() }}
                            </div>
                        </div>

                        <Button v-if="currentTier !== 'platinum'" as-child>
                            <Link href="#plans">
                            Upgrade Membership
                            </Link>
                        </Button>
                    </div>
                </div>

                <!-- Plans Comparison -->
                <div id="plans" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Card v-for="plan in displayPlans" :key="plan.id" class="relative flex flex-col" :class="{
                        'border-primary shadow-md': plan.featured,
                        'border-2 border-primary': isPlanCurrent(plan.tier)
                    }">
                        <div v-if="plan.featured"
                            class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-3 bg-primary text-white text-xs font-bold px-3 py-1 rounded-full">
                            Most Popular
                        </div>

                        <div v-if="isPlanCurrent(plan.tier)"
                            class="absolute top-0 right-0 bg-primary text-white text-xs font-bold px-3 py-1">
                            Current
                        </div>

                        <CardHeader class="text-center">
                            <div class="flex justify-center mb-4">
                                <div
                                    :class="`w-16 h-16 rounded-full bg-${plan.color}-100 dark:bg-${plan.color}-900/20 flex items-center justify-center`">
                                    <component :is="plan.icon" :class="`h-8 w-8 ${plan.iconClass}`" />
                                </div>
                            </div>

                            <CardTitle>{{ plan.name }}</CardTitle>
                            <CardDescription>{{ plan.description }}</CardDescription>

                            <div class="mt-4">
                                <div class="flex items-center justify-center">
                                    <span class="text-3xl font-bold">${{ plan.price }}</span>
                                    <span class="text-neutral-500 ml-1">
                                        /{{ billingPeriod === 'yearly' ? 'year' : 'month' }}
                                    </span>
                                </div>

                                <div v-if="plan.happi_coins_reward > 0"
                                    class="mt-2 text-sm text-yellow-500 flex items-center justify-center">
                                    Earn {{ plan.happi_coins_reward }} HappiCoins
                                </div>
                            </div>
                        </CardHeader>

                        <CardContent class="flex-grow">
                            <ul class="space-y-3">
                                <li v-for="(benefit, index) in plan.benefits" :key="index" class="flex items-start">
                                    <div v-if="benefit.included" class="text-green-500 mr-2 shrink-0">
                                        <CheckCircle2 class="h-5 w-5" />
                                    </div>
                                    <div v-else class="text-neutral-300 dark:text-neutral-600 mr-2 shrink-0">
                                        <XCircle class="h-5 w-5" />
                                    </div>
                                    <span :class="{ 'text-neutral-400 dark:text-neutral-600': !benefit.included }">
                                        {{ benefit.name }}
                                    </span>
                                </li>
                            </ul>
                        </CardContent>

                        <CardFooter>
                            <Button as-child
                                :variant="plan.featured || isPlanUpgrade(plan.tier) ? 'default' : 'outline'"
                                class="w-full" :disabled="isPlanCurrent(plan.tier)">
                                <Link v-if="!isPlanCurrent(plan.tier)" :href="`/membership/checkout/${plan.tier}`">
                                {{ getButtonText(plan.tier) }}
                                </Link>
                                <span v-else>{{ getButtonText(plan.tier) }}</span>
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Features Comparison -->
        <section class="py-12 bg-neutral-50 dark:bg-neutral-800">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Compare Membership Features</h2>
                    <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                        Explore all the benefits and features included in each of our membership plans.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full bg-white dark:bg-neutral-900 rounded-lg shadow-sm">
                        <thead>
                            <tr>
                                <th class="p-4 text-left">Feature</th>
                                <th v-for="plan in membershipPlans" :key="plan.id" class="p-4 text-center"
                                    :class="{ 'bg-primary/5': isPlanCurrent(plan.tier) }">
                                    <div class="flex flex-col items-center">
                                        <component :is="plan.icon" :class="`h-6 w-6 mb-2 ${plan.iconClass}`" />
                                        <span class="font-bold">{{ plan.name }}</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t">
                                <td class="p-4 font-medium">{{ benefit.name }}</td>
                                <td v-for="plan in membershipPlans" :key="`${index}-${plan.id}`" class="p-4 text-center"
                                    :class="{ 'bg-primary/5': isPlanCurrent(plan.tier) }">
                                    <div v-if="plan.benefits[index].included"
                                        class="text-green-500 flex justify-center">
                                        <CheckCircle2 class="h-5 w-5" />
                                    </div>
                                    <div v-else class="text-neutral-300 dark:text-neutral-600 flex justify-center">
                                        <XCircle class="h-5 w-5" />
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-t">
                                <td class="p-4"></td>
                                <td v-for="plan in membershipPlans" :key="`button-${plan.id}`" class="p-4 text-center"
                                    :class="{ 'bg-primary/5': isPlanCurrent(plan.tier) }">
                                    <Button as-child
                                        :variant="plan.featured || isPlanUpgrade(plan.tier) ? 'default' : 'outline'"
                                        size="sm" :disabled="isPlanCurrent(plan.tier)">
                                        <Link v-if="!isPlanCurrent(plan.tier)"
                                            :href="`/membership/checkout/${plan.tier}`">
                                        {{ getButtonText(plan.tier) }}
                                        </Link>
                                        <span v-else>{{ getButtonText(plan.tier) }}</span>
                                    </Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-12 bg-white dark:bg-neutral-900">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Frequently Asked Questions</h2>
                    <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                        Get answers to commonly asked questions about our membership plans.
                    </p>
                </div>

                <div class="max-w-3xl mx-auto space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>What are HappiCoins and how do they work?</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p>
                                HappiCoins are our loyalty reward points. You earn HappiCoins when you purchase a
                                membership plan or insurance
                                product. These coins can be used to get discounts on future insurance purchases, policy
                                upgrades, or even
                                premium reductions. Higher membership tiers earn more HappiCoins with every purchase!
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Can I change my membership plan later?</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p>
                                Yes, you can upgrade your membership plan at any time. The price difference will be
                                prorated based on
                                the remaining time on your current membership. Downgrades are processed at renewal time.
                                Contact our
                                customer support for assistance with plan changes.
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>How do family discounts work?</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p>
                                Gold and Platinum memberships include family discounts on insurance products. You can
                                add family members
                                to your family group, and they'll receive discounts on their insurance purchases. This
                                is a great way to
                                save money on insurance for your entire family.
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>What happens when my membership expires?</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p>
                                When your membership expires, you'll automatically be downgraded to the Basic membership
                                tier unless you
                                renew your plan. We'll send you notifications before your membership expires so you can
                                renew in time to
                                maintain your benefits. Any unused HappiCoins will expire 90 days after your membership
                                ends.
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Is there a refund policy for membership plans?</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p>
                                Yes, we offer a 14-day money-back guarantee on all paid membership plans. If you're not
                                satisfied with your
                                membership for any reason, you can request a full refund within 14 days of purchase.
                                After that period,
                                refunds are not available, but you can still enjoy your membership benefits until the
                                expiration date.
                            </p>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-12 bg-gradient-to-r from-primary/90 to-primary text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">Ready to Upgrade Your Membership?</h2>
                <p class="text-lg mb-8 max-w-2xl mx-auto">
                    Join thousands of satisfied members who enjoy exclusive benefits and savings with HappiInsurance.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Button as-child size="lg" variant="default" class="bg-white text-primary hover:bg-white/90">
                        <Link href="#plans">
                        View Plans
                        </Link>
                    </Button>

                    <Button as-child size="lg" variant="outline" class="border-white text-white hover:bg-white/10">
                        <Link href="/contact">
                        Contact Support
                        </Link>
                    </Button>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
