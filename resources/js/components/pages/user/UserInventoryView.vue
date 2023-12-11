<script setup>
import { ref, onMounted } from 'vue';
import api from '../../../api';

// API Get Item
const id = localStorage.getItem('id');
const items = ref([]);

const fetchDataItems = async () => {
    await api.get(`/api/user_inventory/${id}`)

    .then(response => {

        //set response data to state "posts"
        items.value = response.data.data
        console.log(items.value)

    });
}

// API User
// const users = ref([]);
// const fetchDataUsers = async () => {
//     await api.get('/api/users')

//     .then(response => {

//         //set response data to state "posts"
//         users.value = response.data.data
//         console.log(users.value)

//     });
// }

onMounted(() => {
    fetchDataItems();
});

</script>

<template>
  <navbar />
  <user-sidebar />

  <main class="ml-48 mt-32">
    <div class="bg-white m-8 pt-1 rounded-3xl shadow-sm">
      <search />
      <table class="table-auto w-full text-left">
        <thead class="bg-primary/20 rounded-3xl">
          <tr>
            <th class="p-3">No</th>
            <th class="p-3">Area</th>
            <th class="p-3">Perangkat</th>
            <th class="p-3">Tipe</th>
            <th class="p-3">Nomor Serial</th>
            <th class="p-3">Lokasi</th>
            <th class="p-3">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(item, index) in items" :key="item.id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ item.item_area }}</td>
            <td class="p-3">{{ item.item_name }}</td>
            <td class="p-3">{{ item.item_type }}</td>
            <td class="p-3">{{ item.serial_number }}</td>
            <td class="p-3">{{ item.item_location }}</td>
            <td class="p-3">{{ item.item_status }}</td>
          </tr>
          
        </tbody>
      </table>
    </div>
    
  </main>
</template>

<style>

</style>
