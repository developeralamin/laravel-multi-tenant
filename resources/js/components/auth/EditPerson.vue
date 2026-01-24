<template>
  <div class="container mt-5">
    <div class="modal-dialog">
      <div class="modal-content">

        <form @submit.prevent="onFinish">
          <!-- Name -->
          <div class="modal-header d-block">
            <h5 class="modal-title">Name</h5>
            <input
              type="text"
              class="form-control"
              v-model="person.name"
            />
            <small class="text-danger" v-if="errors.name">
              {{ errors.name[0] }}
            </small>
          </div>

          <div class="modal-body">

            <!-- Address -->
            <div class="form-group mb-3">
              <label>Address</label>
              <input
                type="text"
                class="form-control"
                v-model="person.address"
              />
              <small class="text-danger" v-if="errors.address">
                {{ errors.address[0] }}
              </small>
            </div>

            <!-- Photo -->
            <div class="form-group mb-3">
              <label>Photo</label>
              <input
                type="file"
                class="form-control"
                accept="image/*"
                @change="onImageChange"
              />
              <small class="text-danger" v-if="errors.photo">
                {{ errors.photo[0] }}
              </small>
            </div>

            <!-- Image Preview -->
            <div v-if="imagePreview" class="mt-3">
              <img
                :src="imagePreview"
                class="img-fluid rounded"
                style="max-height: 200px"
              />
            </div>

          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" :disabled="loading">
              {{ loading ? 'Sending...' : 'Submit' }}
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const router = useRouter()
const route = useRoute()

const loading = ref(false)
const errors = ref({})
const imagePreview = ref(null)

const person = reactive({
  name: '',
  address: '',
  photo: ""
})

/* Image Change */
const onImageChange = (event) => {
  const file = event.target.files[0]
  if (!file) return

  person.photo = file
  imagePreview.value = URL.createObjectURL(file)
}

/* Submit Form */
const onFinish = async () => {
  loading.value = true
  errors.value = {}

  const formData = new FormData()
  formData.append('name', person.name)
  formData.append('address', person.address)

  if (person.photo instanceof File) {
    formData.append('photo', person.photo)
  }

  if (route.params.id) {
    formData.append('_method', 'POST')
  }

  try {
    if (route.params.id) {
      await axios.post(`/api/update/${route.params.id}`, formData)
    } else {
      await axios.post('/api/create', formData)
    }

    router.push({ name: 'person' })
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    }
  } finally {
    loading.value = false
  }
}


//get single data
onMounted(() => {
  if (route.params.id) {
    axios.get('/api/show/' + route.params.id)
      .then((response) => {
        person.name = response.data.data.name
        person.address = response.data.data.address

        if (response.data.data.photo) {
          imagePreview.value = response.data.data.photo
        }
      })
      .catch((error) => {
        console.error('Error fetching person:', error)
      })
  }
})
</script>
