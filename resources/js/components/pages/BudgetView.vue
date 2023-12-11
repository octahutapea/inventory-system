<script setup>
import { ref, onMounted, watch } from 'vue';
import api from '../../api';

const currentYear = ref();
const currentBudget = ref();
const nextYear = ref();
const nextYearBudget = ref();
const fetchDataItems = async () => {
    await api.get('/api/budget')

    .then(response => {

        //set response data to state "posts"
        currentYear.value = response.data.currentYear
        currentBudget.value = response.data.currentBudget
        nextYear.value = response.data.nextYear
        nextYearBudget.value = response.data.nextYearBudget

    });
}

const now = new Date().getFullYear();
const year = ref(now);

const dataMonthlyBudget = ref([]);
const dataCounts = async () => {
  try {
    const response = await axios.get(`/api/monthly_budget/${year.value}`);
    dataMonthlyBudget.value = response.data.budget;

    console.log('Data Anggaran Bulanan:', dataMonthlyBudget.value);
  } catch (error) {
    console.error('Gagal mengambil data anggaran bulanan:', error);
  }
}

const year_trend = ref(now);

const dataTrendBudget = ref([]);
const dataTrend = async () => {
  try {
    const response = await axios.get(`/api/trend_budget/${year_trend.value}`);
    dataTrendBudget.value = response.data.trend;

    console.log('Data Anggaran Bulanan:', dataTrendBudget.value);
  } catch (error) {
    console.error('Gagal mengambil data anggaran bulanan:', error);
  }
}

onMounted(() => {
    fetchDataItems();
    dataCounts();
    dataTrend();
});

watch(year, () => {
  // Ketika nilai year berubah, ambil ulang data anggaran bulanan
  dataCounts();
});

watch(year_trend, () => {
  // Ketika nilai year berubah, ambil ulang data anggaran bulanan
  dataTrend();
});
</script>

<template>
  <navbar />
  <sidebar />

  <main class="ml-48 mt-32">

    <div class="mx-8">
      <div class="h-auto bg-white py-16 rounded-3xl shadow-xl grid place-content-center place-items-center gap-8">
        <h2 class="text-xl font-semibold">Beban Biaya Tahun {{ currentYear }}</h2>
        <p class="text-4xl font-bold text-primary">{{ filters.formatMoney(currentBudget) }}</p>
      </div>

      <!-- <div class="h-auto bg-white py-16 rounded-3xl shadow-xl grid place-content-center place-items-center gap-8">
        <h2 class="text-xl font-semibold">Perkiraan Anggaran Tahun {{ nextYear }}</h2>
        <p class="text-4xl font-bold text-primary">{{ filters.formatMoney(nextYearBudget) }}</p>
      </div> -->
    </div>

    <!-- <div class="bg-white m-8 p-8 rounded-3xl shadow-xl">
      <div class="grid place-content-center place-items-center mb-8">
        <h3 class="text-xl font-bold">Anggaran Per Bulan</h3>
      </div>

      <select class="w-56 flex items-center p-2 border rounded-xl line-color" v-model="year" placeholder="Tahun">
        <option v-for="i in now - 2020 + 1" :key="i" :value="now - i + 1">{{ now - i + 1 }}</option>
      </select>

      <div class="grid place-content-center place-items-center mt-12">
        <line-chart :data="dataMonthlyBudget" xtitle="Bulan" ytitle="Anggaran" width="800px" height="500px"></line-chart>
      </div>

    </div>

    <div class="bg-white m-8 p-8 rounded-3xl shadow-xl">
      <div class="grid place-content-center place-items-center mb-8">
        <h3 class="text-xl font-bold">Anggaran 5 Tahun Terakhir</h3>
      </div>

      <select class="w-56 flex items-center p-2 border rounded-xl line-color" v-model="year_trend" placeholder="Tahun">
        <option v-for="i in now - 2020 + 1" :key="i" :value="now - i + 1">{{ now - i + 1 }}</option>
      </select>

      <div class="grid place-content-center place-items-center mt-12">
        <line-chart :data="dataTrendBudget" xtitle="Tahun" ytitle="Anggaran" width="800px" height="500px"></line-chart>
      </div>

    </div> -->
  </main>
</template>
