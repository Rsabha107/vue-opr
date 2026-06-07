<template>
  <div class="venue-card">
    <!-- Header -->
    <div class="venue-head">
      <div class="venue-index">
        <span>{{ String(venueIndex + 1).padStart(2, '0') }}</span>
      </div>
      <div class="venue-glyph">{{ venueIcon }}</div>
      <div class="venue-title">
        <div class="venue-kicker">VENUE</div>
        <div class="venue-name">{{ venue.type || 'Select a venue' }}</div>
      </div>
      <div class="venue-head-actions">
        <span class="venue-entry-count">{{ entryCount }}</span>
        <button
          v-if="canDelete"
          type="button"
          @click.stop="$emit('delete')"
          class="icon-btn danger"
          title="Delete venue"
        >
          <Icon name="trash" :size="16" />
        </button>
        <button
          type="button"
          class="icon-btn collapse-btn"
          :class="{ 'is-rotated': isCollapsed }"
          @click="toggleCollapse"
          :title="isCollapsed ? 'Expand' : 'Collapse'"
        >
          <Icon name="chevron-down" :size="18" />
        </button>
      </div>
    </div>

    <!-- Body (collapsible) -->
    <div v-show="!isCollapsed" class="venue-body">
      <Field
        label="Venue"
        rightHint="Single select"
        :error="errors.type"
        :showError="showErrors"
        required
      >
        <select
          v-model="venue.type"
          class="select"
          @change="$emit('update', { ...venue, type: $event.target.value })"
        >
          <option value="">Choose a venue...</option>
          <option v-for="type in VENUE_TYPES" :key="type" :value="type">
            {{ VENUE_GLYPH[type] }} {{ type }}
          </option>
        </select>
      </Field>

      <Field
        v-if="venue.type === 'Other (please specify)'"
        label="Specify Venue Type"
        :error="errors.otherText"
        :showError="showErrors"
        required
      >
        <input
          v-model="venue.otherText"
          type="text"
          class="input"
          placeholder="Please specify the venue type..."
          @input="$emit('update', { ...venue, otherText: $event.target.value })"
        />
      </Field>

      <!-- Entries section -->
      <div class="entries-head">
        <h4>OBSERVATION ENTRIES</h4>
        <div class="entries-line"></div>
      </div>

      <div class="entry-list">
        <EntryCard
          v-for="(entry, index) in venue.entries"
          :key="entry.id"
          :entry="entry"
          :entryIndex="index"
          :errors="errors.entries?.[index] || {}"
          :showErrors="showErrors"
          :canDelete="venue.entries.length > 1"
          :functionalAreas="functionalAreas"
          @update="updateEntry(index, $event)"
          @delete="deleteEntry(index)"
          @duplicate="duplicateEntry(index)"
        />
      </div>

      <button type="button" @click="addEntry" class="add-entry-btn">
        <Icon name="plus" :size="18" />
        <span>Add another observation</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import Field from './Field.vue';
import Icon from './Icon.vue';
import EntryCard from './EntryCard.vue';
import { VENUE_TYPES, VENUE_GLYPH } from '../../data/observationData';

const props = defineProps({
  venue: {
    type: Object,
    required: true
  },
  venueIndex: {
    type: Number,
    required: true
  },
  errors: {
    type: Object,
    default: () => ({})
  },
  showErrors: {
    type: Boolean,
    default: false
  },
  canDelete: {
    type: Boolean,
    default: false
  },
  functionalAreas: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['update', 'delete']);

const isCollapsed = ref(false);

function toggleCollapse() {
  isCollapsed.value = !isCollapsed.value;
}

const venueIcon = computed(() => props.venue.type ? VENUE_GLYPH[props.venue.type] || '📍' : '📍');

const entryCount = computed(() => {
  const n = props.venue.entries?.length || 0;
  return `${n} ${n === 1 ? 'entry' : 'entries'}`;
});

function duplicateEntry(index) {
  const copy = {
    ...props.venue.entries[index],
    id: `entry-${Date.now()}-${Math.random().toString(36).slice(2, 11)}`,
    photos: []
  };
  const newEntries = [...props.venue.entries];
  newEntries.splice(index + 1, 0, copy);
  emit('update', { ...props.venue, entries: newEntries });
}

function updateEntry(index, updatedEntry) {
  const newEntries = [...props.venue.entries];
  newEntries[index] = updatedEntry;
  emit('update', { ...props.venue, entries: newEntries });
}

function deleteEntry(index) {
  const newEntries = [...props.venue.entries];
  newEntries.splice(index, 1);
  emit('update', { ...props.venue, entries: newEntries });
}

function addEntry() {
  const newEntry = {
    id: `entry-${Date.now()}-${Math.random().toString(36).slice(2, 11)}`,
    types: {},
    description: '',
    photos: [],
    areas: {},
    areaOther: '',
    applicability: ''
  };
  emit('update', { ...props.venue, entries: [...props.venue.entries, newEntry] });
}
</script>

<style scoped>
.venue-card {
  overflow: hidden;
}

.venue-head {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 16px 22px;
  user-select: none;
  background: var(--surface-2);
  border-bottom: 1px solid var(--surface-line);
}

.venue-index {
  flex: none;
  width: 46px;
  height: 46px;
  display: grid;
  place-items: center;
  background: var(--accent);
  border-radius: 10px;
  transform: skewX(-7deg);
}

.venue-index span {
  font-family: var(--font-display);
  font-weight: 900;
  font-size: 20px;
  color: var(--accent-ink);
  transform: skewX(7deg);
  font-variant-numeric: tabular-nums;
}

.venue-glyph {
  flex: none;
  font-size: 22px;
  opacity: 0.7;
}

.venue-title {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1px;
  min-width: 0;
}

.venue-kicker {
  font-family: var(--font-display);
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.18em;
  font-size: 10px;
  color: var(--ink-faint);
}

.venue-name {
  font-family: var(--font-display);
  font-weight: 700;
  font-size: 18px;
  color: var(--ink);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.venue-head-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

.venue-entry-count {
  font-family: var(--font-body);
  font-size: 13px;
  color: var(--ink-soft);
  margin-right: 4px;
}

.collapse-btn {
  transition: transform 0.2s ease;
}

.collapse-btn.is-rotated {
  transform: rotate(180deg);
}

.venue-body {
  padding: var(--card-pad);
  display: flex;
  flex-direction: column;
  gap: var(--field-gap);
}

.entries-head {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-top: 4px;
}

.entries-head h4 {
  margin: 0;
  font-family: var(--font-display);
  font-weight: 700;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.14em;
  color: var(--ink-soft);
  white-space: nowrap;
}

.entries-line {
  flex: 1;
  height: 1px;
  background: var(--surface-line);
}

.entry-list {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.add-entry-btn {
  align-self: flex-start;
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 10px 16px;
  border-radius: 9px;
  border: 1.5px solid var(--surface-line);
  background: transparent;
  color: var(--ink-soft);
  font-family: var(--font-display);
  font-weight: 700;
  font-size: 13px;
  cursor: pointer;
  transition: 0.14s;
}

.add-entry-btn:hover {
  border-color: var(--accent);
  color: var(--accent);
}

@media (max-width: 480px) {
  .venue-head {
    padding: 12px 14px;
    gap: 10px;
  }

  /* Hide "N entries" text — visible below the fold when expanded */
  .venue-entry-count {
    display: none;
  }

  /* Tighten the number badge slightly */
  .venue-index {
    width: 40px;
    height: 40px;
  }

  .venue-index span {
    font-size: 17px;
  }

  .venue-name {
    font-size: 16px;
  }

  /* Stretch "Add another" button full-width on mobile */
  .add-entry-btn {
    align-self: stretch;
    justify-content: center;
    padding: 13px 16px;
  }
}
</style>
