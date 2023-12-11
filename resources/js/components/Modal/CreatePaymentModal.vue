<script setup>

import { ref, onMounted, defineEmits, defineProps } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

const props = defineProps(['contract-id']);

const contract_id = props.contractId;
const period = ref("");
const payment_note = ref("");
const period_start = ref("");
const period_end = ref("");
const details = ref("");
const total = ref("");
const invoice = ref("null");
const payment_status = ref("");

const changeFile = (e) => {
  const file = e.target.files[0];
  // Implement logic to handle the selected file as needed
  invoice.value = file; // Assuming you want to store the file object in claim.claim_document
};

console.log("contract : ", contract_id)

const submit = async () => {
    let formData = new FormData();

        //assign state value to formData
        formData.append("contract_id", contract_id);
        formData.append("period", period.value);
        formData.append("payment_note", payment_note.value);
        formData.append("period_start", period_start.value);
        formData.append("period_end", period_end.value);
        formData.append("details", details.value);
        formData.append("total", total.value);
        formData.append("invoice", invoice.value);
        formData.append("payment_status", payment_status.value);
        formData.append("_method", "POST");

    // API STORE DATA
    await api.post(`/api/payment`, formData)

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
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="period" placeholder="Tagihan Periode" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="payment_note" placeholder="Keterangan" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="period_start" type="date" placeholder= "Periode Mulai" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="period_end" type="date" placeholder= "Periode Selesai" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="details" placeholder="Rincian" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="total" placeholder= "Total" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" @change="changeFile($event)" type="file" placeholder= "Dokumen Tagihan" />
            <select class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="payment_status">
              <option disabled value="">Status Pembayaran</option>
              <option>Lunas</option>
              <option>Belum</option>
            </select>

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
  