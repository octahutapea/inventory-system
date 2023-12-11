<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../api';
import CreatePaymentModal from '../Modal/CreatePaymentModal.vue'
import DeleteModal from '../Modal/DeleteModal.vue'
import ImportPaymentModal from '../Modal/ImportPaymentModal.vue'
import DoneModal from '../Modal/DoneModal.vue'

// API GET PAYMENT BY CONTRACT ID
const payments = ref([]);
const contract_number = ref('');
const route = useRoute();
const router = useRouter();

const fetchDataItems = async () => {
    await api.get(`/api/payment/${route.params.contractId}`)

    .then(response => {
      console.log(route.params.contractId)
      payments.value = response.data.data

    if (response.data.data[0]) {
          if (response.data.data[0].contract_number) {
            contract_number.value = response.data.data[0].contract_number
          } else {
            contract_number.value = " ";
          }
        }

    });
}

onMounted(() => {
    fetchDataItems();
});

// PAYMENT MODAL FOR CREATE
const isCreatePaymentModalVisible = ref(false);
const contractId = route.params.contractId;

const showCreatePaymentModal = (contractId) => {
    isCreatePaymentModalVisible.value = true;
}
const closeCreatePaymentModal = () => {
    isCreatePaymentModalVisible.value = false;
}

// DONE MODAL
const isDoneModalVisible = ref(false);
const idToDone = ref(null);

const showDoneModal = (id) => {
    idToDone.value = id;
    console.log("idToDone : ", idToDone.value)
    isDoneModalVisible.value = true;
}
const closeDoneModal = () => {
    isDoneModalVisible.value = false;
}

// ACQUISITION VALUE MODAL FOR IMPORT
const isImportModalVisible = ref(false);

const showImportModal = (contractId) => {
  isImportModalVisible.value = true;
}
const closeImportModal = () => {
    isImportModalVisible.value = false;
}

const downloadDocument = async (filename) => {
  try {
    const response = await api.get(`/api/open-invoice/${filename}`, {
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
  <sidebar />

  <main class="ml-48 mt-32">
    <div class="bg-white m-8 pt-1 rounded-3xl shadow-sm">
        <div class="flex justify-between">
          <h3 class="w-fit flex items-center m-4 px-2 text-lg font-semibold">Daftar Tagihan pada {{ contract_number }} </h3>

          <div class="flex">
            <import-button @click="showImportModal" />
            <ImportPaymentModal v-if="isImportModalVisible" :contract-id="contractId" @close="closeImportModal" />

            <add-button @click="showCreatePaymentModal" />
            <CreatePaymentModal v-if="isCreatePaymentModalVisible" :contract-id="contractId" @close="closeCreatePaymentModal" />
          </div>
          
        </div>
      
      <table class="table-auto w-full text-left">
        <thead class="bg-primary/20 rounded-3xl">
          <tr>
            <th class="p-3">No</th>
            <th class="p-3">Tagihan Periode</th>
            <th class="p-3">Keterangan</th>
            <th class="p-3">Periode Mulai</th>
            <th class="p-3">Periode Akhir</th>
            <th class="p-3">Rincian</th>
            <th class="p-3">Total</th>
            <th class="p-3">Dokumen Tagihan</th>
            <th class="p-3">Status Pembayaran</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(payment, index) in payments" :key="payment.contract_id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ payment.period }}</td>
            <td class="p-3">{{ payment.payment_note }}</td>
            <td class="p-3">{{ payment.period_start }}</td>
            <td class="p-3">{{ payment.period_end }}</td>
            <td class="p-3">{{ payment.details }}</td>
            <td class="p-3">{{ payment.total }}</td>
            <!-- <td class="p-3">{{ payment.invoice }}</td> -->
            <td class="p-3">
                <button v-if="payment.invoice" @click="downloadDocument(payment.invoice)">
                  <img src="../icons/Document.svg" alt="Dokumen">
                </button>
            </td>

            <td class="p-3">{{ payment.payment_status }}</td>
            <td>
                <button v-if="payment.payment_status == 'Belum'" @click.prevent="showDoneModal(payment.id)">
                    <img class="w-6 h-6" src="../icons/Accept.svg" alt="">
                </button>
            </td>
          </tr>
        </tbody>
      </table>
      <DoneModal v-if="isDoneModalVisible" :id-to-done="idToDone" @close="closeDoneModal" />
    </div>
    
  </main>
</template>
