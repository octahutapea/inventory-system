<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../api';
import AcceptModal from '../Modal/FinishModal.vue'
import RejectModal from '../Modal/RejectModal.vue'

// API GET CLAIM
const requests = ref([]);
const fetchDataItems = async () => {
    await api.get('/api/process_claim_admin')

    .then(response => {

        requests.value = response.data.data

    });
}

onMounted(() => {
    fetchDataItems();
});

// SEARCH
const keyword = ref("");

// API GET COMPANY
const items_search = ref([]);
const items_count = ref();
const search = async () => {
    await api.get(`/api/claim_process_search/${keyword.value}`)

    .then(response => {

        items_search.value = response.data.data
        console.log("item_search", items_search.value)
        items_count.value = response.data.count

    });
}

watch(() => keyword.value, (newKeyword) => {
  console.log("keyword", newKeyword);
    if (newKeyword.trim() !== ' ') {
        // If keyword is not empty, perform search
        search();
    } else {
        // If keyword is empty, fetch default items
        fetchDataItems();
    }
});

const downloadDocument = async (filename) => {
  try {
    const response = await api.get(`/api/open-document/${filename}`, {
      responseType: 'blob', // Set responseType ke 'blob' untuk menangani data biner (binary data)
    });

    // Buat URL objek dari blob data
    const blob = new Blob([response.data], { type: 'application/pdf' });
    const url = window.URL.createObjectURL(blob);

    // Buat elemen <a> untuk menautkan ke URL dan otomatis unduh file
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;

    // Tambahkan elemen <a> ke body dokumen dan klik secara otomatis
    document.body.appendChild(a);
    a.click();

    // Hapus elemen <a> setelah selesai
    document.body.removeChild(a);
  } catch (error) {
    console.error('Error downloading file:', error);
  }
};

// CLAIM MODAL FOR ACCEPT
const isAcceptModalVisible = ref(false);
const idToAccept = ref(null);

const showAcceptModal = (id) => {
    idToAccept.value = id;
    isAcceptModalVisible.value = true;
}
const closeAcceptModal = () => {
    isAcceptModalVisible.value = false;
}

// API ACCEPT CLAIM
const confirmAccept = async () => {
  await api.get(`/api/finish_claim/${idToAccept.value}`)

    .then(response => {
      location.reload();
      console.log(idToAccept.value)
      console.log(response)
    });
};

// CLAIM MODAL FOR REJECT
const isRejectModalVisible = ref(false);
const idToReject = ref(null);

const showRejectModal = (id) => {
    idToReject.value = id;
    isRejectModalVisible.value = true;
}
const closeRejectModal = () => {
    isRejectModalVisible.value = false;
}

// API REJECT CLAIM
const confirmReject = async () => {
  await api.get(`/api/reject_request/${idToReject.value}`)

    .then(response => {
      location.reload();
      console.log(response)
    });
};

// API EXPORT CLAIM
const exportExcel = async () => {
  try {
    const response = await api.get('/api/claims_process_export', {
      responseType: 'blob', // Set responseType ke 'blob' untuk menangani data biner (binary data)
    });

    // Buat URL objek dari blob data
    const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const url = window.URL.createObjectURL(blob);

    // Buat elemen <a> untuk menautkan ke URL dan otomatis unduh file
    const a = document.createElement('a');
    a.href = url;
    a.download = 'claim_for_hc.xlsx';

    // Tambahkan elemen <a> ke body dokumen dan klik secara otomatis
    document.body.appendChild(a);
    a.click();

    // Hapus elemen <a> setelah selesai
    document.body.removeChild(a);
  } catch (error) {
    console.error('Error exporting Excel:', error);
  }
};

</script>

<template>
  <navbar />
  <sidebar />

  <main class="ml-48 mt-32">
    <div class="bg-white m-8 pt-1 rounded-3xl shadow-sm">
        <div class="flex justify-between">
          <div class="w-fit flex items-center m-4 px-2 border rounded-xl line-color">
            <img class="w-4 h-4 mr-1 " src="../icons/SearchIcon.svg" alt="Search Icon" />
            <input placeholder="Cari" v-model="keyword" />

            <p class="text-xs text-font-color/50 whitespace-nowrap" v-if="keyword !== ''">{{ items_count }} data found</p>
          </div>
          <export-button @click="exportExcel" />
        </div>
      
      <table class="table-auto w-full text-left">
        <thead class="bg-primary/20 rounded-3xl">
          <tr>
            <th class="p-3">No</th>
            <th class="p-3">Nama Lengkap</th>
            <th class="p-3">Jabatan</th>
            <th class="p-3">Lokasi</th>
            <th class="p-3">Jenis Fasilitas</th>
            <th class="p-3">Nomor IMEI</th>
            <th class="p-3">Tanggal Klaim</th>
            <th class="p-3">Nilai Klaim</th>
            <th class="p-3">Dokumen</th>
            <th></th>
            <th></th>
            <!-- <th></th> -->
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(request, index) in (keyword !== '' ? items_search : requests)" :key="request.id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ request.name }}</td>
            <td class="p-3">{{ request.positiontext }}</td>
            <td class="p-3">{{ request.claim_location }}</td>
            <td class="p-3">{{ request.facility_type }}</td>
            <td class="p-3">{{ request.claim_imei }}</td>
            <td class="p-3">{{ request.claim_date }}</td>
            <td class="p-3">{{ filters.formatMoney(request.claim_value) }}</td>
            
            <td class="p-3">
              <button @click="downloadDocument(request.claim_document)">
                <img src="../icons/Document.svg" alt="Dokumen">
              </button>
            </td>

            <!-- <td>
                <button @click.prevent="showContract(company.id)" >
                    <img class="w-5 h-5" src="../icons/Detail.svg" alt="">
                </button>
            </td> -->
            <td>
                <button @click.prevent="showAcceptModal(request.id)">
                    <img class="w-6 h-6" src="../icons/Accept.svg" alt="">
                </button>
            </td>
            <td>
                <button @click.prevent="showRejectModal(request.id)">
                    <img class="w-6 h-6" src="../icons/Reject.svg" alt="">
                </button>
            </td>
          </tr>
        </tbody>
      </table>
      <AcceptModal v-if="isAcceptModalVisible" 
      @accept="confirmAccept" 
      @close="closeAcceptModal" />
      <RejectModal v-if="isRejectModalVisible" 
      @reject="confirmReject" 
      @close="closeRejectModal" />
    </div>
    
  </main>
</template>