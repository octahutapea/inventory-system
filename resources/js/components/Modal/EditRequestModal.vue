<script setup>

import { ref, onMounted, defineEmits, defineProps } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

const props = defineProps(['claim-id-to-edit']);

const user_id = localStorage.getItem('id');
const claim_location = ref("");
const facility_type = ref("");
const claim_imei = ref("");
const claim_date = ref("");
const claim_value = ref("");
const claim_document = ref("");
const claim_status = "Proses Admin";

// Fetch detail data
onMounted( async () => {

//fetch detail data post by ID
await api.get(`/api/claims/${props.claimIdToEdit}`)
  .then(response => {
    
    //set response data to state
    claim_location.value = response.data.data.claim_location
    facility_type.value = response.data.data.facility_type
    claim_imei.value = response.data.data.claim_imei
    claim_date.value = response.data.data.claim_date
    claim_value.value = response.data.data.claim_value
    claim_document.value = response.data.data.claim_document

    console.log(response)
    console.log(props.claimIdToEdit)
  });
})

const changeFile = (e) => {
  const file = e.target.files[0];
  // Implement logic to handle the selected file as needed
  claim_document.value = file; // Assuming you want to store the file object in claim.claim_document
};

const submit = async () => {
    let formData = new FormData();

        //assign state value to formData
        formData.append("user_id", user_id);
        formData.append("claim_location", claim_location.value);
        formData.append("facility_type", facility_type.value);
        formData.append("claim_imei", claim_imei.value);
        formData.append("claim_date", claim_date.value);
        formData.append("claim_value", claim_value.value);
        formData.append("claim_document", claim_document.value);
        formData.append("claim_status", claim_status);
        formData.append("_method", "PATCH");

    // API STORE DATA
    await api.post(`/api/claims/${props.claimIdToEdit}`, formData)

    .then(response => {
    location.reload();
    
    console.log(response)
    });
};

</script>

<template>
  <div class="modal">
    <div class="modal-content">
      <slot>
        <form @submit.prevent="submit">
          <h1 class="m-4 font-bold text-xl flex justify-center">Edit Claim</h1>
          <div class="grid grid-cols-2">
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="claim_location" placeholder="Lokasi" />
            <select class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="facility_type">
              <option disabled value="">Jenis Fasilitas</option>
              <option>Handphone</option>
            </select>
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="claim_imei" placeholder="Nomor IMEI" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="claim_date" type="date" placeholder="Tanggal Klaim" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="claim_value" placeholder= "Nilai Klaim" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" @change="changeFile($event)" type="file" placeholder= "Dokumen" />
          </div>

          <div class="flex justify-between">
            <button class="m-4 w-20 flex items-center justify-center py-1 px-2 border rounded-xl border-black font-bold" @click="$emit('close')">Batal</button>
            <button class="m-4 w-20 flex items-center justify-center py-1 px-2 border rounded-xl bg-primary text-white font-bold" type="submit">Simpan</button>
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
  