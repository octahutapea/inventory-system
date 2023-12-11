<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../api';
import CompanyModal from '../Modal/CompanyModal.vue'
import DeleteModal from '../Modal/DeleteModal.vue'
import EditCompanyModal from '../Modal/EditCompanyModal.vue'
import ImportCompanyModal from '../Modal/ImportCompanyModal.vue'

// API GET COMPANY
const companies = ref([]);
const fetchDataItems = async () => {
    await api.get('/api/companies')

    .then(response => {

        companies.value = response.data.data
        console.log(companies.value)

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
    await api.get(`/api/company_search/${keyword.value}`)

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


// COMPANY MODAL FOR CREATE
const isCompanyModalVisible = ref(false);

const showCompanyModal = () => {
    isCompanyModalVisible.value = true;
}
const closeCompanyModal = () => {
    isCompanyModalVisible.value = false;
}

// SHOW CONTRACT
const router = useRouter();

const showContract = (company_id) => {
    router.push({ name: 'detailcontract', params: { companyId: company_id } });
}

// COMPANY MODAL FOR EDIT
const isEditCompanyModalVisible = ref(false);
const companyIdToEdit = ref(null);

const showEditCompanyModal = (id) => {
    isEditCompanyModalVisible.value = true;
    companyIdToEdit.value = id;
}
const closeEditCompanyModal = () => {
    isEditCompanyModalVisible.value = false;
}

// COMPANY MODAL FOR DELETE
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
  await api.delete(`/api/companies/${idToDelete.value}`)

    .then(response => {
      location.reload();
      console.log(response)
    });
};

// API EXPORT COMPANY
const exportExcel = async () => {
  try {
    const response = await api.get('/api/companies_export', {
      responseType: 'blob', // Set responseType ke 'blob' untuk menangani data biner (binary data)
    });

    // Buat URL objek dari blob data
    const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const url = window.URL.createObjectURL(blob);

    // Buat elemen <a> untuk menautkan ke URL dan otomatis unduh file
    const a = document.createElement('a');
    a.href = url;
    a.download = 'company.xlsx';

    // Tambahkan elemen <a> ke body dokumen dan klik secara otomatis
    document.body.appendChild(a);
    a.click();

    // Hapus elemen <a> setelah selesai
    document.body.removeChild(a);
  } catch (error) {
    console.error('Error exporting Excel:', error);
  }
};

// COMPANY MODAL FOR IMPORT
const isImportModalVisible = ref(false);

const showImportModal = () => {
    isImportModalVisible.value = true;
}
const closeImportModal = () => {
    isImportModalVisible.value = false;
}

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
            <import-button @click="showImportModal" />
            <ImportCompanyModal v-if="isImportModalVisible" @close="closeImportModal" />

            <export-button @click="exportExcel" />

            <add-button @click="showCompanyModal" />
            <!-- <button class="w-20 flex items-center justify-center m-4 px-4 py-1 border rounded-2xl bg-blue text-white font-bold"
            @click="showCompanyModal">Tambah</button> -->
            <CompanyModal v-if="isCompanyModalVisible" @close="closeCompanyModal" />
          </div>
          
        </div>
      
      <table class="table-auto w-full text-left">
        <thead class="bg-primary/20 rounded-3xl">
          <tr>
            <th class="p-3">No</th>
            <th class="p-3">Nama Perusahaan</th>
            <th class="p-3">Alamat</th>
            <th class="p-3">Telepon</th>
            <th class="p-3">PIC</th>
            <th class="p-3">Total Kontrak</th>
            <th class="p-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(company, index) in (keyword !== '' ? items_search : companies)" :key="company.id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ company.company_name }}</td>
            <td class="p-3">{{ company.company_address }}</td>
            <td class="p-3">{{ company.company_telephone }}</td>
            <td class="p-3">{{ company.company_pic }}</td>
            <td class="p-3">{{ company.contractCount }}</td>
            <td class="p-3">
                <button @click.prevent="showContract(company.id)" >
                    <img class="w-6 h-6" src="../icons/Detail.svg" alt="">
                </button>
                <button @click.prevent="showEditCompanyModal(company.id)">
                    <img class="w-6 h-6" src="../icons/Edit.svg" alt="">
                </button>
                <button @click.prevent="showDeleteModal(company.id)">
                    <img class="w-6 h-6" src="../icons/Delete.svg" alt="">
                </button>
            </td>
          </tr>
        </tbody>
      </table>
      <EditCompanyModal v-if="isEditCompanyModalVisible" 
      :company-id-to-edit="companyIdToEdit" 
      @close="closeEditCompanyModal" />
      <DeleteModal v-if="isDeleteModalVisible" 
      @delete="confirmDelete" 
      @close="closeDeleteModal" />
    </div>
    
  </main>
</template>
  

  