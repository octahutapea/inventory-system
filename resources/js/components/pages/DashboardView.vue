<script setup>
import { ref, onMounted } from 'vue';
import api from '../../api';
import 'leaflet/dist/leaflet.css';
import { LMap, LTileLayer } from "@vue-leaflet/vue-leaflet";

const zoom = ref(13);
const center = ref([51.505, -0.09]);

const itemCount = ref();
const softwareCount = ref();
const hardwareCount = ref();
const fetchDataItems = async () => {
    await api.get('/api/items')

    .then(response => {

        //set response data to state "posts"
        itemCount.value = response.data.total
        softwareCount.value = response.data.software
        hardwareCount.value = response.data.hardware
        console.log(itemCount.value)

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

const dataHardware = ref([]);
const dataSoftware = ref([]);
const dataCounts = async () => {
    await api.get('/api/item_counts')

    .then(response => {

      // dataHardware.value = response.data.data
      const data = response.data.data;
      dataHardware.value = data.map(itemCount => [itemCount.area, itemCount.hardwareCount]);
      dataSoftware.value = data.map(itemCount => [itemCount.area, itemCount.softwareCount]);

    });
}

onMounted(() => {
    fetchDataItems();
    dataCounts();
});
</script>

<template>
  <navbar />
  <sidebar />

  <main class="ml-48 mt-32">
    <div class="h-32 grid grid-cols-3 divide-x bg-white m-8 place-content-center rounded-3xl shadow-xl font-bold">
      <div class="grid gap-4">
        <div class="flex justify-center text-primary text-4xl">
          {{ itemCount }}
        </div>
        <div class="flex justify-center">
          Total Perangkat
        </div>
      </div>
      <div class="grid gap-4">
        <div class="flex justify-center text-primary text-4xl">
          {{ softwareCount }}
        </div>
        <div class="flex justify-center">
          Total Software
        </div>
      </div>
      <div class="grid gap-4">
        <div class="flex justify-center text-primary text-4xl">
          {{ hardwareCount }}
        </div>
        <div class="flex justify-center">
          Total Hardware
        </div>
      </div>
    </div>

    <div class="h-auto bg-white m-8 rounded-3xl shadow-xl font-bold">
      <h2 class="flex justify-center text-xl p-8">Sebaran Inventaris</h2>

      <l-map ref="map" v-model:zoom="zoom" v-model:center="center" :useGlobalLeaflet="false">
        <l-tile-layer
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        layer-type="base"
        name="OpenStreetMap"
      ></l-tile-layer>
      </l-map>
    </div>

    <div class="grid grid-cols-2">
      <div class="h-auto bg-white m-8 rounded-3xl shadow-xl font-bold">
        <h2 class="flex justify-center text-xl p-8">Total Hardware per Area</h2>
        <div class="mb-8">
          <pie-chart :data="dataHardware"></pie-chart>
        </div>
      </div>
      <div class="h-auto bg-white m-8 rounded-3xl shadow-xl font-bold">
        <h2 class="flex justify-center text-xl p-8">Total Software per Area</h2>
        <div class="mb-8">
          <pie-chart :data="dataSoftware"></pie-chart>
        </div>
      </div>
    </div>
  </main>
</template>
