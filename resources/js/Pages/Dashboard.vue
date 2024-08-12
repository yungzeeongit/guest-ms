<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted  } from 'vue';


const props = defineProps({
    visits: {
        type: Array as () => Array<Data>,
        default: () => [],
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



const currentDateTime = ref<string>(formatTime(new Date()))

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
}

const visitCount = ref([...props.visits]).value.length;
const staffCount = ref([...props.allHosts]).value.length;
const guestCount = ref([...props.allVisitors]).value.length;

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

const updateDateTime = () => {
      currentDateTime.value = formatTime(new Date());
    };

    let intervalId: number;

    onMounted(() => {
      updateDateTime();
      intervalId = setInterval(updateDateTime, 1000);
    });

    onUnmounted(() => {
      clearInterval(intervalId);
    });

    
      
    

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
      

        <div class="py-12" style="height: 80vh;">
    <div class=" max-w-7xl h-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-white h-full overflow-hidden shadow-sm sm:rounded-lg">
            <div class=" text-gray-900 mx-auto h-full" >
                <div class="flex items-center justify-center text-white h-full bg-white rounded-lg">
                    <div class=" duration-1000 ease-in-out mr-2 w-1/3 pt-6 text-4xl flex flex-col bg-blue-800 rounded-lg h-full p-4 hover:w-2/4">
                        <div class="self-center">
                        Total Guest Count
                    </div>
                    <div class=" text-8xl  self-center place-content-center">{{ guestCount }}</div>
                    <div class="h-1/2 mt-2 bt-1-black self-center justify-stretch place-content-center">{{currentDateTime }} </div>
                </div>
                
                    <div class=" duration-1000 ease-in-out mr-2 w-1/3 pt-6 text-4xl flex flex-col bg-blue-800 rounded-lg h-full p-4 hover:w-2/4">
                        <div class="self-center">Total Staff Count </div>
                        <div class="h-2/3 text-8xl justify-self-center self-center place-content-center">{{ staffCount}}</div>
                        
                    </div>
                    <div class="w-1/3 pt-6 duration-1000 ease-in-out text-4xl flex flex-col bg-blue-800 rounded-lg h-full p-4 hover:w-2/4">
    <div class="self-center">Total Visits</div>
    <div class="h-2/3 text-8xl justify-self-center self-center place-content-center">{{ visitCount }}</div>
</div>

                </div>
            </div>
        </div>
    </div>
</div>



    </AuthenticatedLayout>
</template>
