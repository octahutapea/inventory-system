<script setup>

import { ref, defineEmits, defineProps } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

const props = defineProps(['id-to-done']);

console.log("id : ", props.idToDone)

const selectedFile = ref("null");

const changeFile = (e) => {
  const file = e.target.files[0];
  // Implement logic to handle the selected file as needed
  selectedFile.value = file; // Assuming you want to store the file object in claim.claim_document
};

const submit = async () => {
  try {
    let formData = new FormData();
    formData.append("invoice", selectedFile.value);
    formData.append("_method", "PUT");

    const response = await api.post(`/api/payment_update/${props.idToDone}`, formData);
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
          <h1 class="m-4 font-bold text-xl flex justify-center">Unggah Invoice</h1>
          <div>
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
  