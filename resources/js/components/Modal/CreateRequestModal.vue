<script setup>

import { ref, defineEmits, defineProps } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

const id = localStorage.getItem('id');

const user_id = ref("");
const claim_location = ref("");
const facility_type = ref("");
const claim_imei = ref("");
const claim_date = ref("");
const claim_value = ref("");
const claim_document = ref("null");
const claim_status = ref("Proses Admin");
const isChecked = ref(false);

const changeFile = (e) => {
  const file = e.target.files[0];
  // Implement logic to handle the selected file as needed
  claim_document.value = file; // Assuming you want to store the file object in claim.claim_document
};

const submit = async () => {
  let formData = new FormData();

    //assign state value to formData
    formData.append("user_id", id);
    formData.append("claim_location", claim_location.value);
    formData.append("facility_type", facility_type.value);
    formData.append("claim_imei", claim_imei.value);
    formData.append("claim_date", claim_date.value);
    formData.append("claim_value", claim_value.value);
    formData.append("claim_document", claim_document.value);
    formData.append("claim_status", claim_status.value);
    formData.append("_method", "POST");

  await api.post('/api/claims', formData)

    .then(response => {
      console.log(response)
      // location.reload();
    });
};

</script>

<template>
  <div class="modal">
    <div class="modal-content">
      <slot>
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <h1 class="m-4 font-bold text-xl flex justify-center">Tambahkan Klaim</h1>
          <div class="w-64">
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="claim_location" placeholder="Lokasi" />
            <!-- <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="facility_type" placeholder="Jenis Fasilitas" /> -->
            <select class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="facility_type">
              <option disabled value="">Jenis Fasilitas</option>
              <option>Handphone</option>
            </select>
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="claim_imei" placeholder= "Nomor IMEI" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="claim_date" type="date" placeholder="Tanggal Klaim" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="claim_value" placeholder="Nilai Klaim" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" @change="changeFile($event)" type="file" placeholder= "Dokumen" />

            <label class="mx-4 my-2 w-56 break-normal">
              <input type="checkbox" v-model="isChecked" />
              Saya menyatakan bahwa fasilitas jabatan yang saya klaim adalah benar digunakan untuk menunjang pekerjaan dan untuk kepentingan perusahaan.
            </label>
          </div>

          <div class="flex justify-between">
            <button class="m-4 w-20 flex items-center justify-center py-1 px-2 border border-black rounded-xl font-bold" @click="$emit('close')">Batal</button>
            <submit-button :disabled="!isChecked" />
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
  