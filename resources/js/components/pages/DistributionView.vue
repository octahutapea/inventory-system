<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../api';

const router = useRouter();
const distributions = ref([]);
const itemCounts = ref([]);

const fetchDataItems = async () => {
    await api.get('/api/distributions')

    .then(response => {

      distributions.value = response.data.data

    });
}

const calculateItemCounts = async () => {
    await api.get('/api/item_counts')

    .then(response => {

      itemCounts.value = response.data.data

    });
}

onMounted(() => {
    fetchDataItems();
    calculateItemCounts();
});

const areas = ['HO', 'NSA', 'DDA', 'ORA', 'CSA', 'SSA', 'WJA', 'EJA', 'KAL'];

const getAreaCount = (distribution, area) => {
    const areaCount = distribution.areas.find((item) => item.area === area);
    return areaCount ? areaCount.count : 0;
}

// SHOW DISTRIBUTION
const areaToShow = ref(null);

const showDistribution = (area) => {
  areaToShow.value = area;
  console.log(areaToShow);
  router.push({ name: 'area', params: { areaCode: area } });
}

</script>

<template>
  <navbar />
  <sidebar />

  <main class="ml-48 mr-6">
    <div class="mt-24">
      <div class="grid grid-cols-6 w-full">
        <button @click.prevent="showDistribution(itemCount.area)" 
        class="bg-white border-l-4 border-primary m-2 p-4 rounded-xl shadow-sm grid place-content-center place-items-center" 
        v-for="itemCount in itemCounts" :key="itemCount.area">
          <span class="text-3xl font-bold">
            {{ itemCount.count }}
          </span>
          <p class="font-semibold">Total di {{ itemCount.area }}</p>
        </button>
      </div>  
    </div>

    <div class="bg-white m-2 pt-1 rounded-3xl shadow-sm">
      <search />
      <table class="table-auto w-full text-left">
        <thead class="bg-primary/20 rounded-3xl">
          <tr>
            <th class="p-3">No</th>
            <th class="p-3">Perangkat</th>
            <th class="p-3" v-for="area in areas" :key="area">{{ area }}</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(distribution, index) in distributions" :key="distribution.id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ distribution.item_name }}</td>
            <td v-for="area in areas" :key="area">
              <span class="p-3">
                {{ getAreaCount(distribution, area) }}
              </span>
            </td>
          </tr>
          
        </tbody>
      </table>
    </div>
    
  </main>
</template>
  
  <style>
  @media (min-width: 1024px) {
    .about {
      min-height: 100vh;
      display: flex;
      align-items: center;
    }
  }
  </style>
  