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
                ></textarea>
                <small class="text-danger d-block mt-2" v-if="errors.address">
                  <i class="fas fa-exclamation-circle"></i> {{ errors.address[0] }}
                </small>
              </div>

              <!-- Photo Upload -->
              <div class="form-group mb-4">
                <label class="form-label fw-bold">Profile Photo</label>
                
                <!-- Drag & Drop Zone -->
                <div
                  class="border-3 border-dashed rounded p-5 text-center"
                  :class="[
                    isDragging ? 'bg-primary bg-opacity-10 border-primary' : 'bg-light border-secondary',
                    errors.photo ? 'border-danger' : ''
                  ]"
                  @dragover.prevent="isDragging = true"
                  @dragleave.prevent="isDragging = false"
                  @drop.prevent="onDropFile"
                  style="cursor: pointer; transition: all 0.3s ease;"
                >
                  <input
                    type="file"
                    class="d-none"
                    id="photoInput"
                    accept="image/*"
                    @change="onImageChange"
                    ref="fileInput"
                  />
                  
                  <div @click="$refs.fileInput.click()" style="cursor: pointer;">
                    <i class="fas fa-cloud-upload-alt fa-3x mb-3" :class="isDragging ? 'text-primary' : 'text-secondary'"></i>
                    <p class="mb-2 fw-bold text-dark">
                      {{ isDragging ? 'Drop your photo here' : 'Drag & drop your photo here' }}
                    </p>
                    <p class="text-muted mb-3">or click to select a file</p>
                    <button
                      type="button"
                      class="btn btn-primary"
                      @click.prevent="$refs.fileInput.click()"
                    >
                      <i class="fas fa-folder-open"></i> Choose Photo
                    </button>
                  </div>
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
const isDragging = ref(false)
const fileInput = ref(null)

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
  // Clear photo error when file is selected
  errors.value.photo = null
}

const onDropFile = (event) => {
  isDragging.value = false
  const file = event.dataTransfer.files[0]
  
  if (!file) return
  
  // Validate file is an image
  if (!file.type.startsWith('image/')) {
    errors.value.photo = ['Please upload a valid image file']
    return
  }
  
  person.photo = file
  imagePreview.value = URL.createObjectURL(file)
  // Clear photo error when file is successfully dropped
  errors.value.photo = null
}

const removeImage = () => {
  person.photo = null
  imagePreview.value = null
  document.getElementById('photoInput').value = ''
}

const validateForm = () => {
  errors.value = {}
  
  if (!person.name || person.name.trim() === '') {
    errors.value.name = ['Full name is required']
  }
  
  if (!person.address || person.address.trim() === '') {
    errors.value.address = ['Address is required']
  }
  
  if (!person.photo) {
    errors.value.photo = ['Profile photo is required']
  }
  
  return Object.keys(errors.value).length === 0
}

const onFinish = async () => {
  // Validate form before sending request
  
  loading.value = true
  uploadProgress.value = 0

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
    console.log(error.response?.data?.errors)
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    }
  } finally {
    loading.value = false
    uploadProgress.value = 0
  }
}
</script>
