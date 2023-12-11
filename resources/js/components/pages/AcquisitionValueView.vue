<script setup>
import { ref, onMounted, watch } from 'vue';
import api from '../../api';

const acquisition_values = ref([]);
const fetchDataItems = async () => {
    await api.get('/api/acquisition_values')

    .then(response => {

        acquisition_values.value = response.data.data
        console.log(acquisition_values.value)

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
    await api.get(`/api/acquisition_value_search/${keyword.value}`)

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

// API EXPORT ACQUISITION VALUE
const exportExcel = async () => {
  try {
    const response = await api.get('/api/acquisition_value_export', {
      responseType: 'blob', // Set responseType ke 'blob' untuk menangani data biner (binary data)
    });

    // Buat URL objek dari blob data
    const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const url = window.URL.createObjectURL(blob);

    // Buat elemen <a> untuk menautkan ke URL dan otomatis unduh file
    const a = document.createElement('a');
    a.href = url;
    a.download = 'acquisition_value.xlsx';

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
    <div class="bg-white m-8 rounded-3xl shadow-sm">
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
            <th class="p-3">Merk</th>
            <th class="p-3">Tipe</th>
            <th class="p-3">Total</th>
            <th class="p-3">Nilai Perolehan</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(acquisition_value, index) in (keyword !== '' ? items_search : acquisition_values)" :key="acquisition_value.id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ acquisition_value.contract_number }}</td>
            <td class="p-3">{{ acquisition_value.contract_start_date }}</td>
            <td class="p-3">{{ acquisition_value.contract_end_date }}</td>
            <td class="p-3">{{ acquisition_value.item_name }}</td>
            <td class="p-3">{{ acquisition_value.item_merk }}</td>
            <td class="p-3">{{ acquisition_value.item_type }}</td>
            <td class="p-3">{{ acquisition_value.item_total }}</td>
            <td class="p-3">{{ filters.formatMoney(acquisition_value.item_value) }}</td>
          </tr>
          
        </tbody>
      </table>
    </div>
    
  </main>
</template>
  
