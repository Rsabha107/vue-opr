<template>
  <div class="entry-card">
    <div class="entry-bar">
      <div class="entry-no">ENTRY {{ entryIndex + 1 }}</div>
      <div class="entry-actions">
        <button
          type="button"
          @click="$emit('duplicate')"
          class="icon-btn"
          title="Duplicate observation"
        >
          <Icon name="copy" :size="16" />
        </button>
        <button
          v-if="canDelete"
          type="button"
          @click="$emit('delete')"
          class="icon-btn danger"
          title="Delete observation"
        >
          <Icon name="trash" :size="16" />
        </button>
      </div>
    </div>

    <div class="entry-body">
      <Field
        label="Type of entry"
        rightHint="Select all that apply"
        :error="errors.types"
        :showError="showErrors"
        required
      >
        <div class="pill-row">
          <PillCheck
            v-for="type in ENTRY_TYPES"
            :key="type.id"
            :label="type.label"
            :checked="entry.types[type.id] || false"
            @update:checked="updateType(type.id, $event)"
          />
        </div>
      </Field>

      <Field
        label="Description"
        :hint="`${charCount} / ${MIN_DESCRIPTION} min. characters`"
        :error="errors.description"
        :showError="showErrors"
        required
      >
        <textarea
          v-model="entry.description"
          rows="5"
          class="textarea"
          placeholder="Describe what you observed, the context, and why it matters..."
          @input="$emit('update', { ...entry, description: $event.target.value })"
        ></textarea>
      </Field>

      <Field label="Photo attachments">
        <PhotoUploader
          :photos="entry.photos || []"
          @update:photos="updatePhotos"
        />
      </Field>

      <Field
        label="Functional Area Impact"
        rightHint="Select all that apply"
      >
        <div class="fa-all">
          <CheckTile
            code="ALL"
            name="All Functional Areas"
            :checked="allAreasChecked"
            @update:checked="toggleAllAreas"
          />
        </div>
        <div class="fa-grid">
          <CheckTile
            v-for="area in functionalAreas"
            :key="area.code"
            :code="area.code"
            :name="area.name"
            :checked="entry.areas?.[area.code] || false"
            @update:checked="updateArea(area.code, $event)"
          />
        </div>
      </Field>

      <Field
        v-if="entry.areas?.Other"
        label="Specify Other Functional Area"
        :error="errors.areaOther"
        :showError="showErrors"
        required
      >
        <input
          v-model="entry.areaOther"
          type="text"
          class="input"
          placeholder="Please specify..."
          @input="$emit('update', { ...entry, areaOther: $event.target.value })"
        />
      </Field>

      <Field
        label="Applicability for Cover"
        :error="errors.applicability"
        :showError="showErrors"
        required
      >
        <RadioPills
          :options="APPLICABILITY"
          :modelValue="entry.applicability"
          @update:modelValue="updateApplicability"
          :name="`applicability-${entry.id}`"
        />
      </Field>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import Field from './Field.vue';
import Icon from './Icon.vue';
import PillCheck from './PillCheck.vue';
import CheckTile from './CheckTile.vue';
import RadioPills from './RadioPills.vue';
import PhotoUploader from './PhotoUploader.vue';
import { ENTRY_TYPES, APPLICABILITY, MIN_DESCRIPTION } from '../../data/observationData';

const props = defineProps({
  entry: {
    type: Object,
    required: true
  },
  entryIndex: {
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

const emit = defineEmits(['update', 'delete', 'duplicate']);

const charCount = computed(() => (props.entry.description || '').trim().length);

const AREAS_WITHOUT_OTHER = computed(() => 
  props.functionalAreas.filter(a => a.code !== 'Other')
);

const allAreasChecked = computed(() =>
  AREAS_WITHOUT_OTHER.value.every(a => props.entry.areas?.[a.code])
);

function toggleAllAreas(checked) {
  // Checks/unchecks every FA except "Other (please specify)"
  const newAreas = Object.fromEntries(AREAS_WITHOUT_OTHER.value.map(a => [a.code, checked]));
  emit('update', { ...props.entry, areas: newAreas });
}

function updateType(typeId, checked) {
  emit('update', { ...props.entry, types: { ...props.entry.types, [typeId]: checked } });
}

function updateArea(areaCode, checked) {
  emit('update', { ...props.entry, areas: { ...props.entry.areas, [areaCode]: checked } });
}

function updateApplicability(value) {
  emit('update', { ...props.entry, applicability: value });
}

function updatePhotos(photos) {
  emit('update', { ...props.entry, photos });
}
</script>
