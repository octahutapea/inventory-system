<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../../api';

const route = useRoute();
const areaCode = route.params.areaCode;

const area_distribution = ref([]);
const fetchDataItems = async () => {
    await api.get(`/api/item_per_area/${areaCode}`)

    .then(response => {

        //set response data to state "posts"
        area_distribution.value = response.data.data
        console.log(area_distribution.value)

    });
}

onMounted(() => {
    fetchDataItems();
});

</script>

<template>
  <navbar />
  <sidebar />

  <main class="ml-48 mt-32">
    <div class="bg-white m-8 pt-1 rounded-3xl shadow-sm">
      <div class="flex justify-between">
        <h3 class="m-4 text-xl font-bold">Perangkat di {{ areaCode }}</h3>
        <search />
      </div>
      
      <table class="table-auto w-full text-left">
        <thead class="bg-primary/20 rounded-3xl">
          <tr>
            <th class="p-3">No</th>
            <th class="p-3">Instansi</th>
            <th class="p-3">Perangkat</th>
            <th class="p-3">Merk</th>
            <th class="p-3">Tipe</th>
            <th class="p-3">Nomor Serial</th>
            <th class="p-3">Nama Pengguna</th>
            <th class="p-3">Jabatan</th>
            <th class="p-3">Lokasi</th>
            <th class="p-3">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(item, index) in area_distribution" :key="item.id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ item.company_name }}</td>
            <td class="p-3">{{ item.item_name }}</td>
            <td class="p-3">{{ item.item_merk }}</td>
            <td class="p-3">{{ item.item_type }}</td>
            <td class="p-3">{{ item.serial_number }}</td>
            <td class="p-3">{{ item.name }}</td>
            <td class="p-3">{{ item.positiontext }}</td>
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
