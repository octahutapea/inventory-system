<script setup>
import { ref } from 'vue';
import api from '../../api';
import LogoutModal from '../Modal/LogoutModal.vue'

// GET ROLE
const role = localStorage.getItem('role')

// LOGOUT MODAL
const isLogoutModalVisible = ref(false);
const tokenToLogout = ref(null);

const showLogoutModal = () => {
  isLogoutModalVisible.value = true;
}
const closeLogoutModal = () => {
    isLogoutModalVisible.value = false;
}

const confirmLogout = async () => {
  const token = localStorage.getItem('token')
  tokenToLogout.value = `Bearer ${token}`

  await api.get(`/api/logout`, {
    headers: {
      Authorization: tokenToLogout.value
    }
  })

    .then(response => {
      console.log(response)

      if (response.data.success) {
        localStorage.setItem("authenticated", "false")
        localStorage.setItem("token", "")
        localStorage.removeItem('token');
        location.reload();
      }
      else {
      console.error("Logout failed");
      }
    });
};
</script>

<template>
  <header>
    <div class="bg-color flex justify-between items-center w-full h-24 fixed top-0">
      <div class="mx-8">
        <img alt="Pertagas logo" class="h-10" src="../../../img/pertagas.png"  />
      </div>
      <div class="flex items-center mx-8">
        <p class="font-bold px-6">{{ role }}</p>
        <button @click.prevent="showLogoutModal" 
        class="bg-red-color font-bold text-white px-6 py-1.5 rounded-3xl shadow">Logout</button>
      </div>
      <LogoutModal v-if="isLogoutModalVisible" 
      @logout="confirmLogout" 
      @close="closeLogoutModal" />
    </div>
  </header>
</template>
