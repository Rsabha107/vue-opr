<template>
  <label class="pill-check" :class="{ 'is-checked': checked, 'is-disabled': disabled }">
    <input
      type="checkbox"
      :checked="checked"
      :disabled="disabled"
      @change="$emit('update:checked', $event.target.checked)"
      class="pill-check__input"
    />
    <span class="pill-check__label">
      {{ label }}
    </span>
  </label>
</template>

<script setup>
defineProps({
  label: {
    type: String,
    required: true
  },
  checked: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  }
});

defineEmits(['update:checked']);
</script>

<style scoped>
.pill-check {
  display: inline-flex;
  align-items: center;
  padding: 9px 15px;
  border-radius: 999px;
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

.pill-check:hover:not(.is-disabled):not(.is-checked) {
  border-color: var(--ink-faint);
  background: var(--surface-2);
}

.pill-check.is-checked {
  background: var(--accent);
  border-color: var(--accent);
  color: var(--accent-ink);
}

.pill-check.is-checked:hover:not(.is-disabled) {
  background: #5f1722;
  border-color: #5f1722;
}

.pill-check.is-disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pill-check__input {
  position: absolute;
  opacity: 0;
  pointer-events: none;
}
</style>
