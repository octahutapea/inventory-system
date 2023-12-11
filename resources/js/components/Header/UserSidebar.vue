<script setup>
import { RouterLink, RouterView } from 'vue-router'

</script>

<template>

  <aside class="top-0 flex fixed mt-24 left-0 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0">
    <ul>
      <li class="px-8 py-3">
        <router-link active-class="text-primary font-bold" to="/userclaim">Klaim</router-link>
      </li>

      <li class="px-8 py-3">          
          <router-link active-class="text-primary font-bold" to="/userhistory">Riwayat Klaim</router-link>    
      </li>

      <li class="px-8 py-3">
        <router-link active-class="text-primary font-bold" to="/userinventory">Riwayat Perangkat</router-link>
      </li>

    </ul>
  </aside>

  <RouterView />
</template>