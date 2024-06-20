<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,useForm } from '@inertiajs/vue3';
import { ref,Ref, onMounted,onUnmounted } from 'vue';
import TextInputx from '@/Components/TextInputx.vue';
import InputLabelx from '@/Components/InputLabelx.vue';
import PrimaryButtonx from '@/Components/PrimaryButtonx.vue';
import DeleteConfirmationModal from '@/Components/DeleteConfirmatiomModal.vue'


// Props
const props = defineProps({
    visitors: Array<Data>
});

//Delete one confirm
const deleteConfirmationMessage = ref("Are you sure you want to delete this Visitor(s)?");
const deleteConfirmButtonText = ref("Yes, I'm sure");
const deleteCancelButtonText = ref("No, cancel");

const showModal = ref<boolean>(false);
const editMode = ref<boolean>(false);
const deleteModal = ref<boolean>(false);
const currentId = ref<string>('');

interface Data {
    id: number,
    full_name: string;
    email: string;
    company: string;
    phone_number: string;
}
const form = useForm<Data>({
    id: 0,
    full_name: '',
    email: '',
    phone_number: '',
    company: '',
});
const deleteData = () => {
    const id = currentId.value;
    form.delete(route('visitors.destroy', { visitor: id }), {
        // onFinish: () => fetchVisitors(),
        onFinish: () => {
            deleteModal.value = false;

        },
    });
};

const getId = (visitor:Data) => {
    console.log(visitor.id);
    currentId.value = String(visitor.id);
    deleteModal.value = true;
};


const closedeltemodal = () => {
    deleteModal.value = false;
};
// Inertia.delete(route('visitors.destroy', { visitor: id }), {
//     onFinish: () => fetchVisitors(),
// });

const submit = () => {
    form.post(route('visitors.store'), {
        onFinish: () => {
            // form.reset('full_name', 'email','phone','company');
            // formreset();

        },
    });
    showModal.value = false;
};

const updateData = () => {
    console.log('Starting')
    console.log(form.id);
    form.put(route('visitors.update', { visitor: form.id }), {
        onFinish: () => {
            form.reset('full_name', 'email', 'phone_number', 'company');
            showModal.value = false;

        },
    })

};

// const deleteModal = (visitor)=>{
//
// }
const editModal = (visitor:Data) => {
    // isOpen.value = false;
    showModal.value = false;
    formreset();
    editMode.value = true;

    form.id = visitor.id;
    form.full_name = visitor.full_name;
    form.phone_number = visitor.phone_number;
    form.email = visitor.email;
    form.company = visitor.company;

    showModal.value = true;
};

const formreset = () => {
    form.full_name = '';
    form.phone_number = '';
    form.email = '';
    form.company = '';
}
const newModal = () => {
    console.log('i hit ')
    showModal.value = false;
    // editMode.value = false;
    // form.reset();
    formreset();
    // form.value.fill(port_code);
    showModal.value = true;
};

const closeModal = () =>{
    showModal.value = false;
    editMode.value = false;
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
<!--                    <div class="p-6 text-gray-900">You're logged in ss!</div>-->

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <div class="flex flex-wrap items-center justify-between pb-4 space-y-4 bg-white flex-column md:flex-row md:space-y-0">
                            <div class="mt-4 ml-5">
<!--                                <button type="button" class="inline-flex items-center px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg  pl- 4 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">-->
<!--                                   <div class="pr-1">Add</div>-->
<!--                                    <svg class="w-3 h-3 text-white  me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">-->
<!--                                        <path d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z"/>-->
<!--                                        <path d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z"/>-->
<!--                                    </svg>-->

<!--                                </button>-->
                                <button @click="newModal" data-modal-target="static-modal" data-modal-toggle="static-modal" class="inline-flex items-center px-3 py-2 pl-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg  hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300" type="button">
                                    <div class="pr-1">Add</div>
                              <svg class="w-3 h-3 mb-1 text-white  me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                  <path d="M11 11V5H13V11H19V13H13V19H11V13H5V11H11Z"></path>
                                   </svg>
                                </button>

<!--                                ShowModal-->
                                <div v-show="showModal"  class="fixed inset-0 bg-black bg-opacity-50"></div>
                                <div :class="{ 'hidden': !showModal }" id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class=" flex  overflow-y-auto overflow-x-hidden fixed top-1/2 right-1/2 left-1/2 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full p-4">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow ">
                                            <!-- Modal header -->
                                            <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5 ">
                                                <h3 class="text-xl font-semibold text-gray-900 " v-if="!editMode" >
                                                 Add Visitor
                                                </h3>
                                                <h3 class="text-xl font-semibold text-gray-900 " v-if="editMode">
                                                    Edit Visitor
                                                </h3>
                                                <button @click="closeModal" type="button" class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 ms-auto " data-modal-hide="static-modal">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="p-4 space-y-4 md:p-5">
                                                <form   class="max-w-sm mx-auto">
<!--                                                    <form @submit.prevent="submit"   class="max-w-sm mx-auto">-->
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="Full Name" />
                                                        <TextInputx
                                                            id="name"
                                                            type="text"
                                                            v-model="form.full_name"
                                                            required
                                                            autofocus
                                                            placeholder="Omoboriola Chukwudi Danjuma"
                                                            autocomplete="off"
                                                        />
                                                           </div>
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="Email" />
                                                        <TextInputx
                                                            id="email"
                                                            type="email"
                                                            v-model="form.email"
                                                            required
                                                            autofocus
                                                            placeholder="example@example.com"
                                                            autocomplete="off"
                                                        />
                                                    </div>
                                                        <div class="mb-5">
                                                            <InputLabelx for="name" value="Phone Number" />
                                                            <TextInputx
                                                                id="phone"
                                                                type="text"
                                                                v-model="form.phone_number"
                                                                required
                                                                autofocus
                                                                placeholder="07000000000"
                                                                autocomplete="off"
                                                            />
                                                        </div>

                                                        <div class="mb-5">
                                                            <InputLabelx for="name" value="Company" />
                                                            <TextInputx
                                                                id="company"
                                                                type="text"
                                                                v-model="form.company"
                                                                required
                                                                autofocus
                                                                placeholder="XYZ Limited"
                                                                autocomplete="off"
                                                            />
                                                        </div>



                                                </form>
                                            </div>
                                            <!-- Modal footer -->
                                            <div class="flex items-center justify-center p-4 border-t border-gray-200 rounded-b md:p-5 ">
                                                <PrimaryButtonx @click="submit" v-if="!editMode" class="text-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                    Create
                                                </PrimaryButtonx>
                                                <PrimaryButtonx @click="updateData" v-show="editMode" class="text-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                    Update
                                                </PrimaryButtonx>
                                                 <button @click="closeModal"  type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <!-- Reusable Delete Confirmation Modal -->
                                    <delete-confirmation-modal
                                        :show-modal="deleteModal"
                                        :message="deleteConfirmationMessage"
                                        :confirm-button-text="deleteConfirmButtonText"
                                        :cancel-button-text="deleteCancelButtonText"
                                        :confirm-action="deleteData"
                                        :close-modal="closedeltemodal"
                                    />
                                </div>



<!--                                <button id="dropdownActionButton" data-dropdown-toggle="dropdownAction" class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5" type="button">-->
<!--                                    <span class="sr-only">Action button</span>-->
<!--                                    Action-->
<!--                                    <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">-->
<!--                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>-->
<!--                                    </svg>-->
<!--                                </button>-->
                                <!-- Dropdown menu -->
                            </div>
                            <label for="table-search" class="sr-only">Search</label>
<!--                            <div class="relative">-->
<!--                                <div class="absolute inset-y-0 flex items-center pointer-events-none rtl:inset-r-0 start-0 ps-3">-->
<!--                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">-->
<!--                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>-->
<!--                                    </svg>-->
<!--                                </div>-->
<!--                                <input type="text" id="table-search-users" class="block p-2 text-sm text-gray-900 border border-gray-300 rounded-lg ps-10 w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for users">-->
<!--                            </div>-->
                        </div>
                        <table class="w-full text-sm text-left text-gray-500 rtl:text-right">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        <!-- <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="checkbox-all-search" class="sr-only">checkbox</label> -->
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Full Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Phone Number
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Company
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="visitor in visitors" :key="visitor.id" class="bg-white border-b hover:bg-gray-50">
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ visitor.full_name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ visitor.email }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>  {{ visitor.phone_number }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ visitor.company }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="#" @click.stop="editModal(visitor)" class="font-medium text-blue-600 hover:underline">Edit</a> /
                                    <a href="#" @click.stop="getId(visitor)" class="font-medium text-red-600 hover:underline">Delete</a>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>




                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
