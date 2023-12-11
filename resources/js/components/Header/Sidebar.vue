<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { ref } from 'vue'

const role = localStorage.getItem('role');

const isClaimDropdownOpen = ref(false);
const isContractDropdownOpen = ref(false);

const toggleClaimDropdown = () => {
  isClaimDropdownOpen.value = !isClaimDropdownOpen.value;
}

const toggleContractDropdown = () => {
  isContractDropdownOpen.value = !isContractDropdownOpen.value;
}

</script>

<template>

  <aside class="top-0 flex fixed mt-24 left-0 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0">
    <ul>
      <li class="px-8 py-3">
        <router-link active-class="text-primary font-bold" to="/">Beranda</router-link>
      </li>

      <li class="px-8 py-3">          
          <router-link active-class="text-primary font-bold" to="/inventory">Inventaris</router-link>    
      </li>

      <li class="px-8 py-3">
        <button @click="toggleClaimDropdown" class="flex items-center">
          <img class="w-4 h-4 mr-2" src="../icons/Dropdown.svg" alt="">
          <span active-class="text-primary font-bold">Klaim</span>
        </button>
        <ul v-if="isClaimDropdownOpen">
          <li class="pt-3">
            <router-link class="pl-6" active-class="text-primary font-bold" to="/claim">Semua Klaim</router-link>
          </li>
          <li class="pt-3" v-if="role === 'Admin'">
            <router-link class="pl-6" active-class="text-primary font-bold" to="/request">Permintaan</router-link>
          </li>
          <li class="pt-3" v-if="role === 'Manager'">
            <router-link class="pl-6" active-class="text-primary font-bold" to="/managerrequest">Permintaan</router-link>
          </li>
          <li class="pt-3" v-if="role === 'Admin'">
            <router-link class="pl-6" active-class="text-primary font-bold" to="/claim-process">Proses HC</router-link>
          </li>
        </ul>
      </li>

      <li class="px-8 py-3">
        <router-link active-class="text-primary font-bold" to="/acquisitionvalue">Nilai Perolehan</router-link>
      </li>

      <li class="px-8 py-3">
        <router-link active-class="text-primary font-bold" to="/distribution">Sebaran</router-link>
      </li>

      <li class="px-8 py-3">
        <button @click="toggleContractDropdown" class="flex items-center">
          <img class="w-4 h-4 mr-2" src="../icons/Dropdown.svg" alt="">
          <span active-class="text-primary font-bold">Kontrak</span>
        </button>
        <ul v-if="isContractDropdownOpen">
          <li class="pt-3">
            <router-link class="pl-6" active-class="text-primary font-bold" to="/contract">Semua Kontrak</router-link>
          </li>
          <li class="pt-3">
            <router-link class="pl-6" active-class="text-primary font-bold" to="/company">Daftar Perusahaan</router-link>
          </li>
        </ul>
      </li>

      <li class="px-8 py-3">
        <router-link active-class="text-primary font-bold" to="/budget">Anggaran</router-link>
      </li>
    </ul>
  </aside>

  <RouterView />
</template>