<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../api';
import CreateContractModal from '../Modal/CreateContractModal.vue'
import DeleteModal from '../Modal/DeleteModal.vue'
import EditContractModal from '../Modal/EditContractModal.vue'
// import InputContractModal from '../Modal/InputContractModal.vue'
import ImportContractModal from '../Modal/ImportContractModal.vue'

// API GET CONTRACT BY COMPANY ID
const contracts = ref([]);
const company_name = ref('');
const route = useRoute();
const router = useRouter();

const fetchDataItems = async () => {
    await api.get(`/api/detail_contracts/${route.params.companyId}`)

    .then(response => {
        console.log(route.params.companyId)
        contracts.value = response.data.data
        company_name.value = response.data.data[0].company_name
        console.log(response)

    });
}

onMounted(() => {
    fetchDataItems();
});

// CONTRACT MODAL FOR CREATE
const isCreateContractModalVisible = ref(false);
const companyId = route.params.companyId;

const showCreateContractModal = (companyId) => {
    isCreateContractModalVisible.value = true;
}
const closeCreateContractModal = () => {
    isCreateContractModalVisible.value = false;
}

// SHOW ACQUISITION VALUE
const contractIdToShow = ref(null);

const showAcquisitionValue = (contract_id) => {
    contractIdToShow.value = contract_id;
    router.push({ name: 'detailacquisitionvalue', params: { contractId: contract_id } });
}

// CONTRACT MODAL FOR EDIT
const isEditContractModalVisible = ref(false);
const contractIdToEdit = ref(null);

const showEditContractModal = (id) => {
    isEditContractModalVisible.value = true;
    contractIdToEdit.value = id;
}
const closeEditContractModal = () => {
    isEditContractModalVisible.value = false;
}

// SHOW INVOICE
// const contractIdToShow = ref(null);

const showInvoice = (contract_id) => {
    contractIdToShow.value = contract_id;
    router.push({ name: 'contractinvoice', params: { contractId: contract_id } });
}

// CONTRACT MODAL FOR DELETE
const isDeleteModalVisible = ref(false);
const idToDelete = ref(null);

const showDeleteModal = (id) => {
    idToDelete.value = id;
    isDeleteModalVisible.value = true;
}
const closeDeleteModal = () => {
    isDeleteModalVisible.value = false;
}

// API DELETE CONTRACT
const confirmDelete = async () => {
  await api.delete(`/api/contracts/${idToDelete.value}`)

    .then(response => {
      location.reload();
      console.log(response)
    });
};

// CONTRACT MODAL FOR INPUT
const isInputContractModalVisible = ref(false);
const contractIdToInput = ref(null);

const showInputContractModal = (id) => {
    isInputContractModalVisible.value = true;
    contractIdToInput.value = id;
}
const closeInputContractModal = () => {
    isInputContractModalVisible.value = false;
}

// CONTRACT MODAL FOR IMPORT
const isImportModalVisible = ref(false);

const showImportModal = (companyId) => {
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
          <h3 class="w-fit flex items-center m-4 px-2 text-lg font-semibold">Daftar Kontrak pada {{ company_name }}</h3>

          <div class="flex">
            <import-button @click="showImportModal" />
            <ImportContractModal v-if="isImportModalVisible" :company-id="companyId" @close="closeImportModal" />

            <add-button @click="showCreateContractModal" />
            <CreateContractModal v-if="isCreateContractModalVisible" :company-id="companyId" @close="closeCreateContractModal" />
          </div>
          
        </div>
      
      <div class="overflow-x-scroll">
        <table class="table-auto w-full text-left">
          <thead class="bg-primary/20 rounded-3xl">
            <tr>
              <th class="p-3">No</th>
              <th class="p-3">Nomor Kontrak</th>
              <th class="p-3">Tanggal Mulai</th>
              <th class="p-3">Tanggal Selesai</th>
              <th class="p-3">Perangkat</th>
              <th class="p-3">Nilai Kontrak</th>
              <th class="p-3">Tipe Kontrak</th>
              <th class="p-3">Jenis Perangkat</th>
              <th class="p-3">Keterangan</th>
              <th class="p-3">Status</th>
              <th class="p-3"></th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t border-dark-color/10" v-for="(contract, index) in contracts" :key="contract.company_id">
              <td class="p-3">{{ index + 1 }}</td>
              <td class="p-3">{{ contract.contract_number }}</td>
              <td class="p-3">{{ contract.contract_start_date }}</td>
              <td class="p-3">{{ contract.contract_end_date }}</td>
              <td class="p-3">{{ contract.item_name }}</td>
              <td class="p-3">{{ filters.formatMoney(contract.contract_value) }}</td>
              <td class="p-3">{{ contract.contract_type }}</td>
              <td class="p-3">{{ contract.item_type }}</td>
              <td class="p-3">{{ contract.contract_note }}</td>
              <td class="p-3">{{ contract.contract_status }}</td>
              <td class="p-3">
                <button @click.prevent="showAcquisitionValue(contract.id)">
                  <img class="w-6 h-6" src="../icons/Detail.svg" alt="">
                </button>
                <button @click.prevent="showEditContractModal(contract.id)">
                  <img class="w-6 h-6" src="../icons/Edit.svg" alt="">
                </button>
                <button @click.prevent="showInvoice(contract.id)">
                  <img class="w-6 h-6" src="../icons/Input.svg" alt="">
                </button>
                <!-- <button @click.prevent="showEditContractModal(contract.id)">
                  <img class="w-6 h-6" src="../icons/History.svg" alt="">
                </button> -->
                <!-- <button @click.prevent="showDeleteModal(contract.id)">
                  <img class="w-6 h-6" src="../icons/Delete.svg" alt="">
                </button> -->
              </td>
            </tr>
          </tbody>
        </table>
        <EditContractModal v-if="isEditContractModalVisible" 
        :contract-id-to-edit="contractIdToEdit" 
        @close="closeEditContractModal" />
        <InputContractModal v-if="isInputContractModalVisible" 
        :contract-id-to-input="contractIdToInput" 
        @close="closeInputContractModal" />
        <DeleteModal v-if="isDeleteModalVisible" 
        @delete="confirmDelete" 
        @close="closeDeleteModal" />
      </div>
      
    </div>
    
  </main>
</template>
