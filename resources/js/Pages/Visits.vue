<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref,onMounted } from 'vue';
import TextInputx from '@/Components/TextInputx.vue';
import InputLabelx from '@/Components/InputLabelx.vue';
import PrimaryButtonx from '@/Components/PrimaryButtonx.vue';
import DeleteConfirmationModal from '@/Components/DeleteConfirmatiomModal.vue'
import axios from 'axios';
import Visitors from './Visitors.vue';
import Hosts from './Hosts.vue';



// Props
const props = defineProps({
    visits: Array<Data>,
   
    visitorNames: {
        type: Array as () => Array<Visitor>,
        default: () => []
    },
    hostNames: {
        type: Array as () => Array<Host>,
        default: () => []
    },

    allVisitors: {
        type: Array as () => Array<Visitor>,
        default: () => [],
    },
    allHosts: {
        type: Array as () => Array<Host>,
        default: () => [],
    },
});


// Delete confirmation modal
const deleteConfirmationMessage = ref("Are you sure you want to delete this Host?");
const deleteConfirmButtonText = ref("Yes, I'm sure");
const deleteCancelButtonText = ref("No, cancel");



const selectedVisitor = ref<Visitor | null>(null);
const selectedHost = ref<Host | null>(null);
const showDetails = ref(false);


// const showDetails = ref(false);
// const selectedVisitor = ref(null);
// const selectedHost = ref(null);

const suggestions = ref<Array<{ id: number, full_name: string }>>([]);

const hosts = ref<Array<{ host_id: number, host_name: string }>>([]);
const showSuggestions = ref(false);

const showModal = ref<boolean>(false);
const editMode = ref<boolean>(false);
const deleteModal = ref<boolean>(false);
const currentId = ref<string>('');

interface Visitor {
    id: number;
    full_name: string;
}

interface Host {
    host_id: number;
    host_name: string;
}

interface Data {
    visit_id: number;
  
    visitor_name: string;
   
    host_name: string;
    check_in_time: string;
    check_out_time: string;
    purpose: string;
    status: string;
    visitor: Visitor,
    host: Host,
}
const form = useForm<Data>({
    visitor: {
        id: 0,
        full_name: '',
    },
    host: { host_id: 0, host_name: '' },
    visit_id:0,
    visitor_name: '',   
    host_name:'',
  
    check_in_time: '',
    check_out_time: '',
    purpose: '',
    status: '',  
});


//***************************





const searchVisitors = () => {
    const query = form.visitor_name.toLowerCase();
    if (query.length > 0) {
        suggestions.value = props.visitorNames.filter(visitor => 
            visitor.full_name.toLowerCase().includes(query)
        );
        showSuggestions.value = suggestions.value.length > 0;
    } else {
        showSuggestions.value = false;
    }
    console.log(suggestions.value);
};

const searchHosts = () => {
    const query = form.host_name.toLowerCase();
    if (query.length > 0) {
        hosts.value = props.hostNames.filter(host => 
            host.host_name.toLowerCase().includes(query)
        );
        showSuggestions.value = hosts.value.length > 0;
    } else {
        showSuggestions.value = false;
    }
    console.log(suggestions.value);
};

const selectSuggestion = (suggestion: { id: number, full_name: string }) => {
    form.visitor_name = suggestion.full_name;
    showSuggestions.value = false;
};

const showMe = (visitor: number, host: number) => {
    selectedVisitor.value = props.allVisitors.find(visitor => visitor.id === visitor['id']) || null ;
    selectedHost.value = props.allHosts.find(host => host.host_id === host.host_id) || null;
    showDetails.value = true;
    
    console.log(props.allVisitors);
    console.log('Selected Visitor:', selectedVisitor.value);
    console.log('Selected Host:', selectedHost.value);
 
};





// const showMe = (visitorId: number, hostId: number) => {
//     selectedVisitor.value = props.allVisitors.find(visitor => visitor.id === visitorId) || null;
//     selectedHost.value = props.allHosts.find(host => host.host_id === hostId) || null;
//     showDetails.value = true;

//     console.log('Selected Visitor:', selectedVisitor.value);
//     console.log('Selected Host:', selectedHost.value);
//     console.log(props.allVisitors);
//     console.log(props.allHosts);
//     console.log(visitorId)
// };






const selectHost = (hosts: { host_id: number, host_name: string }) => {
    form.visitor_name = hosts.host_name;
    showSuggestions.value = false;
};


// const showMe = (visit) => {
//     selectedVisitor.value = allVisitors.value.find(visitor => visitor.id === visit.visitor_id);
//     selectedHost.value = allHosts.value.find(host => host.host_id === visit.host_id);
//     showDetails.value = true;
//     console.log('Selected Visitor:', selectedVisitor.value);
//     console.log('Selected Host:', selectedHost.value);
// };


const hideDetais = () => {
showDetails.value = false;
    console.log('hideDetais');
};




//******************************************************

const deleteData = () => {
    const visit_id = currentId.value;
    console.log(currentId.value);
    form.delete(route('visits.destroy', { visit:visit_id }), {
        onFinish: () => {
            deleteModal.value = false;
        },
    });
};

const getId = (visit: Data) => {
    currentId.value = String(visit.visit_id);
    deleteModal.value = true;
};

const closedeltemodal = () => {
    deleteModal.value = false;
};

const submit = () => {
    form.post(route('visits.store'), {
        onFinish: () => {
            showModal.value = false;
        },
    });
};


const updateData = () => {
    form.patch(route('visits.update', { visit:form.visit_id }), {
        onFinish: () => {
            form.reset();
            showModal.value = false;
        },
    });
};

const editModal = (visit:Data) => {
    showModal.value = false;
    formreset();
    editMode.value = true;

    form.visit_id = visit.visit_id;
    form.visitor_name = visit.visitor_name;
    form.host_name = visit.host_name;
    form.check_in_time = visit.check_in_time;
    form.check_out_time = visit.check_out_time;
    form.purpose = visit.purpose;
    form.status = visit.status;
   

    showModal.value = true;
};

const formreset = () => {
    form.visitor_name   = '';  
    form.host_name   = '';
    form.check_in_time   = ''; 
    form.check_out_time   = '';
    form.purpose   = '';
    form.status   = '';
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

function id(value: Visitor, index: number, obj: Visitor[]): unknown {
    throw new Error('Function not implemented.');
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
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                      
                      
                        <div class="flex flex-wrap items-center justify-between pb-4 space-y-4 bg-white flex-column md:flex-row md:space-y-0">
                            <div class="mt-4 ml-5">
                                <button @click="newModal" data-modal-target="static-modal" data-modal-toggle="static-modal" class="inline-flex items-center px-3 py-2 pl-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300" type="button">
                                    <div class="pr-1">Add</div>
                                    <svg class="w-3 h-3 mb-1 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                        <path d="M11 11V5H13V11H19V13H13V19H11V13H5V11H11Z"></path>
                                    </svg>
                                </button>

                                <div v-show="showModal" class="fixed inset-0 bg-black bg-opacity-50"></div>
                                <div :class="{ 'hidden': !showModal }" id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="flex overflow-y-auto overflow-x-hidden fixed top-1/2 right-1/2 left-1/2 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full p-4">
                                        <div class="relative bg-white rounded-lg shadow">
                                            <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5">
                                                <h3 class="text-xl font-semibold text-gray-900" v-if="!editMode">Add Visit</h3>
                                                <h3 class="text-xl font-semibold text-gray-900" v-if="editMode">Edit Visit</h3>
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
                                                        <InputLabelx for="visitor_name" value="Visitor Name" />
                                                        <div class="relative">
                                                            <TextInputx
                                                                id="visitor_name"
                                                                type="text"
                                                                v-model="form.visitor_name"
                                                                @input="searchVisitors()"
                                                                required
                                                                autocomplete="off"
                                                            />
                                                            <div v-if="showSuggestions && suggestions.length" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg">
                                                                <ul>
                                                                    <li v-for="suggestion in suggestions" :key="suggestion.id"   class="px-4 py-2 cursor-pointer hover:bg-gray-200" @click="selectSuggestion(suggestion)">
                                                                        {{ suggestion.full_name }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="mb-5">
                                                        <InputLabelx for="host_name" value="Staff Name" />
                                                        <div class="relative">
                                                            <TextInputx
                                                                id="host_name"
                                                                type="text"
                                                                v-model="form.host_name"
                                                                @input="searchHosts()"
                                                                required
                                                                autocomplete="off"
                                                            />
                                                            <div v-if="showSuggestions && hosts.length" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg">
                                                                <ul>
                                                                    <li v-for="host in hosts" :key="host.host_id"   class="px-4 py-2 cursor-pointer hover:bg-gray-200" @click="selectHost(host)">
                                                                        {{ host.host_name }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                 
                                                   
                                               
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="check_in_time" />
                                                        <TextInputx
                                                            id="check_in_time"
                                                            type="time"
                                                            v-model="form.check_in_time"
                                                            required
                                                            autofocus
                                                            placeholder="Sales"
                                                            autocomplete="off"
                                                        />
                                                    </div>
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="Check out at" />
                                                        <TextInputx
                                                            id="check_out_time"
                                                            type="time"
                                                            v-model="form.check_out_time"
                                                            required
                                                            autofocus
                                                            placeholder="Sales"
                                                            autocomplete="off"
                                                        />
                                                    </div>
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="Purpose" />
                                                        <TextInputx
                                                            id="purpose"
                                                            type="text"
                                                            v-model="form.purpose"
                                                            required
                                                            autofocus
                                                            placeholder="Sales"
                                                            autocomplete="off"
                                                        />
                                                    </div>
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="Status" />
                                                        <TextInputx
                                                            id="status"
                                                            type="text"
                                                            v-model="form.status"
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
                            <tr >
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                   
                                        <!-- <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="checkbox-all-search" class="sr-only">checkbox</label> -->
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Visitor Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                   Staff Name
                                </th>
                               
                                <th scope="col" class="px-6 py-3">
                                    Check In At
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Check Out At
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Purpose
                                  </th>
                                  <th scope="col" class="px-6 py-3">
                                    Status
                                  </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="visit in visits"  :key="visit.visit_id" class="bg-white border-b hover:bg-gray-50"  @click="showMe(visit.visitor.id, visit.host.host_id)">
                               
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                      
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ visit.visitor_name }}
                                </td>
                           
                                <td class="px-6 py-4">
                                    {{ visit.host_name }}
                                </td>
                            
                                <td class="px-6 py-4">
                                    {{ visit.check_in_time }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ visit.check_out_time}}
                                </td>
                                <td class="px-6 py-4">
                                    {{ visit.purpose }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ visit.status }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="#" @click.stop="editModal(visit)" class="font-medium text-blue-600 hover:underline">Edit</a> /
                                    <a href="#" @click.stop="getId(visit),console.log(currentId.valueOf)" class="font-medium text-red-600 hover:underline">Delete</a>
                                </td>
                            </tr>

                            </tbody>
                        </table>

                          <div v-show="showDetails"  class="fixed inset-0 bg-black bg-opacity-50"></div>
                        <div :class="{ 'hidden': !showDetails }" class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex" aria-modal="true" role="dialog">
                        <div class="relative w-full max-w-md max-h-full p-4">
                            <div class="relative bg-white rounded-lg shadow">
                                <button @click="hideDetais" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <!-- <div class="p-4 text-center md:p-5"> -->
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
                                                Visitor details
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                               Staff Details
                                            </th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="showDetails"  class="bg-white border-b hover:bg-gray-50">
                                                <td class="px-6 py-4">
                                                    
                                                </td>
                                                <td class="px-6 py-4">
                                                 <span>Visitor Name:</span>   {{ selectedVisitor ? selectedVisitor.full_name: 'No visitor selected' }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span>Host Name:</span>  {{ selectedHost ? selectedHost.host_name : 'No host selected' }}                                               
                                                 </td>
                                            </tr>
                                           
                                        </tbody>

                                        
                                    </table>
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

  
</template>
