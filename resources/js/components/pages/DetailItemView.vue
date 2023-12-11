<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../api';
import CreateItemModal from '../Modal/CreateItemModal.vue'
import DeleteModal from '../Modal/DeleteModal.vue'
import EditItemModal from '../Modal/EditItemModal.vue'
import ImportItemModal from '../Modal/ImportItemModal.vue'

// API GET ITEM BY ACQUISITION VALUE ID
const items = ref([]);
const item_merk = ref('');
const item_type = ref('');
const route = useRoute();
const router = useRouter();

const fetchDataItems = async () => {
    await api.get(`/api/detail_items/${route.params.acquisitionValueId}`)

    .then(response => {
        console.log(route.params.acquisitionValueId)
        items.value = response.data.data
        // item_merk.value = response.data.data[0].item_merk
        // item_type.value = response.data.data[0].item_type

        if (response.data.data[0]) {
          if (response.data.data[0].item_merk) {
            item_merk.value = response.data.data[0].item_merk;
          } else {
            item_merk.value = " ";
          }
          if (response.data.data[0].item_type) {
            item_type.value = response.data.data[0].item_type;
          } else {
            item_type.value = " ";
          }
        }
        
        console.log(response)

    });
}

onMounted(() => {
    fetchDataItems();
});

// ITEM MODAL FOR CREATE
const isCreateItemModalVisible = ref(false);
const acquisitionValueId = route.params.acquisitionValueId;

const showCreateItemModal = (acquisitionValueId) => {
    isCreateItemModalVisible.value = true;
}
const closeCreateItemModal = () => {
    isCreateItemModalVisible.value = false;
}

// ITEM MODAL FOR EDIT
const isEditItemModalVisible = ref(false);
const itemIdToEdit = ref(null);

const showEditItemModal = (id) => {
    isEditItemModalVisible.value = true;
    itemIdToEdit.value = id;
}
const closeEditItemModal = () => {
    isEditItemModalVisible.value = false;
}

// ITEM MODAL FOR DELETE
const isDeleteModalVisible = ref(false);
const idToDelete = ref(null);

const showDeleteModal = (id) => {
    idToDelete.value = id;
    isDeleteModalVisible.value = true;
}
const closeDeleteModal = () => {
    isDeleteModalVisible.value = false;
}

// API DELETE ITEM
const confirmDelete = async () => {
  await api.delete(`/api/items/${idToDelete.value}`)

    .then(response => {
      location.reload();
      console.log(response)
    });
};

// ITEM MODAL FOR IMPORT
const isImportModalVisible = ref(false);

const showImportModal = (acquisitionValueId) => {
  isImportModalVisible.value = true;
}
const closeImportModal = () => {
    isImportModalVisible.value = false;
}

const downloadDocument = async (filename) => {
  try {
    const response = await api.get(`/api/open-image/${filename}`, {
      responseType: 'blob', // Set responseType ke 'blob' untuk menangani data biner (binary data)
    });

    const contentType = response.headers['content-type'];
    const extension = filename.split('.').pop();
    const imageType = contentType.startsWith('image') ? contentType : `image/${extension}`;

    // Buat URL objek dari blob data
    const blob = new Blob([response.data], { type: imageType });
    const url = window.URL.createObjectURL(blob);

    // Buat elemen <a> untuk menautkan ke URL dan otomatis unduh file
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;

    // Tambahkan elemen <a> ke body dokumen dan klik secara otomatis
    document.body.appendChild(a);
    a.click();

    // Hapus elemen <a> setelah selesai
    document.body.removeChild(a);
  } catch (error) {
    console.error('Error downloading file:', error);
  }
};

</script>

<template>
  <navbar />
  <sidebar />

  <main class="ml-48 mt-32">
    <div class="bg-white m-8 pt-1 rounded-3xl shadow-sm">
        <div class="flex justify-between">
          <h3 class="w-fit flex items-center m-4 px-2 text-lg font-semibold">Daftar Item {{ item_merk || ' ' }} {{ item_type || ' ' }}</h3>

          <div class="flex">
            <import-button @click="showImportModal" />
            <ImportItemModal v-if="isImportModalVisible" :acquisition-value-id="acquisitionValueId" @close="closeImportModal" />

            <add-button @click="showCreateItemModal" />
            <CreateItemModal v-if="isCreateItemModalVisible" :acquisition-value-id="acquisitionValueId" @close="closeCreateItemModal" />
          </div>
          
        </div>
      
      <table class="table-auto w-full text-left">
        <thead class="bg-primary/20 rounded-3xl">
          <tr>
            <th class="p-3">No</th>
            <th class="p-3">Area</th>
            <th class="p-3">Instansi</th>
            <th class="p-3">Perangkat</th>
            <th class="p-3">Tipe</th>
            <th class="p-3">Nomor Serial</th>
            <th class="p-3">Nama Pengguna</th>
            <th class="p-3">Jabatan</th>
            <th class="p-3">Lokasi</th>
            <th class="p-3">Gambar</th>
            <th class="p-3">Status</th>
            <th class="p-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-dark-color/10" v-for="(item, index) in items" :key="item.id">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ item.item_area }}</td>
            <td class="p-3">{{ item.company_name }}</td>
            <td class="p-3">{{ item.item_name }}</td>
            <td class="p-3">{{ item.item_type }}</td>
            <td class="p-3">{{ item.serial_number }}</td>
            <td class="p-3">{{ item.name }}</td>
            <td class="p-3">{{ item.positiontext }}</td>
            <td class="p-3">{{ item.item_location }}</td>
            <!-- <td class="p-3">{{ item.item_pict }}</td> -->

            <td class="p-3">
              <button v-if="item.item_pict" @click="downloadDocument(item.item_pict)">
                <img class="w-6 h-6" src="../icons/Image.svg" alt="Dokumen">
              </button>
            </td>

            <td class="p-3">{{ item.item_status }}</td>
            <td>
              <button @click.prevent="showEditItemModal(item.id)">
                <img class="w-6 h-6" src="../icons/Edit.svg" alt="">
              </button>
              <button @click.prevent="showDeleteModal(item.id)">
                <img class="w-6 h-6" src="../icons/Delete.svg" alt="">
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <EditItemModal v-if="isEditItemModalVisible" 
      :item-id-to-edit="itemIdToEdit" 
      @close="closeEditItemModal" />
      <DeleteModal v-if="isDeleteModalVisible" 
      @delete="confirmDelete" 
      @close="closeDeleteModal" />
    </div>
    
  </main>
</template>
