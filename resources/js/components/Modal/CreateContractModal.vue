<script setup>

import { ref, defineEmits, defineProps } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

const props = defineProps(['company-id']);

const items_name = [
  'Printer A3',
  'Printer A4',
  'Printer AIO',
  'Printer Mono',
  'Scanner A3',
  'Scanner A4',
  'Proyektor',
  'UPS',
  'Monitor LED',
  'Notebook',
  'PC Desktop',
  'Matrix Switcher HDMI',
  'Digital Audio Mixer 16 Channel',
  'Wireless Processor with Display Interactive System'
]

const contract = ref({
  company_id: props.companyId,
  contract_number: '',
  item_name: '',
  contract_start_date: '',
  contract_end_date: '',
  contract_value: '',
  contract_type: '',
  item_type: '',
  contract_note: '',
  contract_status: ''
});

const submit = async () => {
  await api.post('/api/contracts', contract.value)

    .then(response => {
      console.log(response)
      console.log(response)
      location.reload();
    });
};

</script>

<template>
  <div class="modal">
    <div class="modal-content">
      <slot>
        <form @submit.prevent="submit">
          <h1 class="m-6 font-bold text-xl flex justify-center">Tambahkan Kontrak</h1>
          <div class="grid grid-cols-2 gap-4 m-4">
            <input class="w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract.contract_number" placeholder="Nomor Kontrak" />
            <!-- <input class="my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract.item_name" placeholder="Nama Perangkat" /> -->
            <select class="w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="contract.item_name">
              <option disabled value="">Nama Perangkat</option>
              <option v-for="item_name in items_name" :key="item_name">{{ item_name }}</option>
            </select>
            <input class="w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract.contract_start_date" type="date" placeholder="Tanggal Mulai" />
            <input class="w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract.contract_end_date" type="date" placeholder="Tanggal Selesai" />
            <input class="w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract.contract_value" placeholder="Nilai Kontrak" />
            <!-- <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract.contract_category" placeholder= "Jenis Kontrak" /> -->
            <select class="w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="contract.contract_type">
              <option disabled value="">Tipe Kontrak</option>
              <option>Sewa</option>
              <option>Beli</option>
              <option>Hibah</option>
            </select>
            <!-- <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="contract.contract_type" placeholder="Tipe Kontrak" /> -->
            <select class="w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="contract.item_type">
              <option disabled value="">Jenis Perangkat</option>
              <option>Software</option>
              <option>Hardware</option>
            </select>
            <select class="w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="contract.contract_status">
              <option disabled value="">Status Kontrak</option>
              <option>Aktif</option>
              <option>Tidak Aktif</option>
            </select>
            
          </div>
          <div class="m-4">
            <textarea class="w-full flex items-center p-2 border rounded-xl line-color" v-model="contract.contract_note" placeholder="Keterangan" />
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
  