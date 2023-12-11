<script setup>
import { ref, onMounted, watch } from 'vue';
import api from '../../api';

const items = ref([]);
const fetchDataItems = async () => {
    await api.get('/api/items')

    .then(response => {

        //set response data to state "posts"
        items.value = response.data.data
        console.log("item = ", items.value)

    });
}

onMounted(() => {
  fetchDataItems();
});

// SEARCH
const keyword = ref("");

// API GET COMPANY
const items_search = ref([]);
const items_count = ref(0);
const search = async () => {
    await api.get(`/api/item_search/${keyword.value}`)

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

// API EXPORT ITEM
const exportExcel = async () => {
  try {
    const response = await api.get('/api/items_export', {
      responseType: 'blob', // Set responseType ke 'blob' untuk menangani data biner (binary data)
    });

    // Buat URL objek dari blob data
    const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const url = window.URL.createObjectURL(blob);

    // Buat elemen <a> untuk menautkan ke URL dan otomatis unduh file
    const a = document.createElement('a');
    a.href = url;
    a.download = 'item.xlsx';

    // Tambahkan elemen <a> ke body dokumen dan klik secara otomatis
    document.body.appendChild(a);
    a.click();

    // Hapus elemen <a> setelah selesai
    document.body.removeChild(a);
  } catch (error) {
    console.error('Error exporting Excel:', error);
  }
};

const downloadDocument = async (filename) => {
  try {
    const response = await api.get(`/api/open-image/${filename}`, {
      responseType: 'blob', // Set responseType ke 'blob' untuk menangani data biner (binary data)
    });

    const contentType = response.headers['content-type'];
    const extension = filename.split('.').pop();
    const imageType = contentType.startsWith('image') ? contentType : `image/${extension}`;

    // Buat URL objek dari blob data
    const blob = new Blob([response.data], { type: imageType });
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
            <th class="p-3">Area</th>
            <th class="p-3">Instansi</th>
            <th class="p-3">Perangkat</th>
            <th class="p-3">Merk</th>
            <th class="p-3">Tipe</th>
            <th class="p-3">Nomor Serial</th>
            <th class="p-3">Nama Pengguna</th>
            <th class="p-3">Jabatan</th>
            <th class="p-3">Lokasi</th>
            <th class="p-3">Gambar</th>
            <th class="p-3">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(item, index) in (keyword !== '' ? items_search : items)" :key="item.id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ item.item_area }}</td>
            <td class="p-3">{{ item.company_name }}</td>
            <td class="p-3">{{ item.item_name }}</td>
            <td class="p-3">{{ item.item_merk }}</td>
            <td class="p-3">{{ item.item_type }}</td>
            <td class="p-3">{{ item.serial_number }}</td>
            <td class="p-3">{{ item.name }}</td>
            <td class="p-3">{{ item.positiontext }}</td>
            <td class="p-3">{{ item.item_location }}</td>
            <td class="p-3">
              <button v-if="item.item_pict" @click="downloadDocument(item.item_pict)">
                <img class="w-6 h-6" src="../icons/Image.svg" alt="Dokumen">
              </button>
            </td>
            <td class="p-3">{{ item.item_status }}</td>
          </tr>
          
        </tbody>
      </table>
    </div>
    
  </main>
</template>

<style>

</style>
