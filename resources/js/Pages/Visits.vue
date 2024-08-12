<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import TextInputx from '@/Components/TextInputx.vue';
import InputLabelx from '@/Components/InputLabelx.vue';
import PrimaryButtonx from '@/Components/PrimaryButtonx.vue';
import DeleteConfirmationModal from '@/Components/DeleteConfirmatiomModal.vue'
import { format, parseISO } from 'date-fns';






const props = defineProps({
    visits:  {
        type: Array as () => Array<Data>,
            default: () => [],
    },

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
const selectedVisits = ref<Data | null>(null);
const showDetails = ref(false);

const currentPage = ref(1);
const pageSize = ref(10);



const suggestions = ref<Array<{ id: number, full_name: string }>>([]);
const filteredVisits = ref([...props.visits]);


const hosts = ref<Array<{ host_id: number, host_name: string }>>([]);
const showSuggestions = ref(false);
const showHosts = ref(false);
const searchQuery = ref('');

const showModal = ref<boolean>(false);
const editMode = ref<boolean>(false);
const deleteModal = ref<boolean>(false);
const currentId = ref<string>('');

interface Host {
    host_id: number,
    host_name: string;
    host_email: string;
    host_phone: string;
    host_position: string;
    host_department: string;
}

interface Visitor {
    id: number,
    full_name: string;
    email: string;
    company: string;
    phone_number: string;
}


interface Data {
    visit_id: number;
    visitor_name: string;
    host_name: string;
    purpose: string;
    status: string;
    searchQuery: string;
    check_in_time: string;
check_out_time: string;
duration: number;
}
const form = useForm<Data>({
    visit_id: 0,
    visitor_name: '',
    host_name: '',
    purpose: '',
    status: '',
    searchQuery: '',
    check_in_time: '',
    check_out_time:'',
    duration : 0,
});


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
        showHosts.value = hosts.value.length > 0;
    } else {
        showHosts.value = false;
    }
    console.log(hosts.value);
};

const selectSuggestion = (suggestion: { id: number, full_name: string }) => {
    form.visitor_name = suggestion.full_name;
    showSuggestions.value = false;
};

const selectHost = (hosts: { host_id: number, host_name: string }) => {
    form.host_name = hosts.host_name;
    showHosts.value = false;
};

const showMe = (visit: Data) => {
    selectedVisitor.value = props.allVisitors.find(v => v.full_name === visit.visitor_name) || null;
    selectedHost.value = props.allHosts.find(h => h.host_name === visit.host_name) || null;
    selectedVisits.value = props.visits.find(h => h.check_in_time  === visit.check_in_time ) || null;
    showDetails.value = true;
};


const formattedCheckInTime = computed(() => {
  if (selectedVisits.value && selectedVisits.value.check_in_time) {
    // Format the check-in time
    return format(parseISO(selectedVisits.value.check_in_time), 'do MMMM yyyy, hh:mm a');
  } else {
    return 'No check-in time available';
  }
});


const hideDetais = () => {
    showDetails.value = false;
    console.log('hideDetais');
};


const currentTime = new Date().toISOString();

//******************************************************

const deleteData = () => {
    const visit_id = currentId.value;
    console.log(currentId.value);
    form.delete(route('visits.destroy', { visit: visit_id }), {
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
            printDiv();
        },
    });
};


const updateData = () => {
    form.patch(route('visits.update', { visit: form.visit_id }), {
        onFinish: () => {
            form.reset();
            showModal.value = false;
        },
    });
};

const editModal = (visit: Data) => {
    showModal.value = false;
    formreset();
    editMode.value = true;

    form.visit_id = visit.visit_id;
    form.visitor_name = visit.visitor_name;
    form.host_name = visit.host_name;
    form.purpose = visit.purpose;
    form.status = visit.status;
    showModal.value = true;
};

const formreset = () => {
    form.visitor_name = '';
    form.host_name = '';

    form.purpose = '';
    form.status = '';
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



function formatTime(isoString: string | number | Date) {
    const date = new Date(isoString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

}


const searchVisits = () => {
  const query = searchQuery.value.toLowerCase();
  if (query.length > 0) {
    filteredVisits.value = props.visits.filter((visit) =>
      Object.values(visit).some((value) =>
        value && value.toString().toLowerCase().includes(query)
      )
    );
  } else {
    filteredVisits.value = [...props.visits];
  }
  console.log(filteredVisits.value);
  console.log(props.visits);
};



const paginatedVisits = computed(() => {
      const start = (currentPage.value - 1) * pageSize.value;
      const end = start + pageSize.value;
      return filteredVisits.value.slice(start, end);
    });

    const totalPages = computed(() => {
      return Math.ceil(filteredVisits.value.length / pageSize.value);
    });

    watch(() => props.visits, (newVisits) => {
      searchVisits();
    });


    const calculateDurationInMinutes = (checkInTime: string | number | Date, checkOutTime: string | number | Date) => {
  const checkInDate = new Date(checkInTime);
  const checkOutDate = new Date(checkOutTime);

  const checkInTimeMs = checkInDate.getTime();
  const checkOutTimeMs = checkOutDate.getTime();

  const diffTime = Math.abs(checkOutTimeMs - checkInTimeMs);
  const diffMinutes = Math.ceil(diffTime / (1000 * 60));

  return diffMinutes;
};

const printDiv = () => {
  const div = document.getElementById('printableDiv');

  if (!div) {
    console.error('The element to print was not found.');
    return;
  }

  const divContents = div.innerHTML;
  const printWindow = window.open('', '', 'height=400,width=600');

  if (!printWindow) {
    console.error('Failed to open the print window.');
    return;
  }

  printWindow.document.write('<html><head><title>Print</title>');

  // Optionally add styles
  printWindow.document.write('<style>body { font-family: Arial, sans-serif; }</style>');

  printWindow.document.write('</head><body >');
  printWindow.document.write(divContents);
  printWindow.document.write('</body></html>');

  printWindow.document.close();
  printWindow.focus();
  printWindow.print();
};


// const checkout = (visit :Data) => {

// visit.status = 'completed'


//     }

const checkout = (visit: Data, form: any) => {
  // Update the status to 'completed'
  visit.check_out_time = currentTime;
  const durationInMinutes = calculateDurationInMinutes(visit.check_in_time, visit.check_out_time);


  visit.status = 'visitor-out';
  visit.duration = durationInMinutes
  form.duration = visit.duration
  form.status = visit.status

  // Send the updated status to the server
  form.patch(route('visits.checkout', { visit: visit.visit_id }), {
    status: visit.status,
    duration: visit.duration
  });
  console.log(form.duration)
};

function id(value: Visitor, index: number, obj: Visitor[]): unknown {
    throw new Error('Function not implemented.');
}

const options = [

  { value: 'visitor-in', text: 'Visitor In' },
  { value: 'visitor-out', text: 'Visitor Out' }
];
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


                        <div
                            class="flex flex-wrap items-center justify-between pb-4 space-y-4 bg-white flex-column md:flex-row md:space-y-0">
                            <div class="mt-4 ml-5">
                                <button @click="newModal" data-modal-target="static-modal"
                                    data-modal-toggle="static-modal"
                                    class="inline-flex items-center px-3 py-2 pl-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                                    type="button">
                                    <div class="pr-1">Add</div>
                                    <svg class="w-3 h-3 mb-1 text-white me-2" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                        <path d="M11 11V5H13V11H19V13H13V19H11V13H5V11H11Z"></path>
                                    </svg>
                                </button>

                                <input type="text" v-model="searchQuery" @input="searchVisits" placeholder="Search..."
                                    class="search-input rounded-lg ml-20" />



                                <div v-show="showModal" class="fixed inset-0 bg-black bg-opacity-50"></div>
                                <div :class="{ 'hidden': !showModal }" id="static-modal" data-modal-backdrop="static"
                                    tabindex="-1" aria-hidden="true"
                                    class="flex overflow-y-auto overflow-x-hidden fixed top-1/2 right-1/2 left-1/2 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-2xl max-h-full p-4">
                                        <div class="relative bg-white rounded-lg shadow">
                                            <div
                                                class="flex items-center justify-between p-4 border-b rounded-t md:p-5">
                                                <h3 class="text-xl font-semibold text-gray-900" v-if="!editMode">Add
                                                    Visit</h3>
                                                <h3 class="text-xl font-semibold text-gray-900" v-if="editMode">Edit
                                                    Visit</h3>
                                                <button @click="closeModal" type="button"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 ms-auto"
                                                    data-modal-hide="static-modal">
                                                    <svg class="w-3 h-3" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <div class="p-4 space-y-4 md:p-5">
                                                <form class="max-w-sm mx-auto">
                                                    <div class="mb-5">
                                                        <InputLabelx for="visitor_name" value="Visitor Name" />
                                                        <div class="relative">
                                                            <TextInputx id="visitor_name" type="text"
                                                                v-model="form.visitor_name" @input="searchVisitors()"
                                                                required autocomplete="off" />
                                                            <div v-if="showSuggestions && suggestions.length"
                                                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg">
                                                                <ul>
                                                                    <li v-for="suggestion in suggestions"
                                                                        :key="suggestion.id"
                                                                        class="px-4 py-2 cursor-pointer hover:bg-gray-200"
                                                                        @click="selectSuggestion(suggestion)">
                                                                        {{ suggestion.full_name }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-5">
                                                        <InputLabelx for="host_name" value="Staff Name" />
                                                        <div class="relative">
                                                            <TextInputx id="host_name" type="text"
                                                                v-model="form.host_name" @input="searchHosts()" required
                                                                autocomplete="off" />
                                                            <div v-if="showHosts && hosts.length"
                                                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg">
                                                                <ul>
                                                                    <li v-for="host in hosts" :key="host.host_id"
                                                                        class="px-4 py-2 cursor-pointer hover:bg-gray-200"
                                                                        @click="selectHost(host)">
                                                                        {{ host.host_name }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>





                                                    </div>
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="Purpose" />
                                                        <TextInputx id="purpose" type="text" v-model="form.purpose"
                                                            required autofocus placeholder="Sales" autocomplete="off" />
                                                    </div>
                                                    <div class="mb-5">
                                                        <InputLabelx for="name" value="Status" />
                                                        <!-- <TextInputx id="status" type="text" v-model="form.status"
                                                            required autofocus placeholder="Sales" autocomplete="off" /> -->

                                                            <select v-model="form.status" name="activity" id="status" class="form-control block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                            <!-- <option value="">Select Status</option> -->
                                                            <option v-for="option in options" :key="option.value" :value="option.value">
                                                                {{ option.text }}
                                                            </option>
                                                            </select>
                                                            <!-- <p>Selected Status: {{ status }}</p> -->

                                                    </div>

                                                </form>
                                            </div>
                                            <div
                                                class="flex items-center justify-center p-4 border-t border-gray-200 rounded-b md:p-5">
                                                <PrimaryButtonx @click="submit" v-if="!editMode" class="text-center"
                                                    :class="{ 'opacity-25': form.processing }"
                                                    :disabled="form.processing">
                                                    Create
                                                </PrimaryButtonx>
                                                <PrimaryButtonx @click="updateData" v-show="editMode"
                                                    class="text-center" :class="{ 'opacity-25': form.processing }"
                                                    :disabled="form.processing">
                                                    Update
                                                </PrimaryButtonx>
                                                <button @click="closeModal" type="button"
                                                    class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <!-- Reusable Delete Confirmation Modal -->
                                    <delete-confirmation-modal :show-modal="deleteModal"
                                        :message="deleteConfirmationMessage"
                                        :confirm-button-text="deleteConfirmButtonText"
                                        :cancel-button-text="deleteCancelButtonText" :confirm-action="deleteData"
                                        :close-modal="closedeltemodal" />
                                </div>
                            </div>
                        </div>

                        <table class="w-full text-sm text-left text-gray-500 rtl:text-right">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">


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
                                        Visit Duration (minutes)
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr v-for="visit in paginatedVisits"
                                      :key="visit.visit_id"
                                    class="bg-white border-b hover:bg-gray-50" @click=showMe(visit)>
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
                                        {{ formatTime(visit.check_in_time) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ formatTime(visit.check_out_time) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ visit.purpose }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ visit.status }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ visit.duration }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a v-show="visit.status == 'visitor-in'" href="#" @click.stop="checkout(visit,form)"
                                        class="font-medium text-green-600 hover:underline">checkout /</a>

                                        <a  href="#" @click.stop="editModal(visit)"
                                            class="font-medium text-blue-600 hover:underline">Edit /</a>

                                        <a href="#" @click.stop="getId(visit),console.log(currentId)"
                                            class="font-medium text-red-600 hover:underline">Delete</a>
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

                        <div  v-show="showDetails" class="fixed inset-0 bg-black bg-opacity-50"></div>
                        <div :class="{ 'hidden': !showDetails }"
                            class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex"
                            aria-modal="true" role="dialog">
                            <div class="relative w-full max-w-md max-h-full p-4">
                                <div class="relative bg-white rounded-lg shadow">
                                    <div>  <button @click="printDiv">Print</button></div>
                                    <button @click="hideDetais" type="button"
                                        class="absolute inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg top-1 right-1 hover:bg-gray-200 hover:text-gray-900 ms-auto dark:hover:bg-gray-600 dark:hover:text-white">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                    <body id="printableDiv" class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-4 border border-gray-300 rounded-lg shadow-lg w-80">
        <div class="flex items-center mb-4">
            <span class="text-lg font-bold text-gray-700">Tag # 1{{ selectedVisitor?.id }}5</span>
        </div>
        <div class="mb-2">
            <p class="text-sm font-medium text-gray-600">Visitor's Name:</p>
            <p class="text-md text-gray-800">{{ selectedVisitor?.full_name }}</p>
        </div>
        <div class="mb-2">
            <p class="text-sm font-medium text-gray-600">Receiving Visitor:</p>
            <p class="text-md text-gray-800">{{ selectedHost?.host_name }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-600">Entry Date / Time:</p>
            <p class="text-md text-gray-800"> {{ formattedCheckInTime  }} </p>
        </div>

        <div>
            <p class="text-sm font-medium text-gray-600">Tag is restricted to {{ selectedHost?.host_department }} department only</p>
            <!-- <p class="text-md text-gray-800"> {{ formattedCheckInTime  }} </p> -->
        </div>
    </div>
</body>
                     <!-- <table class="w-full text-sm text-left rounded gray-500 rtl:text-right">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr class="divide-x divide-slate-700">

                                                <th scope="col" class="px-6 py-3 ">
                                                    Visitor details
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Staff Details
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="showDetails"
                                                class="bg-white border-b divide-x divide-slate-700 hover:bg-gray-50">

                                                <td class="px-6 py-4 ">
                                                    <div><span>Visitor Name:</span> {{ selectedVisitor?.full_name }}
                                                    </div>
                                                    <div><span>Visitor Email:</span> {{ selectedVisitor?.email }}</div>
                                                    <div><span>Visitor Company:</span> {{ selectedVisitor?.company }}
                                                    </div>
                                                    <div><span>Visitor Phone:</span> {{ selectedVisitor?.phone_number }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 ">
                                                    <div><span>Host Name:</span> {{ selectedHost?.host_name }}</div>
                                                    <div><span>Host Email:</span> {{ selectedHost?.host_email }}</div>
                                                    <div><span>Host Phone:</span> {{ selectedHost?.host_phone }}</div>
                                                    <div><span>Host Position:</span> {{ selectedHost?.host_position }}
                                                    </div>
                                                    <div><span>Host Department:</span> {{
                                                        selectedHost?.host_department}}</div>
                                                </td>
                                            </tr>

                                        </tbody>


                                    </table> -->
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
