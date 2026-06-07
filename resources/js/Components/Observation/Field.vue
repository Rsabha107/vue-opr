<template>
  <div class="obs-field" :class="{ 'has-error': error && showError }">
    <div v-if="label" class="obs-field__label-row">
      <label :for="id" class="obs-field__label">
        {{ label }}
        <span v-if="required" class="obs-field__required">*</span>
      </label>
      <span v-if="rightHint" class="obs-field__right-hint">{{ rightHint }}</span>
    </div>

    <div v-if="hint" class="obs-field__hint">
      {{ hint }}
    </div>

    <div class="obs-field__control">
      <slot></slot>
    </div>

    <div v-if="error && showError" class="obs-field__error">
      {{ error }}
    </div>
  </div>
</template>

<script setup>
defineProps({
  id: {
    type: String,
    default: () => `field-${Math.random().toString(36).slice(2, 11)}`
  },
  label: {
    type: String,
    default: null
  },
  hint: {
    type: String,
    default: null
  },
  rightHint: {
    type: String,
    default: null
  },
  error: {
    type: String,
    default: null
  },
  showError: {
    type: Boolean,
    default: true
  },
  required: {
    type: Boolean,
    default: false
  }
});
</script>

<style scoped>
.obs-field {
  display: flex;
  flex-direction: column;
  gap: 9px;
}

.obs-field__label-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
}

.obs-field__label {
  font-family: var(--font-display);
  font-weight: 600;
  font-size: 14.5px;
  color: var(--ink);
  letter-spacing: 0.005em;
}

.obs-field__required {
  color: #e0481f;
  margin-left: 3px;
}

.obs-field__right-hint {
  font-size: 12.5px;
  color: var(--ink-soft);
  font-weight: 500;
  white-space: nowrap;
}

.obs-field__hint {
  font-size: 12.5px;
  color: var(--ink-soft);
  line-height: 1.4;
  margin-top: -4px;
}

.obs-field__error {
  font-size: 12.5px;
  color: #e0481f;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 6px;
}

.obs-field__error::before {
  content: '⚠';
  flex-shrink: 0;
}

.has-error :deep(input),
.has-error :deep(textarea),
.has-error :deep(select) {
  border-color: #e0481f !important;
}
</style>
