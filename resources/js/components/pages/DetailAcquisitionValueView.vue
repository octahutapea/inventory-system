<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../api';
import CreateAcquisitionValueModal from '../Modal/CreateAcquisitionValueModal.vue'
import DeleteModal from '../Modal/DeleteModal.vue'
import EditAcquisitionValueModal from '../Modal/EditAcquisitionValueModal.vue'
import ImportAcquisitionValueModal from '../Modal/ImportAcquisitionValueModal.vue'

// API GET ACQUISITION VALUE BY CONTRACT ID
const acquisition_values = ref([]);
const contract_number = ref('');
const route = useRoute();
const router = useRouter();

const fetchDataItems = async () => {
    await api.get(`/api/detail_acquisition_values/${route.params.contractId}`)

    .then(response => {
      console.log(route.params.contractId)
      acquisition_values.value = response.data.data
      contract_number.value = response.data.data[0].contract_number

    });
}

onMounted(() => {
    fetchDataItems();
});

// ACQUISITION VALUE MODAL FOR CREATE
const isCreateAcquisitionValueModalVisible = ref(false);
const contractId = route.params.contractId;

const showCreateAcquisitionValueModal = (contractId) => {
    isCreateAcquisitionValueModalVisible.value = true;
}
const closeCreateAcquisitionValueModal = () => {
    isCreateAcquisitionValueModalVisible.value = false;
}

// SHOW ITEM
const itemIdToShow = ref(null);

const showItem = (acquisition_value_id) => {
    itemIdToShow.value = acquisition_value_id;
    router.push({ name: 'detailitem', params: { acquisitionValueId: acquisition_value_id } });
}

// ACQUISITION VALUE MODAL FOR EDIT
const isEditAcquisitionValueModalVisible = ref(false);
const acquisitionValueIdToEdit = ref(null);

const showEditAcquisitionValueModal = (id) => {
    isEditAcquisitionValueModalVisible.value = true;
    acquisitionValueIdToEdit.value = id;
}
const closeEditAcquisitionValueModal = () => {
    isEditAcquisitionValueModalVisible.value = false;
}

// ACQUISITION VALUE MODAL FOR DELETE
const isDeleteModalVisible = ref(false);
const idToDelete = ref(null);

const showDeleteModal = (id) => {
    idToDelete.value = id;
    isDeleteModalVisible.value = true;
}
const closeDeleteModal = () => {
    isDeleteModalVisible.value = false;
}

// API DELETE ACQUISITION VALUE
const confirmDelete = async () => {
  await api.delete(`/api/acquisition_values/${idToDelete.value}`)

    .then(response => {
      location.reload();
      console.log(idToDelete.value)
      console.log(response)
    });
};

// ACQUISITION VALUE MODAL FOR IMPORT
const isImportModalVisible = ref(false);

const showImportModal = (contractId) => {
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
          <h3 class="w-fit flex items-center m-4 px-2 text-lg font-semibold">Daftar Nilai Perolehan pada {{ contract_number }} </h3>

          <div class="flex">
            <import-button @click="showImportModal" />
            <ImportAcquisitionValueModal v-if="isImportModalVisible" :contract-id="contractId" @close="closeImportModal" />

            <add-button @click="showCreateAcquisitionValueModal" />
            <CreateAcquisitionValueModal v-if="isCreateAcquisitionValueModalVisible" :contract-id="contractId" @close="closeCreateAcquisitionValueModal" />
          </div>
          
        </div>
      
      <table class="table-auto w-full text-left">
        <thead class="bg-primary/20 rounded-3xl">
          <tr>
            <th class="p-3">No</th>
            <th class="p-3">Nomor Kontrak</th>
            <th class="p-3">Tanggal Mulai</th>
            <th class="p-3">Tanggal Selesai</th>
            <th class="p-3">Perangkat</th>
            <th class="p-3">Merk</th>
            <th class="p-3">Tipe</th>
            <th class="p-3">Total</th>
            <th class="p-3">Nilai Perolehan</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(acquisition_value, index) in acquisition_values" :key="acquisition_value.contract_id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ acquisition_value.contract_number }}</td>
            <td class="p-3">{{ acquisition_value.contract_start_date }}</td>
            <td class="p-3">{{ acquisition_value.contract_end_date }}</td>
            <td class="p-3">{{ acquisition_value.item_name }}</td>
            <td class="p-3">{{ acquisition_value.item_merk }}</td>
            <td class="p-3">{{ acquisition_value.item_type }}</td>
            <td class="p-3">{{ acquisition_value.item_total }}</td>
            <td class="p-3">{{ filters.formatMoney(acquisition_value.item_value) }}</td>
            <td>
              <button @click.prevent="showItem(acquisition_value.id)">
                <img class="w-6 h-6" src="../icons/Detail.svg" alt="">
              </button>
              <button @click.prevent="showEditAcquisitionValueModal(acquisition_value.id)">
                <img class="w-6 h-6" src="../icons/Edit.svg" alt="">
              </button>
              <button @click.prevent="showDeleteModal(acquisition_value.id)">
                <img class="w-6 h-6" src="../icons/Delete.svg" alt="">
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <EditAcquisitionValueModal v-if="isEditAcquisitionValueModalVisible" 
      :acquisition-value-id-to-edit="acquisitionValueIdToEdit" 
      @close="closeEditAcquisitionValueModal" />
      <DeleteModal v-if="isDeleteModalVisible" 
      @delete="confirmDelete" 
      @close="closeDeleteModal" />
    </div>
    
  </main>
</template>
