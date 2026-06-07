<template>
  <label class="chk-tile" :class="{ 'is-on': checked, 'is-disabled': disabled }">
    <input
      type="checkbox"
      :checked="checked"
      :disabled="disabled"
      @change="$emit('update:checked', $event.target.checked)"
    />
    <div class="chk-box">
      <Icon name="check" :size="13" />
    </div>
    <div class="chk-label">
      <strong>{{ code }}</strong> {{ name }}
    </div>
  </label>
</template>

<script setup>
import Icon from './Icon.vue';

defineProps({
  code: {
    type: String,
    required: true
  },
  name: {
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
.chk-tile {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 9px;
  border: 1.5px solid var(--surface-line);
  background: var(--field-bg);
  cursor: pointer;
  transition: 0.13s;
}

.chk-tile:hover:not(.is-disabled) {
  border-color: var(--ink-faint);
}

.chk-tile input {
  display: none;
}

.chk-box {
  flex: none;
  width: 20px;
  height: 20px;
  border-radius: 6px;
  border: 1.6px solid var(--ink-faint);
  display: grid;
  place-items: center;
  color: transparent;
  transition: 0.13s;
}

.chk-tile.is-on {
  border-color: var(--accent);
  background: color-mix(in srgb, var(--accent) 10%, var(--field-bg));
}

.chk-tile.is-on .chk-box {
  background: var(--accent);
  border-color: var(--accent);
  color: var(--accent-ink);
}

.chk-label {
  font-size: 13.5px;
  line-height: 1.25;
  color: var(--ink);
}

.chk-label strong {
  font-weight: 700;
}

.is-disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
