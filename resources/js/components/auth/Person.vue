<template>
  <div class="card">
    <div class="card-body">
      <div>
       
      <router-link  type="button" class="btn btn-primary" :to="{ name: 'add-person' }">Add Person</router-link>
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>SL</th>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Photo</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(user, index) in persons" :key="index">
            <th>{{ index + 1 }}</th>
            <th>{{ user.id }}</th>
            <td>{{ user.name }}</td>
            <td>{{ user.address }}</td>
            <td>
              <img :src="user.photo" alt="Photo" class="img-fluid rounded" style="max-height: 100px"/>
         
            </td>
            <td>
              <router-link  type="button" class="btn btn-primary" :to="{ name: 'edit-person',params: { id: user.id } }">Edit</router-link>
              <button @click="onDelete(user.id)" class="btn btn-sm btn-danger">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>


<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

const persons = ref();

onMounted(() => {
  axios.get('/api/persons')
    .then((response) => {
      persons.value = response.data.data;
    })
    .catch((error) => {
      console.error('Error fetching persons:', error);
    });
});

/**
 * 
 * @param id 
 */
const onDelete = (id) => {
  axios.delete(`/api/persons/${id}`)
    .then(() => {
      persons.value = persons.value.filter(person => person.id !== id);
    })
    .catch((error) => {
      console.error('Error deleting person:', error);
    });
}


// edit 
const onEdit = (id) => {
  axios.get(`/api/persons/${id}`)
    .then((response) => {
      console.log(response.data);
    })
    .catch((error) => {
      console.error('Error fetching person:', error);
    });
}


</script> 

<style scoped>
.table {
  margin: 0 auto;
}
</style>
