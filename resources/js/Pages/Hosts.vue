<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref,Ref, onMounted,onUnmounted, computed, watch } from 'vue';
import TextInputx from '@/Components/TextInputx.vue';
import InputLabelx from '@/Components/InputLabelx.vue';
import PrimaryButtonx from '@/Components/PrimaryButtonx.vue';
import DeleteConfirmationModal from '@/Components/DeleteConfirmatiomModal.vue'

// Props
const props = defineProps({
    hosts:  {
        type: Array as () => Array<Data>,
            default: () => [],
    }
});

// Delete confirmation modal
const deleteConfirmationMessage = ref("Are you sure you want to delete this Host?");
const deleteConfirmButtonText = ref("Yes, I'm sure");
const deleteCancelButtonText = ref("No, cancel");
const filteredHosts = ref([...props.hosts]) ;
const currentPage = ref(1);
const pageSize = ref(10);

const showModal = ref<boolean>(false);
const editMode = ref<boolean>(false);
const deleteModal = ref<boolean>(false);
const currentId = ref<string>('');
const searchQuery = ref('')

interface Data {
    host_id: number,
    host_name: string;
    host_email: string;
    host_phone: string;
    host_position: string;
    host_department: string;
}
const form = useForm<Data>({
    host_id:0,
    host_name: '',
    host_email: '',
    host_phone: '',
    host_position: '',
    host_department: '',
});

const deleteData = () => {
    const host_id = currentId.value;
    console.log(currentId.value);
    form.delete(route('hosts.destroy', { host:host_id }), {
        onFinish: () => {
            deleteModal.value = false;
        },
    });
};

const getId = (host: Data) => {
    currentId.value = String(host.host_id);
    deleteModal.value = true;
};

const closedeltemodal = () => {
    deleteModal.value = false;
};

const submit = () => {
    form.post(route('hosts.store'), {
        onFinish: () => {
            showModal.value = false;
        },
    });
};

const updateData = () => {
    form.patch(route('hosts.update', { host: form.host_id }), {
        onFinish: () => {
            form.reset('host_name', 'host_email', 'host_phone', 'host_position', 'host_department');
            showModal.value = false;
        },
    });
};

const editModal = (host:Data) => {
    showModal.value = false;
    formreset();
    editMode.value = true;

    form.host_id = host.host_id;
    form.host_name = host.host_name;
    form.host_phone = host.host_phone;
    form.host_email = host.host_email;
    form.host_position = host.host_position;
    form.host_department = host.host_department;

    showModal.value = true;
};

const formreset = () => {
    form.host_name = '';
    form.host_phone = '';
    form.host_email = '';
    form.host_position = '';
    form.host_department = '';
};

const newModal = () => {
    showModal.value = false;
    formreset();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editMode.value = false;
};

const searchHosts = () => {
      const query = searchQuery.value.toLowerCase();
      if (query.length > 0) {
        filteredHosts.value = props.hosts.filter((host) =>
          Object.values(host).some((value) =>
            value && value.toString().toLowerCase().includes(query)
          )
        );
      } else {
        filteredHosts.value = [...props.hosts];
      }
      console.log(filteredHosts.value);
    };
  
    const paginatedVisits = computed(() => {
      const start = (currentPage.value - 1) * pageSize.value;
      const end = start + pageSize.value;
      return filteredHosts.value.slice(start, end);
    });

    const totalPages = computed(() => {
      return Math.ceil(filteredHosts.value.length / pageSize.value);
    });

    watch(() => props.hosts, (newHosts) => {
        searchHosts();
    });

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Guest Manager</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <div class="flex flex-wrap items-center justify-between pb-4 space-y-4 bg-white flex-column md:flex-row md:space-y-0">
                            <div class="mt-4 ml-5">
                                <button @click="newModal" data-modal-target="static-modal" data-modal-toggle="static-modal" class="inline-flex items-center px-3 py-2 pl-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300" type="button">
                                    <div class="pr-1">Add</div>
                                    <svg class="w-3 h-3 mb-1 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                        <path d="M11 11V5H13V11H19V13H13V19H11V13H5V11H11Z"></path>
                                    </svg>
                                </button>
                                <input type="text" v-model="searchQuery" @input="searchHosts" placeholder="Search..."
                                class="search-input rounded-lg ml-20" />

                                <div v-show="showModal" class="fixed inset-0 bg-black bg-opacity-50"></div>
                                <div :class="{ 'hidden': !showModal }" id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="flex overflow-y-auto overflow-x-hidden fixed top-1/2 right-1/2 left-1/2 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full p-4">
                                        <div class="relative bg-white rounded-lg shadow">
                                            <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5">
                                                <h3 class="text-xl font-semibold text-gray-900" v-if="!editMode">Add Host</h3>
                                                <h3 class="text-xl font-semibold text-gray-900" v-if="editMode">Edit Host</h3>
                                                <button @click="closeModal" type="button" class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 ms-auto" data-modal-hide="static-modal">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <div class="p-4 space-y-4 md:p-5">
                                                <form class="max-w-sm mx-auto">
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="Full Name" />
                                                        <TextInputx
                                                            id="host_name"
                                                            type="text"
                                                            v-model="form.host_name"
                                                            required
                                                            autofocus
                                                            placeholder="Omoboriola Chukwudi Danjuma"
                                                            autocomplete="off"
                                                        />
                                                    </div>
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="Email" />
                                                        <TextInputx
                                                            id="host_email"
                                                            type="email"
                                                            v-model="form.host_email"
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
                                                            v-model="form.host_phone"
                                                            required
                                                            autofocus
                                                            placeholder="07000000000"
                                                            autocomplete="off"
                                                        />
                                                    </div>
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="Position" />
                                                        <TextInputx
                                                            id="position"
                                                            type="text"
                                                            v-model="form.host_position"
                                                            required
                                                            autofocus
                                                            placeholder="Manager"
                                                            autocomplete="off"
                                                        />
                                                    </div>
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="Department" />
                                                        <TextInputx
                                                            id="department"
                                                            type="text"
                                                            v-model="form.host_department"
                                                            required
                                                            autofocus
                                                            placeholder="Sales"
                                                            autocomplete="off"
                                                        />
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="flex items-center justify-center p-4 border-t border-gray-200 rounded-b md:p-5">
                                                <PrimaryButtonx @click="submit" v-if="!editMode" class="text-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                    Create
                                                </PrimaryButtonx>
                                                <PrimaryButtonx @click="updateData" v-show="editMode" class="text-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                    Update
                                                </PrimaryButtonx>
                                                <button @click="closeModal" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
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
                                </div>                            </div>
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
                                  Department
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Position
                                  </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="host in paginatedVisits" :key="host.host_id" class="bg-white border-b hover:bg-gray-50">
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ host.host_name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ host.host_email }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>  {{ host.host_phone }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ host.host_department }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ host.host_position }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="#" @click.stop="editModal(host)" class="font-medium text-blue-600 hover:underline">Edit</a> /
                                    <a href="#" @click.stop="getId(host),console.log(currentId.valueOf)" class="font-medium text-red-600 hover:underline">Delete</a>
                                </td>
                            </tr>

                            </tbody>
                        </table>

                        <div class="flex flex-wrap items-center justify-center p-4 space-y-4 bg-white flex-column md:flex-row md:space-y-0">
      <button
        :disabled="currentPage === 1"
        @click="currentPage--"
        class="inline-flex items-center mr-3 px-3 py-1 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-2"      >
        Prev
      </button>
      <span>Page {{ currentPage }} of {{ totalPages }}</span>
      <button
        :disabled="currentPage === totalPages"
        @click="currentPage++"
      class="inline-flex items-center ml-3 px-3 py-1 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-2"
      >
        Next
      </button>
    </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
