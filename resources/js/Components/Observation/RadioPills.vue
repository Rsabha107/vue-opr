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

.radio-pill:hover {
  border-color: var(--ink-faint);
  background: var(--surface-2);
}

.radio-pill.is-on {
  color: var(--ink);
  border-color: currentColor;
}

.radio-pill.is-on .radio-dot {
  border-width: 5px;
}

.radio-pill.tone-danger.is-on   { color: #119a5b; }
.radio-pill.tone-success.is-on  { color: #2563d8; }
.radio-pill.tone-info.is-on     { color: #7c5cff; }
.radio-pill.tone-secondary.is-on{ color: #64748b; }
.radio-pill.tone-warning.is-on  { color: #d98314; }
</style>
