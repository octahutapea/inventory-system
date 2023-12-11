<script setup>

import { ref, onMounted, defineEmits, defineProps } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

const props = defineProps(['company-id-to-edit']);

const company_name = ref("");
const company_address = ref("");
const company_telephone = ref("");
const company_pic = ref("");

// Fetch detail data
onMounted( async () => {

//fetch detail data post by ID
await api.get(`/api/companies/${props.companyIdToEdit}`)
.then(response => {
    
    //set response data to state
    company_name.value = response.data.data.company_name
    company_address.value = response.data.data.company_address
    company_telephone.value = response.data.data.company_telephone
    company_pic.value = response.data.data.company_pic

    console.log(response)
});
})

const submit = async () => {
    let formData = new FormData();

        //assign state value to formData
        formData.append("company_name", company_name.value);
        formData.append("company_address", company_address.value);
        formData.append("company_telephone", company_telephone.value);
        formData.append("company_pic", company_pic.value);
        formData.append("_method", "PATCH");

    // API STORE DATA
    await api.post(`/api/companies/${props.companyIdToEdit}`, formData)

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
          <h1 class="m-4 font-bold text-xl flex justify-center">Tambahkan Instansi</h1>
          <input class="m-4 w-56 flex items-center p-2 border rounded-xl line-color" v-model="company_name" placeholder="Nama Perusahaan" />
          <textarea class="m-4 w-56 flex items-center p-2 border rounded-xl line-color" v-model="company_address" placeholder="Alamat" />
          <input class="m-4 w-56 flex items-center p-2 border rounded-xl line-color" v-model="company_telephone" placeholder= "Telepon" />
          <input class="m-4 w-56 flex items-center p-2 border rounded-xl line-color" v-model="company_pic" placeholder= "PIC" />

          <div class="flex justify-between">
            <button class="m-4 w-20 flex items-center justify-center py-1 px-2 border rounded-xl border-black font-bold" @click="$emit('close')">Batal</button>
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
  