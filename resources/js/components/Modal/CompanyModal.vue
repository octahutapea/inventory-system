<script setup>

import { ref, defineEmits } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

const company = ref({
  company_name: '',
  company_address: '',
  company_telephone: '',
  company_pic: ''
});

const submit = async () => {
  await api.post('/api/companies', company.value)

    .then(response => {
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
          <h1 class="m-4 font-bold text-xl flex justify-center">Tambahkan Instansi</h1>
          <input class="m-4 w-56 flex items-center p-2 border rounded-xl line-color" v-model="company.company_name" placeholder="Nama Perusahaan" />
          <textarea class="m-4 w-56 flex items-center p-2 border rounded-xl line-color" v-model="company.company_address" placeholder="Alamat" />
          <input class="m-4 w-56 flex items-center p-2 border rounded-xl line-color" v-model="company.company_telephone" placeholder= "Telepon" />
          <input class="m-4 w-56 flex items-center p-2 border rounded-xl line-color" v-model="company.company_pic" placeholder= "PIC" />

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
  