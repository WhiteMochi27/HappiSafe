// resources/js/Pages/Vehicles/Index.vue
<script setup lang="ts">
// eslint-disable-next-line @typescript-eslint/no-unused-vars
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
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
    Label,
    Dialog,
    DialogTrigger,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
    Alert,
    AlertTitle,
    AlertDescription,
    Separator,
    Badge
} from '@/components/ui';
import {
    Car,
    Plus,
    Trash,
    Edit,
    Shield,
    Camera,
    Upload,
    X,
    FileText
} from 'lucide-vue-next';

defineProps<{
    vehicles: any[];
}>();

// Sample vehicles data (would come from backend)
// eslint-disable-next-line vue/no-dupe-keys
const vehicles = ref([
    {
        id: 1,
        make: 'Toyota',
        model: 'Camry',
        year: '2020',
        license_plate: 'ABC1234',
        vin: 'JT2BF22K1X0123456',
        color: 'Silver',
        vehicle_card_image_path: null,
        has_insurance: true
    },
    {
        id: 2,
        make: 'Honda',
        model: 'Civic',
        year: '2019',
        license_plate: 'DEF5678',
        vin: 'JHMEJ6674YS012345',
        color: 'Blue',
        vehicle_card_image_path: null,
        has_insurance: false
    }
]);

// Dialogs state
const showAddDialog = ref(false);
const showEditDialog = ref(false);
const showDeleteDialog = ref(false);
const currentVehicle = ref<any>(null);

// Forms
const addVehicleForm = useForm({
    make: '',
    model: '',
    year: '',
    license_plate: '',
    vin: '',
    color: '',
    vehicle_card_image: null as File | null
});

const editVehicleForm = useForm({
    id: 0,
    make: '',
    model: '',
    year: '',
    license_plate: '',
    vin: '',
    color: '',
    vehicle_card_image: null as File | null
});

// Vehicle card image preview
const vehicleCardPreview = ref<string | null>(null);

// Function to handle image selection
const handleImageSelection = (event: Event, form: any) => {
    const fileInput = event.target as HTMLInputElement;

    if (fileInput.files && fileInput.files.length > 0) {
        const file = fileInput.files[0];
        form.vehicle_card_image = file;

        // Create image preview
        const reader = new FileReader();
        reader.onload = (e) => {
            vehicleCardPreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

// Function to remove selected image
const removeImage = (form: any) => {
    form.vehicle_card_image = null;
    vehicleCardPreview.value = null;
};

// Add new vehicle
const addVehicle = () => {
    addVehicleForm.post(route('vehicles.store'), {
        onSuccess: () => {
            addVehicleForm.reset();
            vehicleCardPreview.value = null;
            showAddDialog.value = false;
        }
    });
};

// Edit vehicle
const editVehicle = (vehicle: any) => {
    currentVehicle.value = vehicle;
    editVehicleForm.id = vehicle.id;
    editVehicleForm.make = vehicle.make;
    editVehicleForm.model = vehicle.model;
    editVehicleForm.year = vehicle.year;
    editVehicleForm.license_plate = vehicle.license_plate;
    editVehicleForm.vin = vehicle.vin;
    editVehicleForm.color = vehicle.color;
    editVehicleForm.vehicle_card_image = null;

    vehicleCardPreview.value = vehicle.vehicle_card_image_path;
    showEditDialog.value = true;
};

// Update vehicle
const updateVehicle = () => {
    editVehicleForm.put(route('vehicles.update', { id: editVehicleForm.id }), {
        onSuccess: () => {
            showEditDialog.value = false;
            vehicleCardPreview.value = null;
        }
    });
};

// Delete vehicle
const confirmDelete = (vehicle: any) => {
    currentVehicle.value = vehicle;
    showDeleteDialog.value = true;
};

const deleteVehicle = () => {
    if (!currentVehicle.value) return;

    // In real implementation, this would call to backend API
    // vehicles.value = vehicles.value.filter(v => v.id !== currentVehicle.value.id);

    showDeleteDialog.value = false;
    currentVehicle.value = null;
};

// Check if a vehicle has valid insurance
const hasInsurance = (vehicle: any) => {
    return vehicle.has_insurance;
};
</script>

<template>

    <Head title="My Vehicles | HappiInsurance" />

    <AppLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold">My Vehicles</h1>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Manage your registered vehicles and vehicle insurance policies
                    </p>
                </div>

                <!-- Add Vehicle Button -->
                <Dialog>
                    <DialogTrigger as-child>
                        <Button @click="showAddDialog = true" class="flex items-center">
                            <Plus class="mr-2 h-4 w-4" />
                            Add Vehicle
                        </Button>
                    </DialogTrigger>

                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Add New Vehicle</DialogTitle>
                            <DialogDescription>
                                Add your vehicle details to manage insurance policies
                            </DialogDescription>
                        </DialogHeader>

                        <form @submit.prevent="addVehicle" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <Label for="add-make">Make</Label>
                                    <Input id="add-make" v-model="addVehicleForm.make" required
                                        placeholder="e.g. Toyota" />
                                </div>
                                <div>
                                    <Label for="add-model">Model</Label>
                                    <Input id="add-model" v-model="addVehicleForm.model" required
                                        placeholder="e.g. Camry" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <Label for="add-year">Year</Label>
                                    <Input id="add-year" v-model="addVehicleForm.year" required
                                        placeholder="e.g. 2020" />
                                </div>
                                <div>
                                    <Label for="add-license-plate">License Plate</Label>
                                    <Input id="add-license-plate" v-model="addVehicleForm.license_plate" required
                                        placeholder="e.g. ABC1234" />
                                </div>
                                <div>
                                    <Label for="add-color">Color</Label>
                                    <Input id="add-color" v-model="addVehicleForm.color" placeholder="e.g. Silver" />
                                </div>
                            </div>

                            <div>
                                <Label for="add-vin">VIN (Optional)</Label>
                                <Input id="add-vin" v-model="addVehicleForm.vin"
                                    placeholder="Vehicle Identification Number" />
                            </div>

                            <div>
                                <Label for="add-vehicle-card">Vehicle Card (Optional)</Label>
                                <div class="mt-1">
                                    <div v-if="!vehicleCardPreview"
                                        class="border-2 border-dashed border-neutral-300 dark:border-neutral-700 rounded-md p-4">
                                        <div class="flex justify-center">
                                            <label for="add-vehicle-card-upload"
                                                class="cursor-pointer flex flex-col items-center gap-2 text-center">
                                                <Camera class="h-8 w-8 text-neutral-400" />
                                                <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                                    Upload vehicle card or take a photo
                                                </span>
                                                <span class="text-xs text-neutral-500">
                                                    JPG, PNG or PDF up to 5MB
                                                </span>
                                                <input id="add-vehicle-card-upload" type="file" class="hidden"
                                                    accept="image/jpeg,image/png,application/pdf"
                                                    @change="(e) => handleImageSelection(e, addVehicleForm)" />
                                            </label>
                                        </div>
                                    </div>
                                    <div v-else class="relative mt-2">
                                        <img :src="vehicleCardPreview" alt="Vehicle card preview"
                                            class="max-h-48 mx-auto rounded-md object-contain" />
                                        <button type="button"
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1"
                                            @click="removeImage(addVehicleForm)">
                                            <X class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <Alert class="bg-blue-50 dark:bg-blue-900/20 border-blue-200">
                                <Camera class="h-4 w-4 text-blue-500" />
                                <AlertTitle>Vehicle Card AI Recognition</AlertTitle>
                                <AlertDescription>
                                    Our system can automatically extract vehicle information from your vehicle card.
                                </AlertDescription>
                            </Alert>

                            <DialogFooter>
                                <Button type="button" variant="outline" @click="showAddDialog = false">
                                    Cancel
                                </Button>
                                <Button type="submit" :disabled="addVehicleForm.processing">
                                    {{ addVehicleForm.processing ? 'Adding...' : 'Add Vehicle' }}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>

            <!-- Vehicle Listing -->
            <div v-if="vehicles.length === 0"
                class="text-center py-12 bg-white dark:bg-neutral-800 rounded-lg shadow-sm">
                <Car class="h-16 w-16 text-neutral-300 mx-auto mb-4" />
                <p class="text-lg text-neutral-500 mb-6">You don't have any vehicles registered yet.</p>
                <Button @click="showAddDialog = true" class="flex items-center mx-auto">
                    <Plus class="mr-2 h-4 w-4" />
                    Add Your First Vehicle
                </Button>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <Card v-for="vehicle in vehicles" :key="vehicle.id" class="overflow-hidden">
                    <div class="bg-gradient-to-r from-primary/80 to-primary h-2"></div>

                    <CardHeader class="pb-2">
                        <div class="flex justify-between">
                            <CardTitle>{{ vehicle.make }} {{ vehicle.model }}</CardTitle>
                            <div>
                                <Badge v-if="hasInsurance(vehicle)" variant="success"
                                    class="font-medium flex items-center">
                                    <Shield class="h-3 w-3 mr-1" />
                                    Insured
                                </Badge>
                                <Badge v-else variant="destructive" class="font-medium flex items-center">
                                    <Shield class="h-3 w-3 mr-1" />
                                    Not Insured
                                </Badge>
                            </div>
                        </div>
                        <CardDescription>{{ vehicle.year }}</CardDescription>
                    </CardHeader>

                    <CardContent>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-neutral-500">License Plate</p>
                                <p class="font-medium">{{ vehicle.license_plate }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-neutral-500">Color</p>
                                <p class="font-medium">{{ vehicle.color }}</p>
                            </div>
                        </div>

                        <div v-if="vehicle.vin" class="mb-4">
                            <p class="text-sm text-neutral-500">VIN</p>
                            <p class="font-medium font-mono text-sm">{{ vehicle.vin }}</p>
                        </div>

                        <div v-if="vehicle.vehicle_card_image_path" class="mb-4">
                            <p class="text-sm text-neutral-500 mb-1">Vehicle Card</p>
                            <Button variant="outline" size="sm" class="w-full flex items-center">
                                <FileText class="h-4 w-4 mr-2" />
                                View Vehicle Card
                            </Button>
                        </div>
                    </CardContent>

                    <Separator />

                    <CardFooter class="flex justify-between pt-4">
                        <div class="space-x-2">
                            <Button variant="outline" size="sm" @click="editVehicle(vehicle)">
                                <Edit class="h-4 w-4 mr-1" />
                                Edit
                            </Button>

                            <Button variant="outline" size="sm" class="text-red-500 border-red-200 hover:bg-red-50"
                                @click="confirmDelete(vehicle)">
                                <Trash class="h-4 w-4 mr-1" />
                                Delete
                            </Button>
                        </div>

                        <Button v-if="!hasInsurance(vehicle)" as-child size="sm">
                            <Link :href="`/insurance/auto?vehicle=${vehicle.id}`">
                            <Shield class="h-4 w-4 mr-1" />
                            Get Insurance
                            </Link>
                        </Button>

                        <Button v-else as-child size="sm" variant="outline">
                            <Link :href="`/profile#insurance`">
                            View Policy
                            </Link>
                        </Button>
                    </CardFooter>
                </Card>
            </div>

            <!-- Edit Vehicle Dialog -->
            <Dialog v-model:open="showEditDialog">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Edit Vehicle</DialogTitle>
                        <DialogDescription>
                            Update your vehicle information
                        </DialogDescription>
                    </DialogHeader>

                    <form v-if="currentVehicle" @submit.prevent="updateVehicle" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <Label for="edit-make">Make</Label>
                                <Input id="edit-make" v-model="editVehicleForm.make" required
                                    placeholder="e.g. Toyota" />
                            </div>
                            <div>
                                <Label for="edit-model">Model</Label>
                                <Input id="edit-model" v-model="editVehicleForm.model" required
                                    placeholder="e.g. Camry" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <Label for="edit-year">Year</Label>
                                <Input id="edit-year" v-model="editVehicleForm.year" required placeholder="e.g. 2020" />
                            </div>
                            <div>
                                <Label for="edit-license-plate">License Plate</Label>
                                <Input id="edit-license-plate" v-model="editVehicleForm.license_plate" required
                                    placeholder="e.g. ABC1234" />
                            </div>
                            <div>
                                <Label for="edit-color">Color</Label>
                                <Input id="edit-color" v-model="editVehicleForm.color" placeholder="e.g. Silver" />
                            </div>
                        </div>

                        <div>
                            <Label for="edit-vin">VIN (Optional)</Label>
                            <Input id="edit-vin" v-model="editVehicleForm.vin"
                                placeholder="Vehicle Identification Number" />
                        </div>

                        <div>
                            <Label for="edit-vehicle-card">Vehicle Card (Optional)</Label>
                            <div class="mt-1">
                                <div v-if="!vehicleCardPreview"
                                    class="border-2 border-dashed border-neutral-300 dark:border-neutral-700 rounded-md p-4">
                                    <div class="flex justify-center">
                                        <label for="edit-vehicle-card-upload"
                                            class="cursor-pointer flex flex-col items-center gap-2 text-center">
                                            <Upload class="h-8 w-8 text-neutral-400" />
                                            <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                                Upload vehicle card or take a photo
                                            </span>
                                            <span class="text-xs text-neutral-500">
                                                JPG, PNG or PDF up to 5MB
                                            </span>
                                            <input id="edit-vehicle-card-upload" type="file" class="hidden"
                                                accept="image/jpeg,image/png,application/pdf"
                                                @change="(e) => handleImageSelection(e, editVehicleForm)" />
                                        </label>
                                    </div>
                                </div>
                                <div v-else class="relative mt-2">
                                    <img :src="vehicleCardPreview" alt="Vehicle card preview"
                                        class="max-h-48 mx-auto rounded-md object-contain" />
                                    <button type="button"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1"
                                        @click="removeImage(editVehicleForm)">
                                        <X class="h-4 w-4" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <DialogFooter>
                            <Button type="button" variant="outline" @click="showEditDialog = false">
                                Cancel
                            </Button>
                            <Button type="submit" :disabled="editVehicleForm.processing">
                                {{ editVehicleForm.processing ? 'Updating...' : 'Save Changes' }}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            <!-- Delete Confirmation Dialog -->
            <Dialog v-model:open="showDeleteDialog">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Delete Vehicle</DialogTitle>
                        <DialogDescription>
                            Are you sure you want to delete this vehicle? This action cannot be undone.
                        </DialogDescription>
                    </DialogHeader>

                    <div v-if="currentVehicle" class="bg-neutral-50 dark:bg-neutral-800 p-4 rounded-md mb-4">
                        <p class="font-medium">{{ currentVehicle.make }} {{ currentVehicle.model }}</p>
                        <p class="text-sm text-neutral-500">{{ currentVehicle.year }} - {{ currentVehicle.license_plate
                        }}</p>
                    </div>

                    <Alert v-if="currentVehicle && hasInsurance(currentVehicle)" variant="destructive">
                        <AlertTitle>Warning</AlertTitle>
                        <AlertDescription>
                            This vehicle has an active insurance policy. Deleting this vehicle will not cancel your
                            insurance policy.
                        </AlertDescription>
                    </Alert>

                    <DialogFooter>
                        <Button type="button" variant="outline" @click="showDeleteDialog = false">
                            Cancel
                        </Button>
                        <Button type="button" variant="destructive" @click="deleteVehicle">
                            Delete Vehicle
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
