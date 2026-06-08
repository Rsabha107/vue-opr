<template>
  <div class="radio-pills">
    <label
      v-for="option in options"
      :key="option.id"
      class="radio-pill"
      :class="[
        { 'is-on': modelValue === option.id },
        `tone-${option.tone}`
      ]"
    >
      <input
        type="radio"
        :name="name"
        :value="option.id"
        :checked="modelValue === option.id"
        @change="$emit('update:modelValue', option.id)"
        class="radio-pill__input"
      />
      <span class="radio-dot"></span>
      <span class="radio-pill__label">{{ option.label }}</span>
    </label>
  </div>
</template>

<script setup>
defineProps({
  options: {
    type: Array,
    required: true
  },
  modelValue: {
    type: String,
    default: null
  },
  name: {
    type: String,
    required: true
  }
});

defineEmits(['update:modelValue']);
</script>

<style scoped>
.radio-pills {
  display: flex;
  flex-wrap: wrap;
  gap: 9px;
}

.radio-pill {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  padding: 10px 16px;
  border-radius: 10px;
  border: 1.5px solid var(--surface-line);
  background: var(--field-bg);
  cursor: pointer;
  font-family: var(--font-body);
  font-weight: 600;
  font-size: 14px;
  color: var(--ink-soft);
  transition: 0.14s;
  user-select: none;
}

.radio-pill__input {
  display: none;
}

.radio-dot {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  border: 1.6px solid var(--ink-faint);
  flex: none;
  transition: 0.14s;
}

.radio-pill:hover:not(.is-on) {
  border-color: var(--ink-faint);
  background: var(--surface-2);
}

.radio-pill.is-on {
  background: var(--accent);
  border-color: var(--accent);
  color: var(--accent-ink);
}

.radio-pill.is-on:hover {
  background: #5f1722;
  border-color: #5f1722;
}

.radio-pill.is-on .radio-dot {
  border-color: var(--accent-ink);
  background: var(--accent-ink);
  border-width: 5px;
}

/* All tone variants use the same burgundy theme */
.radio-pill.tone-danger.is-on,
.radio-pill.tone-success.is-on,
.radio-pill.tone-info.is-on,
.radio-pill.tone-secondary.is-on,
.radio-pill.tone-warning.is-on {
  background: var(--accent);
  border-color: var(--accent);
  color: var(--accent-ink);
}
</style>
