<script setup>

import { defineEmits } from 'vue';

const { emit } = defineEmits();

</script>

<template>
  <div class="modal">
    <div class="modal-content">
      <slot>
        <p class="m-4 font-bold">Apakah anda yakin ingin menghapus?</p>
        <div class="flex justify-center">
            <button class="m-4 w-20 flex items-center justify-center py-1 px-2 border rounded-xl bg-red-color text-white font-bold" @click="$emit('delete')">Hapus</button>
            <button class="m-4 w-20 flex items-center justify-center py-1 px-2 border rounded-xl font-bold" @click="$emit('close')">Batal</button>
        </div>
        
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
  