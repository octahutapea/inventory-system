<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../api';
import CreateClaimModal from '../Modal/CreateClaimModal.vue'
import DeleteModal from '../Modal/DeleteModal.vue'
import EditClaimModal from '../Modal/EditClaimModal.vue'

// API GET CLAIM
const claims = ref([]);
const fetchDataItems = async () => {
    await api.get('/api/claims')

    .then(response => {

        claims.value = response.data.data
        console.log(claims.value)

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
    await api.get(`/api/claim_search/${keyword.value}`)

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

// CLAIM MODAL FOR CREATE
const isCreateClaimModalVisible = ref(false);

const showCreateClaimModal = () => {
    isCreateClaimModalVisible.value = true;
}
const closeCreateClaimModal = () => {
    isCreateClaimModalVisible.value = false;
}

// // SHOW CONTRACT
// const router = useRouter();

// const showContract = (company_id) => {
//     router.push({ name: 'detailcontract', params: { companyId: company_id } });
// }

// CLAIM MODAL FOR EDIT
const isEditClaimModalVisible = ref(false);
const claimIdToEdit = ref(null);

const showEditClaimModal = (id) => {
    isEditClaimModalVisible.value = true;
    claimIdToEdit.value = id;
}
const closeEditClaimModal = () => {
    isEditClaimModalVisible.value = false;
}

// CLAIM MODAL FOR DELETE
const isDeleteModalVisible = ref(false);
const idToDelete = ref(null);

const showDeleteModal = (id) => {
    idToDelete.value = id;
    isDeleteModalVisible.value = true;
}
const closeDeleteModal = () => {
    isDeleteModalVisible.value = false;
}

// API DELETE COMPANY
const confirmDelete = async () => {
  await api.delete(`/api/claims/${idToDelete.value}`)

    .then(response => {
      location.reload();
      console.log(response)
    });
};

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

// API EXPORT CLAIM
const exportExcel = async () => {
  try {
    const response = await api.get('/api/claims_export', {
      responseType: 'blob', // Set responseType ke 'blob' untuk menangani data biner (binary data)
    });

    // Buat URL objek dari blob data
    const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const url = window.URL.createObjectURL(blob);

    // Buat elemen <a> untuk menautkan ke URL dan otomatis unduh file
    const a = document.createElement('a');
    a.href = url;
    a.download = 'claim.xlsx';

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

        <div class="flex">
          <add-button @click="showCreateClaimModal" />
          <CreateClaimModal v-if="isCreateClaimModalVisible" @close="closeCreateClaimModal" />
          <export-button @click="exportExcel" />
        </div>
        
      </div>
      
      <div class="overflow-x-auto">
        <table class="table-auto w-full text-left">
          <thead class="bg-primary/20 rounded-3xl">
            <tr>
              <th class="p-3">No</th>
              <th class="p-3">Nama Lengkap</th>
              <th class="p-3">Jabatan</th>
              <th class="p-3">PRL</th>
              <th class="p-3">Lokasi</th>
              <th class="p-3">Jenis Fasilitas</th>
              <th class="p-3">Nomor IMEI</th>
              <th class="p-3">Tanggal Klaim</th>
              <th class="p-3">Nilai Klaim</th>
              <th class="p-3">Dokumen</th>
              <th class="p-3">Status</th>
              <th class="p-3"></th>
              <!-- <th></th> -->
            </tr>
          </thead>
          <tbody>
            <tr class="border-t border-dark-color/10" v-for="(claim, index) in (keyword !== '' ? items_search : claims)" :key="claim.id">
              <td class="p-3">{{ index + 1 }}</td>
              <td class="p-3">{{ claim.name }}</td>
              <td class="p-3">{{ claim.positiontext }}</td>
              <td class="p-3">{{ claim.prl_plan }}</td>
              <td class="p-3">{{ claim.claim_location }}</td>
              <td class="p-3">{{ claim.facility_type }}</td>
              <td class="p-3">{{ claim.claim_imei }}</td>
              <td class="p-3">{{ claim.claim_date }}</td>
              <td class="p-3">{{ filters.formatMoney(claim.claim_value) }}</td>
              
              <td class="p-3">
                <button @click="downloadDocument(claim.claim_document)">
                  <img src="../icons/Document.svg" alt="Dokumen">
                </button>
              </td>

              <td class="m-3 w-fit h-fit flex items-center justify-center px-2 border border-black rounded-full">{{ claim.claim_status }}</td>
              <!-- <td>
                  <button @click.prevent="showContract(company.id)" >
                      <img class="w-5 h-5" src="../icons/Detail.svg" alt="">
                  </button>
              </td> -->
              <td class="p-3">
                  <button v-if="claim.claim_status == 'Proses Admin'" @click.prevent="showEditClaimModal(claim.id)">
                      <img class="w-6 h-6" src="../icons/Edit.svg" alt="">
                  </button>
                  <button v-if="claim.claim_status == 'Proses Admin'" @click.prevent="showDeleteModal(claim.id)">
                      <img class="w-6 h-6" src="../icons/Delete.svg" alt="">
                  </button>
              </td>
            </tr>
          </tbody>
        </table>
        <EditClaimModal v-if="isEditClaimModalVisible" 
        :claim-id-to-edit="claimIdToEdit" 
        @close="closeEditClaimModal" />
        <DeleteModal v-if="isDeleteModalVisible" 
        @delete="confirmDelete" 
        @close="closeDeleteModal" />
      </div>
      
    </div>
    
  </main>
</template>