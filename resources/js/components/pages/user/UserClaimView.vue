<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../../api';
import CreateClaimModal from '../../Modal/CreateRequestModal.vue'
import DeleteModal from '../../Modal/DeleteModal.vue'
import EditClaimModal from '../../Modal/EditRequestModal.vue'

// API GET CLAIM
const id = localStorage.getItem('id');
const claims = ref([]);
const fetchDataItems = async () => {
  await api.get(`/api/user_claims/${id}`)

  .then(response => {

      claims.value = response.data.data
      console.log(claims.value)

  });
}

onMounted(() => {
  fetchDataItems();
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

// API DELETE CLAIM
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

</script>

<template>
  <navbar />
  <user-sidebar />

  <main class="ml-48 mt-32">
    <div class="bg-white m-8 pt-1 rounded-3xl shadow-sm">
        <div class="flex justify-end">
          <add-button @click="showCreateClaimModal" />
          <CreateClaimModal v-if="isCreateClaimModalVisible" @close="closeCreateClaimModal" />
        </div>
      
      <table class="table-auto w-full text-left">
        <thead class="bg-primary/20 rounded-3xl">
          <tr>
            <th class="p-3">No</th>
            <th class="p-3">Jenis Fasilitas</th>
            <th class="p-3">Nomor IMEI</th>
            <th class="p-3">Tanggal Klaim</th>
            <th class="p-3">Nilai Klaim</th>
            <th class="p-3">Dokumen</th>
            <th class="p-3">Status</th>
            <th></th>
            <!-- <th></th> -->
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(claim, index) in claims" :key="claim.id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ claim.facility_type }}</td>
            <td class="p-3">{{ claim.claim_imei }}</td>
            <td class="p-3">{{ claim.claim_date }}</td>
            <td class="p-3">{{ filters.formatMoney(claim.claim_value) }}</td>

            <td class="p-3">
              <button @click="downloadDocument(claim.claim_document)">
                <img src="../../icons/Document.svg" alt="Dokumen">
              </button>
            </td>

            <td class="p-3">{{ claim.claim_status }}</td>
            <!-- <td>
                <button @click.prevent="showContract(company.id)" >
                    <img class="w-5 h-5" src="../icons/Detail.svg" alt="">
                </button>
            </td> -->
            <td>
                <button v-if="claim.claim_status == 'Proses Admin'" @click.prevent="showEditClaimModal(claim.id)">
                    <img class="w-5 h-5" src="../../icons/Edit.svg" alt="">
                </button>

                <button v-if="claim.claim_status == 'Proses Admin'" @click.prevent="showDeleteModal(claim.id)">
                    <img class="w-5 h-5" src="../../icons/Delete.svg" alt="">
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
    
  </main>
</template>