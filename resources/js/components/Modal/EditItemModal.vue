<script setup>

import { ref, onMounted, defineEmits, defineProps } from 'vue';
import api from '../../api';

const { emit } = defineEmits();

const props = defineProps(['item-id-to-edit']);

const user_id = ref("");
const acquisition_value_id = ref("");
const item_area = ref("");
const item_location = ref("");
const serial_number = ref("");
const item_pict = ref("null");
const item_status = ref("");

// API GET USER
const users = ref([]);
const fetchDataItems = async () => {
    await api.get('/api/users')

    .then(response => {

        users.value = response.data.data
        console.log(users.value)

    });
}

const areas = ['HO', 'NSA', 'DDA', 'ORA', 'CSA', 'SSA', 'WJA', 'EJA', 'KAL'];

// Fetch detail data
onMounted( async () => {

  fetchDataItems();

//fetch detail data post by ID
await api.get(`/api/items/${props.itemIdToEdit}`)
.then(response => {
    console.log(props.itemIdToEdit)
    console.log(response)
    // set response data to state
    user_id.value = response.data.data.user_id
    acquisition_value_id.value = response.data.data.acquisition_value_id
    item_area.value = response.data.data.item_area
    item_location.value = response.data.data.item_location
    serial_number.value = response.data.data.serial_number
    item_pict.value = response.data.data.item_pict
    item_status.value = response.data.data.item_status

    console.log(response)
});
})

const changeFile = (e) => {
  const file = e.target.files[0];
  // Implement logic to handle the selected file as needed
  item_pict.value = file; // Assuming you want to store the file object in claim.claim_document
};

const submit = async () => {
    let formData = new FormData();

        //assign state value to formData
        formData.append("user_id", user_id.value);
        formData.append("acquisition_value_id", acquisition_value_id.value);
        formData.append("item_area", item_area.value);
        formData.append("item_location", item_location.value);
        formData.append("serial_number", serial_number.value);
        formData.append("item_pict", item_pict.value);
        formData.append("item_status", item_status.value);
        formData.append("_method", "PATCH");

    // API STORE DATA
    await api.post(`/api/items/${props.itemIdToEdit}`, formData)

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
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <h1 class="m-4 font-bold text-xl flex justify-center">Tambahkan Instansi</h1>
          <div>
            <select class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="user_id">
              <option disabled value="">Nama</option>
              <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name + '-' + user.positiontext }}</option>
            </select>
            <select class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="item_area">
              <option disabled value="">Area</option>
              <option v-for="area in areas" :key="area">{{ area }}</option>
            </select>
            <!-- <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="item_area" placeholder="Area" /> -->
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="item_location" placeholder="Lokasi" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="serial_number" placeholder="Nomor Seri" />
            <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" @change="changeFile($event)" type="file" placeholder= "Gambar" />
            
            <!-- <input class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" v-model="status" placeholder= "Status" /> -->

            <select class="mx-4 my-2 w-56 flex items-center p-2 border rounded-xl line-color" name="" id="" v-model="item_status">
              <option disabled value="">Status</option>
              <option>Baik</option>
              <option>Rusak</option>
              <option>Hilang</option>
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
  