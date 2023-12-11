<script setup>

import { ref, defineEmits, defineProps } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

const props = defineProps(['contract-id']);

const contract = ref({
  contract_id: props.contractId,
  item_merk: '',
  item_type: '',
  item_total: '',
  item_value: '',
});

const submit = async () => {
  await api.post('/api/acquisition_values', contract.value)

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
          <h1 class="m-4 font-bold text-xl flex justify-center">Tambahkan Nilai Perolehan</h1>
          <div class="grid mx-4">
            <input class="my-1 w-full flex items-center p-2 border rounded-xl line-color" v-model="contract.item_merk" placeholder="Merk" />
            <input class="my-1 w-full flex items-center p-2 border rounded-xl line-color" v-model="contract.item_type" placeholder="Tipe" />
            <input class="my-1 w-full flex items-center p-2 border rounded-xl line-color" v-model="contract.item_total" placeholder= "Total" />
            <input class="my-1 w-full flex items-center p-2 border rounded-xl line-color" v-model="contract.item_value" placeholder="Nilai Perolehan" />
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
  