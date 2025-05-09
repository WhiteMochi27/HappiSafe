// resources/js/Pages/Home.vue
<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
    CardFooter,
    Button,
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselPrevious,
    CarouselNext
} from '@/components/ui';
import { ArrowRight, Shield, Star, Car, Home, HeartPulse, Plane } from 'lucide-vue-next';

// Simulate data that would come from the backend
const insuranceCategories = ref([
    { id: 1, name: 'Auto Insurance', icon: Car, description: 'Protect your vehicle with comprehensive coverage', slug: 'auto' },
    { id: 2, name: 'Health Insurance', icon: HeartPulse, description: 'Take care of your health with our plans', slug: 'health' },
    { id: 3, name: 'Home Insurance', icon: Home, description: 'Secure your home and belongings', slug: 'home' },
    { id: 4, name: 'Travel Insurance', icon: Plane, description: 'Travel worry-free with our coverage', slug: 'travel' },
]);

const featuredProducts = ref([
    {
        id: 1,
        name: 'Comprehensive Auto Insurance',
        description: 'Full coverage for your vehicle with added benefits',
        price: 299,
        happiCoins: 500,
        category: 'Auto Insurance',
        isPopular: true
    },
    {
        id: 2,
        name: 'Family Health Plan',
        description: 'Complete healthcare coverage for your entire family',
        price: 599,
        happiCoins: 1000,
        category: 'Health Insurance',
        isPopular: true
    },
    {
        id: 3,
        name: 'Home Protection Plus',
        description: 'Comprehensive coverage for your home and belongings',
        price: 349,
        happiCoins: 700,
        category: 'Home Insurance',
        isPopular: false
    },
]);

const membershipPlans = ref([
    {
        id: 1,
        name: 'Basic',
        tier: 'basic',
        price: 0,
        benefits: [
            '10% discount on insurance products',
            'Basic customer support',
            'No HappiCoins rewards'
        ],
        cta: 'Free to Join'
    },
    {
        id: 2,
        name: 'Silver',
        tier: 'silver',
        price: 99,
        happiCoins: 200,
        benefits: [
            '15% discount on insurance products',
            'Priority customer support',
            '2x HappiCoins rewards',
            'Access to exclusive deals'
        ],
        cta: 'Join Now'
    },
    {
        id: 3,
        name: 'Gold',
        tier: 'gold',
        price: 199,
        happiCoins: 500,
        benefits: [
            '20% discount on insurance products',
            'Premium customer support',
            '3x HappiCoins rewards',
            'Free policy upgrades',
            'Family discount'
        ],
        cta: 'Upgrade Now',
        featured: true
    },
    {
        id: 4,
        name: 'Platinum',
        tier: 'platinum',
        price: 349,
        happiCoins: 1000,
        benefits: [
            '25% discount on insurance products',
            'VIP customer support',
            '5x HappiCoins rewards',
            'Free policy upgrades',
            'Extended family discount',
            'Annual health check-up'
        ],
        cta: 'Go Premium'
    }
]);

const testimonials = ref([
    {
        id: 1,
        name: 'Sarah L.',
        comment: 'HappiInsurance made it so easy to find the right plan for my family. The Gold membership benefits are fantastic!',
        rating: 5
    },
    {
        id: 2,
        name: 'James T.',
        comment: 'I saved over $500 on my auto insurance and earned HappiCoins that I used for policy upgrades. Highly recommend!',
        rating: 5
    },
    {
        id: 3,
        name: 'Michelle K.',
        comment: 'The customer service is outstanding. They helped me file a claim and everything was handled smoothly.',
        rating: 4
    },
    {
        id: 4,
        name: 'David R.',
        comment: 'The family group feature allows me to manage all my family\'s policies in one place. So convenient!',
        rating: 5
    },
]);

const highlights = ref([
    {
        title: 'Fast Claims Processing',
        description: 'Our streamlined process ensures quick resolution of your claims'
    },
    {
        title: 'Flexible Payment Options',
        description: 'Pay monthly, quarterly, or annually - whatever works for you'
    },
    {
        title: 'Family Group Discounts',
        description: 'Add family members to save more on all your policies'
    },
    {
        title: 'Reward System',
        description: 'Earn HappiCoins on every purchase to redeem for discounts'
    },
]);
</script>

<template>

    <Head title="HappiInsurance - Insurance Made Simple" />

    <AppLayout>
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-r from-primary/90 to-primary py-20 md:py-28">
            <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0 text-white">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4">
                        Insurance Made Simple & Rewarding
                    </h1>
                    <p class="text-lg md:text-xl mb-8 text-white/90">
                        Protect what matters most with our comprehensive insurance plans.
                        Join our membership program and earn rewards with every policy.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <Button as-child size="lg" variant="default" class="bg-white text-primary hover:bg-white/90">
                            <Link href="/insurance">
                            Explore Insurance Plans
                            </Link>
                        </Button>
                        <Button as-child size="lg" variant="outline" class="border-white text-white hover:bg-white/10">
                            <Link href="/membership">
                            Join Membership
                            </Link>
                        </Button>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <img src="/images/hero-image.svg" alt="Insurance" class="w-full max-w-md" />
                </div>
            </div>
        </section>

        <!-- Insurance Categories -->
        <section class="py-16 bg-white dark:bg-neutral-900">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Insurance Categories</h2>
                    <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                        Explore our range of insurance products designed to protect what matters most to you.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Card v-for="category in insuranceCategories" :key="category.id"
                        class="hover:shadow-md transition-shadow">
                        <CardHeader>
                            <div
                                class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-4">
                                <component :is="category.icon" class="h-6 w-6" />
                            </div>
                            <CardTitle>{{ category.name }}</CardTitle>
                            <CardDescription>{{ category.description }}</CardDescription>
                        </CardHeader>
                        <CardFooter>
                            <Button as-child variant="ghost" class="w-full">
                                <Link :href="`/insurance/${category.slug}`"
                                    class="flex items-center justify-between w-full">
                                Learn More
                                <ArrowRight class="h-4 w-4 ml-2" />
                                </Link>
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Featured Insurance Products -->
        <section class="py-16 bg-neutral-50 dark:bg-neutral-800">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Featured Insurance Products</h2>
                    <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                        Our most popular insurance plans with exclusive benefits and rewards.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <Card v-for="product in featuredProducts" :key="product.id" class="relative overflow-hidden">
                        <div v-if="product.isPopular"
                            class="absolute top-0 right-0 bg-yellow-500 text-white text-xs font-bold px-3 py-1 transform translate-x-2 -translate-y-0 rotate-45 transform-origin-bottom-left">
                            Popular
                        </div>
                        <CardHeader>
                            <CardTitle>{{ product.name }}</CardTitle>
                            <CardDescription>{{ product.category }}</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <p class="mb-4">{{ product.description }}</p>
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <span class="text-2xl font-bold">${{ product.price }}</span>
                                    <span class="text-neutral-500">/year</span>
                                </div>
                                <div class="flex items-center text-sm text-yellow-500">
                                    <span>Earn</span>
                                    <span class="font-bold mx-1">{{ product.happiCoins }}</span>
                                    <span>HappiCoins</span>
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Button as-child class="w-full">
                                <Link :href="`/insurance/product/${product.id}`">
                                View Details
                                </Link>
                            </Button>
                        </CardFooter>
                    </Card>
                </div>

                <div class="mt-10 text-center">
                    <Button as-child variant="outline">
                        <Link href="/insurance" class="flex items-center">
                        View All Insurance Products
                        <ArrowRight class="ml-2 h-4 w-4" />
                        </Link>
                    </Button>
                </div>
            </div>
        </section>

        <!-- Membership Plans -->
        <section class="py-16 bg-white dark:bg-neutral-900">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">HappiMembership Plans</h2>
                    <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                        Join our membership program to unlock exclusive benefits and rewards.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Card v-for="plan in membershipPlans" :key="plan.id" class="relative flex flex-col"
                        :class="{ 'border-primary shadow-md': plan.featured }">
                        <div v-if="plan.featured"
                            class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-3 bg-primary text-white text-xs font-bold px-3 py-1 rounded-full">
                            Most Popular
                        </div>
                        <CardHeader>
                            <CardTitle>{{ plan.name }}</CardTitle>
                            <CardDescription>
                                <div class="flex items-end gap-1">
                                    <span class="text-2xl font-bold">${{ plan.price }}</span>
                                    <span class="text-sm text-neutral-500">/year</span>
                                </div>
                                <div v-if="plan.happiCoins" class="mt-2 text-sm text-yellow-500">
                                    Earn {{ plan.happiCoins }} HappiCoins
                                </div>
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="flex-grow">
                            <ul class="space-y-2">
                                <li v-for="(benefit, index) in plan.benefits" :key="index" class="flex items-start">
                                    <Shield class="h-5 w-5 mr-2 text-primary shrink-0" />
                                    <span>{{ benefit }}</span>
                                </li>
                            </ul>
                        </CardContent>
                        <CardFooter>
                            <Button as-child :variant="plan.featured ? 'default' : 'outline'" class="w-full">
                                <Link :href="`/membership/${plan.tier}`">
                                {{ plan.cta }}
                                </Link>
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-16 bg-neutral-50 dark:bg-neutral-800">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">What Our Customers Say</h2>
                    <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                        Hear from our satisfied customers about their experience with HappiInsurance.
                    </p>
                </div>

                <Carousel class="w-full max-w-4xl mx-auto">
                    <CarouselContent>
                        <CarouselItem v-for="testimonial in testimonials" :key="testimonial.id" class="md:basis-1/2">
                            <div class="p-4 h-full">
                                <Card class="h-full flex flex-col">
                                    <CardContent class="pt-6 flex-grow">
                                        <div class="flex items-center mb-4">
                                            <div v-for="i in 5" :key="i" class="text-yellow-400">
                                                <Star :class="{ 'text-neutral-300': i > testimonial.rating }"
                                                    class="h-5 w-5 fill-current" />
                                            </div>
                                        </div>
                                        <p class="italic">{{ testimonial.comment }}</p>
                                    </CardContent>
                                    <CardFooter>
                                        <div class="font-medium">{{ testimonial.name }}</div>
                                    </CardFooter>
                                </Card>
                            </div>
                        </CarouselItem>
                    </CarouselContent>
                    <CarouselPrevious />
                    <CarouselNext />
                </Carousel>
            </div>
        </section>

        <!-- Highlights -->
        <section class="py-16 bg-white dark:bg-neutral-900">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Why Choose HappiInsurance?</h2>
                    <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                        We're committed to providing the best insurance experience for our customers.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="(highlight, index) in highlights" :key="index" class="text-center">
                        <div
                            class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto mb-4">
                            <Shield class="h-8 w-8" />
                        </div>
                        <h3 class="text-xl font-medium mb-2">{{ highlight.title }}</h3>
                        <p class="text-neutral-600 dark:text-neutral-400">{{ highlight.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-primary text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
                <p class="text-lg mb-8 max-w-2xl mx-auto">
                    Join thousands of satisfied customers who trust HappiInsurance for their protection needs.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Button as-child size="lg" variant="default" class="bg-white text-primary hover:bg-white/90">
                        <Link href="/register">
                        Create Account
                        </Link>
                    </Button>
                    <Button as-child size="lg" variant="outline" class="border-white text-white hover:bg-white/10">
                        <Link href="/contact">
                        Contact Us
                        </Link>
                    </Button>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
