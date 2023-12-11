<script setup>

import { ref, onMounted, defineEmits, defineProps } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

const props = defineProps(['contract-id-to-edit']);

const company_id = ref("");
const contract_number = ref("");
const item_name = ref("");
const contract_start_date = ref("");
const contract_end_date = ref("");
const contract_value = ref("");
const contract_type = ref("");
const item_type = ref("");
const contract_note = ref("");
const contract_status = ref("");

// Fetch detail data
onMounted( async () => {

//fetch detail data post by ID
await api.get(`/api/contracts/${props.contractIdToEdit}`)
.then(response => {
    
    //set response data to state
    company_id.value = response.data.data.company_id
    contract_number.value = response.data.data.contract_number
    item_name.value = response.data.data.item_name
    contract_start_date.value = response.data.data.contract_start_date
    contract_end_date.value = response.data.data.contract_end_date
    contract_value.value = response.data.data.contract_value
    contract_type.value = response.data.data.contract_type
    item_type.value = response.data.data.item_type
    contract_note.value = response.data.data.contract_note
    contract_status.value = response.data.data.contract_status

    console.log(response)
    console.log(company_id)
});
})

const submit = async () => {
    let formData = new FormData();

        //assign state value to formData
        formData.append("company_id", company_id.value);
        formData.append("contract_number", contract_number.value);
        formData.append("item_name", item_name.value);
        formData.append("contract_start_date", contract_start_date.value);
        formData.append("contract_end_date", contract_end_date.value);
        formData.append("contract_value", contract_value.value);
        formData.append("contract_type", contract_type.value);
        formData.append("item_type", item_type.value);
        formData.append("contract_note", contract_note.value);
        formData.append("contract_status", contract_status.value);
        formData.append("_method", "PATCH");

    // API STORE DATA
    await api.post(`/api/contracts/${props.contractIdToEdit}`, formData)

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
          <div class="grid grid-cols-2">
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract_number" placeholder="Nomor Kontrak" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="item_name" placeholder="Nama Perangkat" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract_start_date" type="date" placeholder= "Tanggal Mulai" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract_end_date" type="date" placeholder= "Tanggal Selesai" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract_value" placeholder="Nilai Kontrak" />
            <!-- <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract_category" placeholder= "Jenis Kontrak" /> -->
            <select class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="contract_type">
              <option disabled value="">Tipe Kontrak</option>
              <option>Sewa</option>
              <option>Beli</option>
              <option>Hibah</option>
            </select>
            <!-- <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract_type" placeholder="Tipe Kontrak" /> -->
            <select class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="item_type">
              <option disabled value="">Jenis Perangkat</option>
              <option>Software</option>
              <option>Hardware</option>
            </select>
            <select class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="contract_status">
              <option disabled value="">Status Kontrak</option>
              <option>Aktif</option>
              <option>Tidak Aktif</option>
            </select>

          </div>

          <div class="m-4">
            <textarea class="w-full flex items-center p-2 border rounded-xl line-color" v-model="contract_note" placeholder="Keterangan" />
          </div>

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
  