<script setup>
import { ref, onMounted, watch } from 'vue';
import api from '../../api';

const contracts = ref([]);
const fetchDataItems = async () => {
    await api.get('/api/contracts')

    .then(response => {

        contracts.value = response.data.data
        console.log(contracts.value)

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
    await api.get(`/api/contract_search/${keyword.value}`)

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

// API EXPORT CONTRACT
const exportExcel = async () => {
  try {
    const response = await api.get('/api/contracts_export', {
      responseType: 'blob', // Set responseType ke 'blob' untuk menangani data biner (binary data)
    });

    // Buat URL objek dari blob data
    const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const url = window.URL.createObjectURL(blob);

    // Buat elemen <a> untuk menautkan ke URL dan otomatis unduh file
    const a = document.createElement('a');
    a.href = url;
    a.download = 'contract.xlsx';

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
            <th class="p-3">Nomor Kontrak</th>
            <th class="p-3">Tanggal Mulai</th>
            <th class="p-3">Tanggal Selesai</th>
            <th class="p-3">Perangkat</th>
            <th class="p-3">Nilai Kontrak</th>
            <th class="p-3">Tipe Kontrak</th>
            <th class="p-3">Jenis Perangkat</th>
            <th class="p-3">Keterangan</th>
            <th class="p-3">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(contract, index) in (keyword !== '' ? items_search : contracts)" :key="contract.id">
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
          </tr>
          
        </tbody>
      </table>
    </div>
    
  </main>
</template>
  

  