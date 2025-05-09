// resources/js/Pages/Membership/Checkout.vue
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Card,
    CardHeader,
    CardTitle,
    CardContent,
    CardFooter,
    Button,
    Input,
    Label,
    RadioGroup,
    RadioGroupItem,
    Separator,
    Alert,
    AlertTitle,
    AlertDescription,
} from '@/components/ui';
import {
    CreditCard,
    Check,
    Shield,
    Award,
    Sparkles,
    Crown,
    BadgeCheck,
    ArrowRight
} from 'lucide-vue-next';

// Props would normally include the plan data from the backend
const props = defineProps<{
    plan?: any;
    // tier?: string;
    tier?: keyof typeof tierMap;
}>();

const { auth } = usePage().props as any;
const user = auth.user;

// Plan data (would come from backend in real implementation)
const tierMap = {
    'basic': {
        id: 1,
        name: 'Basic',
        tier: 'basic',
        price: 0,
        happi_coins_reward: 0,
        icon: Shield,
        iconClass: 'text-neutral-500'
    },
    'silver': {
        id: 2,
        name: 'Silver',
        tier: 'silver',
        price: 99,
        happi_coins_reward: 200,
        icon: Award,
        iconClass: 'text-zinc-400'
    },
    'gold': {
        id: 3,
        name: 'Gold',
        tier: 'gold',
        price: 199,
        happi_coins_reward: 500,
        icon: Sparkles,
        iconClass: 'text-yellow-500'
    },
    'platinum': {
        id: 4,
        name: 'Platinum',
        tier: 'platinum',
        price: 349,
        happi_coins_reward: 1000,
        icon: Crown,
        iconClass: 'text-violet-500'
    }
};

// Selected plan
const selectedPlan = computed(() => {
    return props.plan || tierMap[props.tier || 'silver'] || tierMap.silver;
    // return props.plan || tierMap[props.tier ?? 'silver'];
});

// Payment form
const paymentForm = useForm({
    card_number: '',
    card_holder: '',
    expiry_month: '',
    expiry_year: '',
    cvv: '',
    payment_method: 'credit_card',
    promo_code: '',
    plan_id: selectedPlan.value.id
});

// State variables
const isSubmitting = ref(false);
const isSuccess = ref(false);
const isVerifyingPromo = ref(false);
const promoApplied = ref(false);
const promoDiscount = ref(0);

// Computed total price
const totalPrice = computed(() => {
    const basePrice = selectedPlan.value.price;
    const discount = promoApplied.value ? promoDiscount.value : 0;
    return Math.max(0, basePrice - discount);
});

// Apply promo code
const applyPromoCode = () => {
    if (!paymentForm.promo_code) {
        return;
    }

    isVerifyingPromo.value = true;

    // Simulate API call
    setTimeout(() => {
        // For demo, just apply 10% discount for any promo code
        promoDiscount.value = Math.round(selectedPlan.value.price * 0.1);
        promoApplied.value = true;
        isVerifyingPromo.value = false;
    }, 1000);
};

// Submit payment
const submitPayment = () => {
    isSubmitting.value = true;

    // Simulate API call
    setTimeout(() => {
        isSubmitting.value = false;
        isSuccess.value = true;

        // In production, this would submit to your backend
        paymentForm.post(route('membership.purchase'));
    }, 2000);
};

onMounted(() => {
    // Set plan ID when component mounts
    paymentForm.plan_id = selectedPlan.value.id;
});
</script>

<template>

    <Head :title="`Checkout - ${selectedPlan.name} Membership | HappiInsurance`" />

    <AppLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-3xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold">Complete Your Purchase</h1>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        You're just a few steps away from upgrading to {{ selectedPlan.name }} membership.
                    </p>
                </div>

                <!-- Success State -->
                <div v-if="isSuccess" class="bg-white dark:bg-neutral-800 rounded-lg shadow-md p-8 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                            <Check class="h-8 w-8 text-green-500" />
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold mb-2">Payment Successful!</h2>
                    <p class="text-neutral-600 dark:text-neutral-400 mb-6">
                        Thank you for upgrading to {{ selectedPlan.name }} membership. Your account has been updated.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Button as-child>
                            <Link href="/profile">
                            Go to Profile
                            </Link>
                        </Button>
                        <Button as-child variant="outline">
                            <Link href="/insurance">
                            Browse Insurance Plans
                            </Link>
                        </Button>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Payment Form -->
                    <div class="md:col-span-2">
                        <Card>
                            <CardHeader>
                                <CardTitle>Payment Details</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <form @submit.prevent="submitPayment" class="space-y-6">
                                    <!-- Payment Method Selection -->
                                    <div>
                                        <Label class="text-base">Payment Method</Label>
                                        <RadioGroup v-model="paymentForm.payment_method" class="mt-3">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <RadioGroupItem value="credit_card" id="credit_card" />
                                                <Label for="credit_card" class="flex items-center cursor-pointer">
                                                    <CreditCard class="mr-2 h-4 w-4" />
                                                    Credit / Debit Card
                                                </Label>
                                            </div>
                                        </RadioGroup>
                                    </div>

                                    <Separator />

                                    <!-- Credit Card Details -->
                                    <div class="space-y-4">
                                        <div>
                                            <Label for="card_holder">Cardholder Name</Label>
                                            <Input id="card_holder" v-model="paymentForm.card_holder"
                                                placeholder="Name on card" required />
                                        </div>

                                        <div>
                                            <Label for="card_number">Card Number</Label>
                                            <Input id="card_number" v-model="paymentForm.card_number"
                                                placeholder="1234 5678 9012 3456" required maxlength="19" />
                                        </div>

                                        <div class="grid grid-cols-3 gap-4">
                                            <div>
                                                <Label for="expiry_month">Expiry Month</Label>
                                                <Input id="expiry_month" v-model="paymentForm.expiry_month"
                                                    placeholder="MM" required maxlength="2" />
                                            </div>

                                            <div>
                                                <Label for="expiry_year">Expiry Year</Label>
                                                <Input id="expiry_year" v-model="paymentForm.expiry_year"
                                                    placeholder="YY" required maxlength="2" />
                                            </div>

                                            <div>
                                                <Label for="cvv">CVV</Label>
                                                <Input id="cvv" v-model="paymentForm.cvv" placeholder="123" required
                                                    maxlength="4" type="password" />
                                            </div>
                                        </div>
                                    </div>

                                    <Separator />

                                    <!-- Promo Code -->
                                    <div>
                                        <Label for="promo_code">Promo Code (Optional)</Label>
                                        <div class="flex gap-2">
                                            <Input id="promo_code" v-model="paymentForm.promo_code"
                                                placeholder="Enter promo code" :disabled="promoApplied" />
                                            <Button type="button" variant="outline" @click="applyPromoCode"
                                                :disabled="!paymentForm.promo_code || promoApplied || isVerifyingPromo">
                                                {{ isVerifyingPromo ? 'Verifying...' : promoApplied ? 'Applied' :
                                                'Apply' }}
                                            </Button>
                                        </div>
                                        <div v-if="promoApplied" class="mt-2 text-sm text-green-500 flex items-center">
                                            <BadgeCheck class="h-4 w-4 mr-1" />
                                            Promo code applied: ${{ promoDiscount }} discount
                                        </div>
                                    </div>
                                </form>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Order Summary -->
                    <div>
                        <Card>
                            <CardHeader>
                                <CardTitle>Order Summary</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="flex items-center mb-4">
                                    <div class="mr-3">
                                        <component :is="selectedPlan.icon"
                                            :class="`h-8 w-8 ${selectedPlan.iconClass}`" />
                                    </div>
                                    <div>
                                        <h3 class="font-bold">{{ selectedPlan.name }} Membership</h3>
                                        <p class="text-sm text-neutral-500">1 year membership</p>
                                    </div>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between">
                                        <span>Subtotal</span>
                                        <span>${{ selectedPlan.price.toFixed(2) }}</span>
                                    </div>

                                    <div v-if="promoApplied" class="flex justify-between text-green-500">
                                        <span>Promo Discount</span>
                                        <span>-${{ promoDiscount.toFixed(2) }}</span>
                                    </div>
                                </div>

                                <Separator />

                                <div class="flex justify-between my-4 font-bold">
                                    <span>Total</span>
                                    <span>${{ totalPrice.toFixed(2) }}</span>
                                </div>

                                <div class="text-sm text-yellow-500 flex items-center mb-4">
                                    <span>You'll earn</span>
                                    <span class="font-bold mx-1">{{ selectedPlan.happi_coins_reward }}</span>
                                    <span>HappiCoins</span>
                                </div>

                                <Alert class="bg-green-50 dark:bg-green-900/20 border-green-200">
                                    <BadgeCheck class="h-4 w-4 text-green-500" />
                                    <AlertTitle>Secure Checkout</AlertTitle>
                                    <AlertDescription>
                                        Your payment information is encrypted and secure.
                                    </AlertDescription>
                                </Alert>
                            </CardContent>
                            <CardFooter>
                                <Button class="w-full" @click="submitPayment" :disabled="isSubmitting">
                                    <span v-if="isSubmitting">Processing...</span>
                                    <span v-else>Complete Purchase</span>
                                    <ArrowRight v-if="!isSubmitting" class="ml-2 h-4 w-4" />
                                </Button>
                            </CardFooter>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
