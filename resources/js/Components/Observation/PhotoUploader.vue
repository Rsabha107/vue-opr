<template>
  <div class="photo-uploader">
    <div v-if="photos.length > 0" class="photo-uploader__list">
      <div
        v-for="(photo, index) in photos"
        :key="index"
        class="photo-uploader__item"
      >
        <img :src="getPhotoUrl(photo)" :alt="`Photo ${index + 1}`" class="photo-uploader__preview" />
        <button
          type="button"
          @click="removePhoto(index)"
          class="photo-uploader__remove"
          title="Remove photo"
        >
          <Icon name="trash" :size="14" />
        </button>
      </div>
    </div>

    <button
      type="button"
      @click="triggerFileInput"
      class="photo-uploader__add"
      :disabled="disabled"
    >
      <Icon name="photo" :size="18" />
      <span>{{ photos.length > 0 ? 'Add more' : 'Upload photos' }}</span>
    </button>

    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      multiple
      @change="handleFileChange"
      style="display:none"
    />

    <div v-if="hint" class="photo-uploader__hint">{{ hint }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Icon from './Icon.vue';

const props = defineProps({
  photos: {
    type: Array,
    default: () => []
  },
  disabled: {
    type: Boolean,
    default: false
  },
  hint: {
    type: String,
    default: null
  }
});

const emit = defineEmits(['update:photos']);

const fileInput = ref(null);

function triggerFileInput() {
  fileInput.value?.click();
}

function handleFileChange(event) {
  const files = Array.from(event.target.files || []);
  if (files.length === 0) return;
  emit('update:photos', [...props.photos, ...files]);
  event.target.value = '';
}

function removePhoto(index) {
  const newPhotos = [...props.photos];
  newPhotos.splice(index, 1);
  emit('update:photos', newPhotos);
}

function getPhotoUrl(photo) {
  return typeof photo === 'string' ? photo : URL.createObjectURL(photo);
}
</script>

<style scoped>
.photo-uploader {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  align-items: center;
}

.photo-uploader__list {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.photo-uploader__item {
  position: relative;
  width: 84px;
  height: 84px;
  border-radius: 10px;
  overflow: hidden;
  border: 1px solid var(--surface-line);
  background: var(--surface-2);
}

.photo-uploader__preview {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.photo-uploader__remove {
  position: absolute;
  top: 4px;
  right: 4px;
  width: 22px;
  height: 22px;
  border-radius: 6px;
  border: none;
  background: rgba(8, 12, 24, 0.65);
  color: #fff;
  display: grid;
  place-items: center;
  cursor: pointer;
}

.photo-uploader__add {
  width: 84px;
  height: 84px;
  border-radius: 10px;
  border: 1.5px dashed var(--ink-faint);
  background: transparent;
  color: var(--ink-soft);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 5px;
  cursor: pointer;
  font-family: var(--font-body);
  font-size: 11px;
  font-weight: 600;
  transition: 0.14s;
}

.photo-uploader__add:hover:not(:disabled) {
  border-color: var(--accent);
  color: var(--accent);
}

.photo-uploader__add:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.photo-uploader__hint {
  width: 100%;
  font-size: 12px;
  color: var(--ink-soft);
  margin-top: 2px;
}
</style>
