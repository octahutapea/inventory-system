<script setup>

import { ref, onMounted, defineEmits, defineProps } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

const props = defineProps(['acquisition-value-id-to-edit']);

const contract_id = ref("");
const item_merk = ref("");
const item_type = ref("");
const item_total = ref("");
const item_value = ref("");

// Fetch detail data
onMounted( async () => {

//fetch detail data post by ID
await api.get(`/api/acquisition_values/${props.acquisitionValueIdToEdit}`)
.then(response => {
    console.log(props.acquisitionValueIdToEdit)
    console.log(response)
    // set response data to state
    contract_id.value = response.data.data.contract_id
    item_merk.value = response.data.data.item_merk
    item_type.value = response.data.data.item_type
    item_total.value = response.data.data.item_total
    item_value.value = response.data.data.item_value

    console.log(response)
    console.log(contract_id)
});
})

const submit = async () => {
    let formData = new FormData();

        //assign state value to formData
        formData.append("contract_id", contract_id.value);
        formData.append("item_merk", item_merk.value);
        formData.append("item_type", item_type.value);
        formData.append("item_total", item_total.value);
        formData.append("item_value", item_value.value);
        formData.append("_method", "PATCH");

    // API STORE DATA
    await api.post(`/api/acquisition_values/${props.acquisitionValueIdToEdit}`, formData)

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
          <h1 class="m-4 font-bold text-xl flex justify-center">Tambahkan Nilai Perolehan</h1>
          <div class="grid mx-4">
            <input class="my-1 w-56 flex items-center p-2 border rounded-xl line-color" v-model="item_merk" placeholder="Merk" />
            <input class="my-1 w-56 flex items-center p-2 border rounded-xl line-color" v-model="item_type" placeholder="Tipe" />
            <input class="my-1 w-56 flex items-center p-2 border rounded-xl line-color" v-model="item_total" placeholder= "Total" />
            <input class="my-1 w-56 flex items-center p-2 border rounded-xl line-color" v-model="item_value" placeholder="Nilai Perolehan" />
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
  