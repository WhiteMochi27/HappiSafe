// resources/js/Pages/Insurance/Index.vue
<script setup lang="ts">
import { ref, computed } from 'vue';
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
    Input,
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
    Badge
} from '@/components/ui';
import {
    Search,
    Shield,
    ArrowRight,
    Car,
    HeartPulse,
    Home as HomeIcon,
    Plane,
    Filter
} from 'lucide-vue-next';

defineProps<{
    categories: any[];
    products: any[];
}>();

// For demonstration, we'll use hardcoded data
// In production, this would come from the backend
// eslint-disable-next-line vue/no-dupe-keys
const categories = ref([
    { id: 1, name: 'Auto Insurance', icon: Car, slug: 'auto', description: 'Protect your vehicle with comprehensive coverage' },
    { id: 2, name: 'Health Insurance', icon: HeartPulse, slug: 'health', description: 'Take care of your health with our plans' },
    { id: 3, name: 'Home Insurance', icon: HomeIcon, slug: 'home', description: 'Secure your home and belongings' },
    { id: 4, name: 'Travel Insurance', icon: Plane, slug: 'travel', description: 'Travel worry-free with our coverage' },
]);

const allProducts = ref([
    {
        id: 1,
        name: 'Comprehensive Auto Insurance',
        category_id: 1,
        category_name: 'Auto Insurance',
        short_description: 'Full coverage for your vehicle with added benefits',
        base_price: 299,
        happi_coins_reward: 500,
        is_popular: true,
        is_featured: true
    },
    {
        id: 2,
        name: 'Third-Party Auto Insurance',
        category_id: 1,
        category_name: 'Auto Insurance',
        short_description: 'Basic coverage for third-party damages',
        base_price: 150,
        happi_coins_reward: 300,
        is_popular: false,
        is_featured: false
    },
    {
        id: 3,
        name: 'Family Health Plan',
        category_id: 2,
        category_name: 'Health Insurance',
        short_description: 'Complete healthcare coverage for your entire family',
        base_price: 599,
        happi_coins_reward: 1000,
        is_popular: true,
        is_featured: true
    },
    {
        id: 4,
        name: 'Individual Health Plan',
        category_id: 2,
        category_name: 'Health Insurance',
        short_description: 'Healthcare coverage tailored for individuals',
        base_price: 299,
        happi_coins_reward: 500,
        is_popular: false,
        is_featured: false
    },
    {
        id: 5,
        name: 'Home Protection Plus',
        category_id: 3,
        category_name: 'Home Insurance',
        short_description: 'Comprehensive coverage for your home and belongings',
        base_price: 349,
        happi_coins_reward: 700,
        is_popular: true,
        is_featured: true
    },
    {
        id: 6,
        name: 'Travel Security Plan',
        category_id: 4,
        category_name: 'Travel Insurance',
        short_description: 'Complete travel protection for international trips',
        base_price: 99,
        happi_coins_reward: 200,
        is_popular: true,
        is_featured: false
    },
]);

// Filter state
const searchQuery = ref('');
const selectedCategory = ref('all');
const sortBy = ref('popular');
const priceRange = ref('all');

// Filtered products
const filteredProducts = computed(() => {
    return allProducts.value.filter(product => {
        // Search filter
        if (searchQuery.value && !product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) &&
            !product.short_description.toLowerCase().includes(searchQuery.value.toLowerCase())) {
            return false;
        }

        // Category filter
        if (selectedCategory.value !== 'all' && product.category_id !== parseInt(selectedCategory.value)) {
            return false;
        }

        // Price range filter
        if (priceRange.value === 'under_200' && product.base_price >= 200) {
            return false;
        } else if (priceRange.value === '200_to_500' && (product.base_price < 200 || product.base_price > 500)) {
            return false;
        } else if (priceRange.value === 'over_500' && product.base_price <= 500) {
            return false;
        }

        return true;
    }).sort((a, b) => {
        // Sort products
        if (sortBy.value === 'popular') {
            return (b.is_popular ? 1 : 0) - (a.is_popular ? 1 : 0);
        } else if (sortBy.value === 'price_low') {
            return a.base_price - b.base_price;
        } else if (sortBy.value === 'price_high') {
            return b.base_price - a.base_price;
        } else if (sortBy.value === 'rewards') {
            return b.happi_coins_reward - a.happi_coins_reward;
        }

        return 0;
    });
});

// Get category by ID
const getCategoryById = (id: number) => {
    return categories.value.find(cat => cat.id === id);
};

// Get icon component for a category
const getCategoryIcon = (categoryId: number) => {
    const category = getCategoryById(categoryId);
    return category ? category.icon : Shield;
};
</script>

<template>

    <Head title="Insurance Products | HappiInsurance" />

    <AppLayout>
        <!-- Hero Banner -->
        <section class="relative bg-gradient-to-r from-primary/90 to-primary py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl">
                    <h1 class="text-3xl md:text-4xl font-bold mb-4 text-white">
                        Find the Perfect Insurance Plan
                    </h1>
                    <p class="text-lg text-white/90 mb-6">
                        Browse our wide range of insurance products designed to protect what matters most to you.
                        Every purchase earns you HappiCoins!
                    </p>
                    <div class="relative max-w-lg">
                        <Input v-model="searchQuery" type="search" placeholder="Search insurance products..."
                            class="pl-10 py-6 bg-white/95 text-black placeholder:text-neutral-500" />
                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-neutral-500" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Navigation -->
        <section class="bg-white dark:bg-neutral-900 py-6 border-b">
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap gap-4 justify-center">
                    <Button :variant="selectedCategory === 'all' ? 'default' : 'outline'"
                        @click="selectedCategory = 'all'" class="flex items-center">
                        <Shield class="mr-2 h-4 w-4" />
                        All Categories
                    </Button>

                    <Button v-for="category in categories" :key="category.id"
                        :variant="selectedCategory === String(category.id) ? 'default' : 'outline'"
                        @click="selectedCategory = String(category.id)" class="flex items-center">
                        <component :is="category.icon" class="mr-2 h-4 w-4" />
                        {{ category.name }}
                    </Button>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="py-8">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Filters Sidebar -->
                    <div class="w-full md:w-64 shrink-0">
                        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm p-4 sticky top-4">
                            <div class="mb-6">
                                <h3 class="text-lg font-medium mb-3 flex items-center">
                                    <Filter class="mr-2 h-4 w-4" />
                                    Filters
                                </h3>

                                <div class="space-y-4">
                                    <!-- Category Filter -->
                                    <div>
                                        <label class="text-sm font-medium block mb-2">Category</label>
                                        <Select v-model="selectedCategory">
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select category" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="all">All Categories</SelectItem>
                                                <SelectItem v-for="category in categories" :key="category.id"
                                                    :value="String(category.id)">
                                                    {{ category.name }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>

                                    <!-- Price Range Filter -->
                                    <div>
                                        <label class="text-sm font-medium block mb-2">Price Range</label>
                                        <Select v-model="priceRange">
                                            <SelectTrigger>
                                                <SelectValue placeholder="Price range" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="all">All Prices</SelectItem>
                                                <SelectItem value="under_200">Under $200</SelectItem>
                                                <SelectItem value="200_to_500">$200 - $500</SelectItem>
                                                <SelectItem value="over_500">Over $500</SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>

                                    <!-- Sorting -->
                                    <div>
                                        <label class="text-sm font-medium block mb-2">Sort By</label>
                                        <Select v-model="sortBy">
                                            <SelectTrigger>
                                                <SelectValue placeholder="Sort by" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="popular">Most Popular</SelectItem>
                                                <SelectItem value="price_low">Price: Low to High</SelectItem>
                                                <SelectItem value="price_high">Price: High to Low</SelectItem>
                                                <SelectItem value="rewards">Highest Rewards</SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                            </div>

                            <Button variant="outline" class="w-full"
                                @click="searchQuery = ''; selectedCategory = 'all'; sortBy = 'popular'; priceRange = 'all'">
                                Reset Filters
                            </Button>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Insurance Products</h2>
                            <div class="text-sm text-neutral-500">{{ filteredProducts.length }} products</div>
                        </div>

                        <div v-if="filteredProducts.length === 0"
                            class="text-center py-12 bg-white dark:bg-neutral-800 rounded-lg shadow-sm">
                            <p class="text-lg text-neutral-500">No insurance products match your filters.</p>
                            <Button variant="outline" class="mt-4"
                                @click="searchQuery = ''; selectedCategory = 'all'; sortBy = 'popular'; priceRange = 'all'">
                                Reset Filters
                            </Button>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <Card v-for="product in filteredProducts" :key="product.id"
                                class="overflow-hidden flex flex-col transition-shadow hover:shadow-md">
                                <div v-if="product.is_popular" class="absolute right-3 top-3 z-10">
                                    <Badge variant="yellow" class="font-medium">Popular</Badge>
                                </div>

                                <div
                                    class="h-32 bg-gradient-to-r from-primary/80 to-primary flex items-center justify-center">
                                    <component :is="getCategoryIcon(product.category_id)"
                                        class="h-16 w-16 text-white" />
                                </div>

                                <CardHeader>
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <CardTitle>{{ product.name }}</CardTitle>
                                            <CardDescription>{{ product.category_name }}</CardDescription>
                                        </div>
                                    </div>
                                </CardHeader>

                                <CardContent class="flex-grow">
                                    <p class="mb-4">{{ product.short_description }}</p>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="text-2xl font-bold">${{ product.base_price }}</span>
                                            <span class="text-neutral-500">/year</span>
                                        </div>
                                        <div class="flex items-center text-sm text-yellow-500">
                                            <span>Earn</span>
                                            <span class="font-bold mx-1">{{ product.happi_coins_reward }}</span>
                                            <span>HappiCoins</span>
                                        </div>
                                    </div>
                                </CardContent>

                                <CardFooter class="border-t pt-4">
                                    <Button as-child class="w-full">
                                        <Link :href="`/insurance/product/${product.id}`">
                                        View Details
                                        </Link>
                                    </Button>
                                </CardFooter>
                            </Card>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-12 bg-neutral-50 dark:bg-neutral-800">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Can't Find What You're Looking For?</h2>
                <p class="text-lg text-neutral-600 dark:text-neutral-400 mb-6 max-w-2xl mx-auto">
                    Contact our insurance experts for personalized advice and custom insurance solutions.
                </p>
                <Button as-child size="lg">
                    <Link href="/contact">
                    Contact Our Experts
                    </Link>
                </Button>
            </div>
        </section>
    </AppLayout>
</template>
