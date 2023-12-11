<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../../api';

// API GET CLAIM
const id = localStorage.getItem('id');
const claims = ref([]);
const fetchDataItems = async () => {
  await api.get(`/api/user_history/${id}`)

  .then(response => {

      claims.value = response.data.data
      console.log(claims.value)

  });
}

onMounted(() => {
  fetchDataItems();
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

</script>

<template>
  <navbar />
  <user-sidebar />

  <main class="ml-48 mt-32">
    <div class="bg-white m-8 pt-1 rounded-3xl shadow-sm">
        <div class="flex justify-between">
          <search />
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
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(claim, index) in claims" :key="claim.id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ claim.facility_type }}</td>
            <td class="p-3">{{ claim.claim_imei }}</td>
            <td class="p-3">{{ claim.claim_date }}</td>
            <td class="p-3">{{ claim.claim_value }}</td>

            <td class="p-3">
              <button @click="downloadDocument(claim.claim_document)">
                <img src="../../icons/Document.svg" alt="Dokumen">
              </button>
            </td>

            <td class="p-3">{{ claim.claim_status }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    
  </main>
</template>