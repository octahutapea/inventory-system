<script setup>

import { ref, defineEmits, defineProps, onMounted } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

// const props = defineProps(['user-id']);

const filename = 'inventory_company_template.xlsx';

// API DOWNLOAD TEMPLATE COMPANY
const downloadTemplate = async () => {
  try {
    console.log('Starting download for filename:', filename);
    const response = await api.get(`/api/download/${filename}`, {
      responseType: 'blob', // Set responseType ke 'blob' untuk menangani data biner (binary data)
    });

    // Buat URL objek dari blob data
    const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const url = window.URL.createObjectURL(blob);

    // Buat elemen <a> untuk menautkan ke URL dan otomatis unduh file
    const a = document.createElement('a');
    a.href = url;
    a.download = 'inventory_company_template.xlsx';

    // Tambahkan elemen <a> ke body dokumen dan klik secara otomatis
    document.body.appendChild(a);
    a.click();

    // Hapus elemen <a> setelah selesai
    document.body.removeChild(a);
  } catch (error) {
    console.error('Error exporting Excel:', error);
  }
};

const selectedFile = ref("");

const changeFile = (e) => {
  const file = e.target.files[0];
  // Implement logic to handle the selected file as needed
  selectedFile.value = file; // Assuming you want to store the file object in claim.claim_document
  console.log(selectedFile.value)
};

const submit = async () => {
  try {
    let formData = new FormData();
    formData.append("file", selectedFile.value);
    formData.append("_method", "POST");

    const response = await api.post('/api/companies_import', formData);
    console.log(response);
    location.reload();
  } catch (error) {
    console.error('Error submitting the form:', error);
  }
};

</script>

<template>
  <div class="modal">
    <div class="modal-content">
      <slot>
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <h1 class="m-4 font-bold text-xl flex justify-center">Unggah Dokumen</h1>
          <div>
            <p class="m-4 w-56">Unggah dokumen menggunakan <span>
              <button type="button" @click="downloadTemplate" class="text-primary underline">template ini.</button>
            </span></p>
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" @change="changeFile($event)" type="file" placeholder= "Dokumen" />
            
          </div>

          <div class="flex justify-between">
            <button class="m-4 w-20 flex items-center justify-center py-1 px-2 border border-black rounded-xl font-bold" @click="$emit('close')">Batal</button>
            <submit-button />
          </div>
        </form>

      </slot> 
    </div>
  </div>
</template>

<style scoped>
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 20px;
  }
</style>
  