<script setup>
import { ref } from 'vue';
import api from '../../api';

const email = ref("");
const password = ref("");
const errorMessage = ref("");

const submit = async () => {
  let formData = new FormData();

  formData.append("email", email.value);
  formData.append("password", password.value);
  
  try {
    const response = await api.post('/api/login', formData);

    if (response.data.success) {
      localStorage.setItem("authenticated", "true");
      localStorage.setItem("token", response.data.token);
      localStorage.setItem("id", response.data.user.id);
      localStorage.setItem("role", response.data.user.role);
      location.reload();
    } else {
      console.error("Login gagal");
      errorMessage.value = "Email atau password salah"; // Set error message
    }
  } catch (error) {
    console.error("Email atau password salah", error);
    errorMessage.value = "Email atau password salah"; // Set error message
  }
};
</script>

<template>
  <header class="fixed">
    <div class="bg-color flex justify-between items-center w-full h-24">
      <img alt="Pertagas logo" class="h-10 mx-8" src="../../../img/pertagas.png"  />
    </div>
  </header>

  <div class="flex justify-center items-center h-screen">
    <main class="w-4/6 h-fit mx-auto my-auto bg-white border-white rounded-xl">
      <form @submit.prevent="submit">
        <div class="grid grid-cols-2 place-items-center">
          <div>
            <img alt="Login cover" class=" flex items-center rounded-l-xl" src="../../../img/login.jpg"  />
          </div>
          <div class="grid place-items-center">
            <img alt="Sisigas logo" class="h-10 my-4" src="../../../img/sisigas.png"  />
            <input class="my-2 w-64 p-2 border rounded-3xl line-color" v-model="email" type="email" placeholder="Email">
            <input class="my-2 w-64 p-2 border rounded-3xl line-color" v-model="password" type="password" placeholder="Password">
            
            <div v-if="errorMessage" class="text-red-color my-1">
              {{ errorMessage }}
            </div>

            <button class="w-64 p-2 my-4 border rounded-3xl bg-primary text-white font-bold" type="submit">Login</button>
          </div>
        </div>
      </form>

    </main>
  </div>
  
</template>