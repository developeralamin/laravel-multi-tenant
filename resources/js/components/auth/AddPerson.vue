<template>
  <div class="container mt-5 mb-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg border-0">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Person Profile</h4>
          </div>
          
          <form @submit.prevent="onFinish" class="needs-validation">
            <div class="card-body">
              <!-- Name -->
              <div class="form-group mb-4">
                <label class="form-label fw-bold">Full Name</label>
                <input
                  type="text"
                  class="form-control form-control-lg"
                  v-model="person.name"
                  placeholder="Enter full name"
                  required
                />
                <small class="text-danger d-block mt-2" v-if="errors.name">
                  <i class="fas fa-exclamation-circle"></i> {{ errors.name[0] }}
                </small>
              </div>

              <!-- Address -->
              <div class="form-group mb-4">
                <label class="form-label fw-bold">Address</label>
                <textarea
                  class="form-control form-control-lg"
                  v-model="person.address"
                  placeholder="Enter address"
                  rows="3"
                  required
                ></textarea>
                <small class="text-danger d-block mt-2" v-if="errors.address">
                  <i class="fas fa-exclamation-circle"></i> {{ errors.address[0] }}
                </small>
              </div>

              <!-- Photo Upload -->
              <div class="form-group mb-4">
                <label class="form-label fw-bold">Profile Photo</label>
                <div class="input-group input-group-lg">
                  <input
                    type="file"
                    class="form-control"
                    id="photoInput"
                    accept="image/*"
                    @change="onImageChange"
                  />
                  <label class="input-group-text bg-primary text-white" for="photoInput">
                    <i class="fas fa-camera"></i> Upload
                  </label>
                </div>
                <small class="text-danger d-block mt-2" v-if="errors.photo">
                  <i class="fas fa-exclamation-circle"></i> {{ errors.photo[0] }}
                </small>
              </div>

              <!-- Image Preview -->
              <div v-if="imagePreview" class="mb-4">
                <div class="position-relative">
                  <img
                    :src="imagePreview"
                    class="img-fluid rounded border border-primary"
                    style="max-height: 250px; width: 100%; object-fit: cover;"
                  />
                  <button
                    type="button"
                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                    @click="removeImage"
                  >
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>

              <!-- Upload Progress Bar -->
              <div v-if="uploadProgress > 0" class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="d-flex align-items-center gap-2">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="fw-bold text-primary">Uploading File...</span>
                  </div>
                  <span class="badge bg-primary fs-6">{{ uploadProgress }}%</span>
                </div>
                <div class="progress" style="height: 35px; background-color: #f0f0f0;">
                  <div
                    class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                    role="progressbar"
                    :style="{ width: (uploadProgress || 5) + '%', minWidth: '5%', transition: 'width 0.3s ease' }"
                    :aria-valuenow="uploadProgress"
                    aria-valuemin="0"
                    aria-valuemax="100"
                  >
                    <span class="text-white fw-bold">{{ uploadProgress }}%</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer bg-light d-flex gap-2 justify-content-end">
              <button type="reset" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-redo"></i> Reset
              </button>
              <button type="submit" class="btn btn-primary btn-lg" :disabled="loading">
                <span v-if="loading">
                  <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                  Uploading...
                </span>
                <span v-else>
                  <i class="fas fa-paper-plane"></i> Submit
                </span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const loading = ref(false)
const uploadProgress = ref(0)
const errors = ref({})
const imagePreview = ref(null)

const person = reactive({
  name: '',
  address: '',
  photo: null
})

const onImageChange = (event) => {
  const file = event.target.files[0]
  if (!file) return

  person.photo = file
  imagePreview.value = URL.createObjectURL(file)
}

const removeImage = () => {
  person.photo = null
  imagePreview.value = null
}

const onFinish = async () => {
  loading.value = true
  uploadProgress.value = 0
  errors.value = {}

  const formData = new FormData()
  formData.append('name', person.name)
  formData.append('address', person.address)
  formData.append('photo', person.photo)

  try {
    await axios.post('/api/create', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      onUploadProgress: (progressEvent) => {
        uploadProgress.value = Math.round((progressEvent.loaded / progressEvent.total) * 100)
      }
    })
    router.push({ path: 'person' })
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    }
  } finally {
    loading.value = false
    uploadProgress.value = 0
  }
}
</script>
