// resources/js/Pages/Family/Index.vue
<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
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
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
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
    Avatar,
    AvatarFallback,
    AvatarImage,
    Badge,
    Separator,
    Tabs,
    TabsList,
    TabsTrigger,
    TabsContent
} from '@/components/ui';
import {
    Users,
    UserPlus,
    Mail,
    Copy,
    Check,
    Shield,
    MailCheck,
    X,
    Trash2,
    Clock
} from 'lucide-vue-next';

// Get the authenticated user
const { auth } = usePage().props as any;
const user = auth.user;

defineProps<{
    familyGroup?: any;
    members?: any[];
    invitations?: any[];
}>();

// For demonstration, we'll use hardcoded data
// In production, this would come from the backend
// eslint-disable-next-line vue/no-dupe-keys
const familyGroup = ref({
    id: 1,
    name: 'Doe Family',
    owner_id: 1,
    created_at: '2023-01-15',
    memberCount: 3
});

// eslint-disable-next-line vue/no-dupe-keys
const members = ref([
    {
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        relationship: 'self',
        is_owner: true
    },
    {
        id: 2,
        name: 'Jane Doe',
        email: 'jane@example.com',
        relationship: 'spouse',
        is_owner: false
    },
    {
        id: 3,
        name: 'Jake Doe',
        email: 'jake@example.com',
        relationship: 'child',
        is_owner: false
    }
]);

// eslint-disable-next-line vue/no-dupe-keys
const invitations = ref([
    {
        id: 1,
        email: 'sarah@example.com',
        status: 'pending',
        expires_at: '2024-06-30'
    }
]);

// State variables
const showCreateFamilyDialog = ref(false);
const showInviteMemberDialog = ref(false);
const showEditMemberDialog = ref(false);
const showRemoveMemberDialog = ref(false);
const showCancelInvitationDialog = ref(false);
const currentMember = ref<any>(null);
const currentInvitation = ref<any>(null);
const activeTab = ref('members');

// Forms
const createFamilyForm = useForm({
    name: ''
});

const inviteMemberForm = useForm({
    email: '',
    relationship: ''
});

const editMemberForm = useForm({
    id: 0,
    relationship: ''
});

// Check if there's a family group
const hasFamilyGroup = computed(() => {
    return !!familyGroup.value;
});

// Check if user is the owner of the family group
const isOwner = computed(() => {
    return familyGroup.value && familyGroup.value.owner_id === user?.id;
});

// Create family group
const createFamilyGroup = () => {
    createFamilyForm.post(route('family.store'), {
        onSuccess: () => {
            createFamilyForm.reset();
            showCreateFamilyDialog.value = false;
            // In a real app, this would reload the page or update the state
        }
    });
};

// Invite family member
const inviteMember = () => {
    inviteMemberForm.post(route('family.invite'), {
        onSuccess: () => {
            inviteMemberForm.reset();
            showInviteMemberDialog.value = false;
            // In a real app, this would reload the page or update the state
        }
    });
};

// Edit member
const editMember = (member: any) => {
    currentMember.value = member;
    editMemberForm.id = member.id;
    editMemberForm.relationship = member.relationship;
    showEditMemberDialog.value = true;
};

// Update member
const updateMember = () => {
    editMemberForm.put(route('family.members.update', { id: editMemberForm.id }), {
        onSuccess: () => {
            showEditMemberDialog.value = false;
            // In a real app, this would reload the page or update the state
        }
    });
};

// Confirm remove member
const confirmRemoveMember = (member: any) => {
    currentMember.value = member;
    showRemoveMemberDialog.value = true;
};

// Remove member
const removeMember = () => {
    if (!currentMember.value) return;

    // In a real implementation, this would call to backend API
    // members.value = members.value.filter(m => m.id !== currentMember.value.id);

    showRemoveMemberDialog.value = false;
    currentMember.value = null;
};

// Confirm cancel invitation
const confirmCancelInvitation = (invitation: any) => {
    currentInvitation.value = invitation;
    showCancelInvitationDialog.value = true;
};

// Cancel invitation
const cancelInvitation = () => {
    if (!currentInvitation.value) return;

    // In a real implementation, this would call to backend API
    // invitations.value = invitations.value.filter(i => i.id !== currentInvitation.value.id);

    showCancelInvitationDialog.value = false;
    currentInvitation.value = null;
};

// Copy invitation link
const copyInvitationLink = (invitationId: number) => {
    const link = `https://happiinsurance.com/family/join/${invitationId}`;
    navigator.clipboard.writeText(link);
    // Could add a toast notification here
};

// Generate initials for avatar
const getInitials = (name: string) => {
    if (!name) return '';

    const nameParts = name.split(' ');
    if (nameParts.length === 1) {
        return nameParts[0].charAt(0).toUpperCase();
    }

    return (nameParts[0].charAt(0) + nameParts[nameParts.length - 1].charAt(0)).toUpperCase();
};
</script>

<template>

    <Head title="Family Group | HappiInsurance" />

    <AppLayout>
        <div class="container mx-auto px-4 py-8">
            <!-- No Family Group State -->
            <div v-if="!hasFamilyGroup" class="max-w-2xl mx-auto">
                <div class="text-center mb-8">
                    <Users class="h-16 w-16 text-primary mx-auto mb-4" />
                    <h1 class="text-3xl font-bold mb-2">Create a Family Group</h1>
                    <p class="text-neutral-600 dark:text-neutral-400 mb-6">
                        Create a family group to invite your family members and enjoy shared benefits on insurance
                        plans.
                    </p>

                    <Button @click="showCreateFamilyDialog = true">Create Family Group</Button>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Family Group Benefits</CardTitle>
                        <CardDescription>
                            Why you should create a family group
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <Shield class="h-5 w-5 text-primary mr-2 shrink-0 mt-0.5" />
                                <div>
                                    <h3 class="font-medium">Family Discounts</h3>
                                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                        Gold and Platinum members get special discounts on insurance policies for family
                                        members.
                                    </p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <Shield class="h-5 w-5 text-primary mr-2 shrink-0 mt-0.5" />
                                <div>
                                    <h3 class="font-medium">Manage Family Policies</h3>
                                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                        Easily manage and view insurance policies for all family members in one place.
                                    </p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <Shield class="h-5 w-5 text-primary mr-2 shrink-0 mt-0.5" />
                                <div>
                                    <h3 class="font-medium">Family Health Plans</h3>
                                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                        Access to special family health insurance plans with better coverage and lower
                                        premiums.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
            </div>

            <!-- Family Group Dashboard -->
            <div v-else>
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold">{{ familyGroup.name }}</h1>
                        <p class="text-neutral-600 dark:text-neutral-400">
                            Manage your family group members and invitations
                        </p>
                    </div>

                    <div class="mt-4 md:mt-0" v-if="isOwner">
                        <Button @click="showInviteMemberDialog = true" class="flex items-center">
                            <UserPlus class="mr-2 h-4 w-4" />
                            Invite Member
                        </Button>
                    </div>
                </div>

                <Tabs v-model="activeTab" class="space-y-6">
                    <TabsList>
                        <TabsTrigger value="members" class="flex items-center">
                            <Users class="mr-2 h-4 w-4" />
                            Members <Badge class="ml-2" variant="secondary">{{ members.length }}</Badge>
                        </TabsTrigger>
                        <TabsTrigger value="invitations" class="flex items-center">
                            <Mail class="mr-2 h-4 w-4" />
                            Invitations <Badge class="ml-2" variant="secondary">{{ invitations.length }}</Badge>
                        </TabsTrigger>
                    </TabsList>

                    <!-- Members Tab -->
                    <TabsContent value="members">
                        <Card>
                            <CardHeader>
                                <CardTitle>Family Members</CardTitle>
                                <CardDescription>
                                    Members of your family group
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="member in members" :key="member.id"
                                        class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                                        <div class="flex items-center mb-3 sm:mb-0">
                                            <Avatar class="h-10 w-10 mr-3">
                                                <AvatarImage v-if="member.avatar" :src="member.avatar"
                                                    :alt="member.name" />
                                                <AvatarFallback class="bg-primary/10 text-primary">
                                                    {{ getInitials(member.name) }}
                                                </AvatarFallback>
                                            </Avatar>
                                            <div>
                                                <div class="font-medium flex items-center">
                                                    {{ member.name }}
                                                    <Badge v-if="member.is_owner" variant="secondary"
                                                        class="ml-2 text-xs">Owner</Badge>
                                                </div>
                                                <div class="text-sm text-neutral-500">{{ member.email }}</div>
                                                <div class="text-xs text-neutral-500 capitalize">{{ member.relationship
                                                    }}</div>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-2" v-if="isOwner && !member.is_owner">
                                            <Button variant="ghost" size="sm" @click="editMember(member)">
                                                Edit
                                            </Button>
                                            <Button variant="ghost" size="sm"
                                                class="text-red-500 hover:text-red-600 hover:bg-red-50"
                                                @click="confirmRemoveMember(member)">
                                                Remove
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <!-- Invitations Tab -->
                    <TabsContent value="invitations">
                        <Card>
                            <CardHeader>
                                <CardTitle>Pending Invitations</CardTitle>
                                <CardDescription>
                                    Invitations sent to family members
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div v-if="invitations.length === 0" class="text-center py-8">
                                    <MailCheck class="h-12 w-12 text-neutral-300 mx-auto mb-2" />
                                    <p class="text-neutral-500">No pending invitations</p>
                                    <Button v-if="isOwner" @click="showInviteMemberDialog = true" variant="outline"
                                        class="mt-4">
                                        <UserPlus class="mr-2 h-4 w-4" />
                                        Invite Member
                                    </Button>
                                </div>

                                <div v-else class="space-y-4">
                                    <div v-for="invitation in invitations" :key="invitation.id"
                                        class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                                        <div class="mb-3 sm:mb-0">
                                            <div class="font-medium">{{ invitation.email }}</div>
                                            <div class="text-xs text-neutral-500 flex items-center">
                                                <Clock class="h-3 w-3 mr-1 inline" />
                                                Expires: {{ new Date(invitation.expires_at).toLocaleDateString() }}
                                            </div>
                                            <Badge variant="yellow" class="mt-1">{{ invitation.status }}</Badge>
                                        </div>

                                        <div class="flex items-center space-x-2" v-if="isOwner">
                                            <Button variant="ghost" size="sm" @click="copyInvitationLink(invitation.id)"
                                                class="flex items-center">
                                                <Copy class="h-4 w-4 mr-1" />
                                                Copy Link
                                            </Button>
                                            <Button variant="ghost" size="sm"
                                                class="text-red-500 hover:text-red-600 hover:bg-red-50"
                                                @click="confirmCancelInvitation(invitation)">
                                                Cancel
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>
                </Tabs>
            </div>

            <!-- Create Family Group Dialog -->
            <Dialog v-model:open="showCreateFamilyDialog">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Create Family Group</DialogTitle>
                        <DialogDescription>
                            Create a family group to invite and manage your family members
                        </DialogDescription>
                    </DialogHeader>

                    <form @submit.prevent="createFamilyGroup" class="space-y-4">
                        <div>
                            <Label for="family-name">Family Group Name</Label>
                            <Input id="family-name" v-model="createFamilyForm.name" required
                                placeholder="e.g. Smith Family" autofocus />
                        </div>

                        <Alert class="bg-blue-50 dark:bg-blue-900/20 border-blue-200">
                            <Shield class="h-4 w-4 text-blue-500" />
                            <AlertTitle>Family Benefits</AlertTitle>
                            <AlertDescription>
                                Gold and Platinum members get special discounts on insurance plans for their family
                                members.
                            </AlertDescription>
                        </Alert>

                        <DialogFooter>
                            <Button type="button" variant="outline" @click="showCreateFamilyDialog = false">
                                Cancel
                            </Button>
                            <Button type="submit" :disabled="createFamilyForm.processing">
                                {{ createFamilyForm.processing ? 'Creating...' : 'Create Family Group' }}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            <!-- Invite Family Member Dialog -->
            <Dialog v-model:open="showInviteMemberDialog">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Invite Family Member</DialogTitle>
                        <DialogDescription>
                            Send an invitation to a family member to join your family group
                        </DialogDescription>
                    </DialogHeader>

                    <form @submit.prevent="inviteMember" class="space-y-4">
                        <div>
                            <Label for="member-email">Email Address</Label>
                            <Input id="member-email" v-model="inviteMemberForm.email" type="email" required
                                placeholder="email@example.com" autofocus />
                        </div>

                        <div>
                            <Label for="member-relationship">Relationship</Label>
                            <Select v-model="inviteMemberForm.relationship" required>
                                <SelectTrigger id="member-relationship">
                                    <SelectValue placeholder="Select relationship" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="spouse">Spouse</SelectItem>
                                    <SelectItem value="child">Child</SelectItem>
                                    <SelectItem value="parent">Parent</SelectItem>
                                    <SelectItem value="sibling">Sibling</SelectItem>
                                    <SelectItem value="other">Other</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <Alert class="bg-neutral-50 dark:bg-neutral-800 border-neutral-200">
                            <Mail class="h-4 w-4 text-neutral-500" />
                            <AlertTitle>Invitation Link</AlertTitle>
                            <AlertDescription>
                                We'll send an email with an invitation link that will be valid for 7 days.
                            </AlertDescription>
                        </Alert>

                        <DialogFooter>
                            <Button type="button" variant="outline" @click="showInviteMemberDialog = false">
                                Cancel
                            </Button>
                            <Button type="submit" :disabled="inviteMemberForm.processing">
                                {{ inviteMemberForm.processing ? 'Sending...' : 'Send Invitation' }}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            <!-- Edit Member Dialog -->
            <Dialog v-model:open="showEditMemberDialog">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Edit Member</DialogTitle>
                        <DialogDescription>
                            Update member relationship
                        </DialogDescription>
                    </DialogHeader>

                    <form v-if="currentMember" @submit.prevent="updateMember" class="space-y-4">
                        <div class="flex items-center mb-4">
                            <Avatar class="h-10 w-10 mr-3">
                                <AvatarFallback class="bg-primary/10 text-primary">
                                    {{ getInitials(currentMember.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div>
                                <div class="font-medium">{{ currentMember.name }}</div>
                                <div class="text-sm text-neutral-500">{{ currentMember.email }}</div>
                            </div>
                        </div>

                        <div>
                            <Label for="edit-relationship">Relationship</Label>
                            <Select v-model="editMemberForm.relationship" required>
                                <SelectTrigger id="edit-relationship">
                                    <SelectValue placeholder="Select relationship" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="spouse">Spouse</SelectItem>
                                    <SelectItem value="child">Child</SelectItem>
                                    <SelectItem value="parent">Parent</SelectItem>
                                    <SelectItem value="sibling">Sibling</SelectItem>
                                    <SelectItem value="other">Other</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <DialogFooter>
                            <Button type="button" variant="outline" @click="showEditMemberDialog = false">
                                Cancel
                            </Button>
                            <Button type="submit" :disabled="editMemberForm.processing">
                                {{ editMemberForm.processing ? 'Saving...' : 'Save Changes' }}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            <!-- Remove Member Confirmation Dialog -->
            <Dialog v-model:open="showRemoveMemberDialog">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Remove Member</DialogTitle>
                        <DialogDescription>
                            Are you sure you want to remove this member from your family group?
                        </DialogDescription>
                    </DialogHeader>

                    <div v-if="currentMember" class="space-y-4">
                        <div class="flex items-center p-4 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                            <Avatar class="h-10 w-10 mr-3">
                                <AvatarFallback class="bg-primary/10 text-primary">
                                    {{ getInitials(currentMember.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div>
                                <div class="font-medium">{{ currentMember.name }}</div>
                                <div class="text-sm text-neutral-500">{{ currentMember.email }}</div>
                                <div class="text-xs text-neutral-500 capitalize">{{ currentMember.relationship }}</div>
                            </div>
                        </div>

                        <Alert variant="destructive">
                            <AlertTitle>Warning</AlertTitle>
                            <AlertDescription>
                                Removing this member will revoke their access to family benefits. This action cannot be
                                undone.
                            </AlertDescription>
                        </Alert>
                    </div>

                    <DialogFooter>
                        <Button type="button" variant="outline" @click="showRemoveMemberDialog = false">
                            Cancel
                        </Button>
                        <Button type="button" variant="destructive" @click="removeMember">
                            Remove Member
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <!-- Cancel Invitation Confirmation Dialog -->
            <Dialog v-model:open="showCancelInvitationDialog">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Cancel Invitation</DialogTitle>
                        <DialogDescription>
                            Are you sure you want to cancel this invitation?
                        </DialogDescription>
                    </DialogHeader>

                    <div v-if="currentInvitation" class="space-y-4">
                        <div class="p-4 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                            <div class="font-medium">{{ currentInvitation.email }}</div>
                            <div class="text-xs text-neutral-500 flex items-center">
                                <Clock class="h-3 w-3 mr-1 inline" />
                                Expires: {{ new Date(currentInvitation.expires_at).toLocaleDateString() }}
                            </div>
                            <Badge variant="yellow" class="mt-1">{{ currentInvitation.status }}</Badge>
                        </div>

                        <Alert>
                            <X class="h-4 w-4" />
                            <AlertTitle>Invitation will be canceled</AlertTitle>
                            <AlertDescription>
                                The invitation link will be invalidated and the person will not be able to join using
                                this invitation.
                            </AlertDescription>
                        </Alert>
                    </div>

                    <DialogFooter>
                        <Button type="button" variant="outline" @click="showCancelInvitationDialog = false">
                            Keep Invitation
                        </Button>
                        <Button type="button" variant="destructive" @click="cancelInvitation">
                            Cancel Invitation
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
