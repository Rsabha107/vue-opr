<template>
  <ObservationLayout>
    <div class="app-root">
      <div class="band"></div>
      
      <!-- Header / Masthead -->
      <div class="masthead">
        <div class="mast-inner">
          <div class="mast-brand">
            <img src="/assets/img/sc_logo_white.png" alt="Logo" class="mast-logo" />
            <!-- <Icon name="shield" :size="40" class="obs-header__logo" /> -->
            <div class="mast-titles">
              <div class="mast-kicker">OBSERVATION PROGRAMME</div>
              <h1 class="mast-title">End-of-Assignment Report</h1>
            </div>
          </div>
          
          <div class="mast-actions">
            <button 
              type="button" 
              class="btn-admin"
              @click="router.visit('/observation/admin')"
              title="Go to admin"
            >
              <Icon name="settings" :size="16" />
              <span>Admin</span>
            </button>
            <button 
              type="button" 
              class="btn-clear"
              @click="showClearModal = true"
              title="Clear form"
            >
              <Icon name="x" :size="16" />
              <span>Clear form</span>
            </button>
          </div>
        </div>
        
        <div class="mast-stats">
          <b>{{ totalVenues }}</b>
          <span>{{ totalVenues === 1 ? 'venue' : 'venues' }}</span>
          <div class="dot"></div>
          <b>{{ totalEntries }}</b>
          <span>{{ totalEntries === 1 ? 'entry' : 'entries' }}</span>
          <template v-if="lastSaved">
            <div class="dot"></div>
            <span class="saved">Saved {{ lastSaved }}</span>
          </template>
          <div class="dot"></div>
          <button 
            type="button" 
            class="autosave-toggle"
            :class="{ 'is-enabled': autosaveEnabled }"
            @click="autosaveEnabled = !autosaveEnabled"
            :title="autosaveEnabled ? 'Disable autosave' : 'Enable autosave'"
          >
            <span>Autosaved</span>
            <span class="toggle-state">{{ autosaveEnabled ? 'ON' : 'OFF' }}</span>
          </button>
        </div>
      </div>
      
      <form @submit.prevent="handleSubmit" class="sheet">
        <!-- Draft resume banner -->
        <div v-if="showDraftBanner" class="draft-banner">
          <div class="db-head">
            <Icon name="save" :size="16" />
            <span>{{ drafts.length === 1 ? 'You have a saved draft' : `You have ${drafts.length} saved drafts` }}</span>
          </div>
          <div class="db-list">
            <div v-for="draft in drafts" :key="draft.id" class="db-row">
              <div class="db-info">
                <span class="db-name">{{ draft.reporter.name || 'Untitled report' }}</span>
                <span class="db-meta">
                  {{ draft.venues.length }} {{ draft.venues.length === 1 ? 'venue' : 'venues' }}
                  &middot;
                  {{ draft.totalEntries }} {{ draft.totalEntries === 1 ? 'entry' : 'entries' }}
                  &middot; saved {{ fmtSaved(draft.savedAt) }}
                </span>
              </div>
              <div class="db-actions">
                <button 
                  type="button" 
                  class="icon-btn db-delete" 
                  @click="deleteDraft(draft.id)"
                  title="Delete draft"
                >
                  <Icon name="trash" :size="16" />
                </button>
                <button type="button" class="btn solid db-resume" @click="resumeDraft(draft)">Resume</button>
              </div>
            </div>
          </div>
          <button type="button" class="db-dismiss" @click="showDraftBanner = false">
            Start fresh instead
          </button>
        </div>

        <!-- Reporter Section -->
        <div class="reporter-card">
          <div class="rc-head">
            <div class="rc-chip">REPORTER</div>
            <h3>Who is submitting this report?</h3>
          </div>
          
          <div class="rc-grid">
            <Field
              label="Your Full Name"
              :error="getFieldError('reporter.name')"
              :showError="showErrors"
              required
            >
              <input
                v-model="form.reporter.name"
                type="text"
                class="input"
                placeholder="e.g. Your Name"
              />
            </Field>
            
            <Field
              label="Role / Function"
              hint="Optional"
            >
              <input
                v-model="form.reporter.role"
                type="text"
                class="input"
                placeholder="e.g. Sporting Operations Observer"
              />
            </Field>
            
            <Field
              label="Assignment / Programme"
              hint="Optional"
            >
              <input
                v-model="form.reporter.programme"
                type="text"
                class="input"
                placeholder="e.g. Assignment 2025 – Observer 2"
              />
            </Field>
          </div>
        </div>
        
        <!-- Venues Section -->
            <VenueCard
              v-for="(venue, index) in form.venues"
              :key="venue.id"
              :venue="venue"
              :venueIndex="index"
              :errors="errors.venues?.[index] || {}"
              :showErrors="showErrors"
              :canDelete="form.venues.length > 1"
              :functionalAreas="functionalAreas"
              @update="updateVenue(index, $event)"
              @delete="deleteVenue(index)"
            />
            
        <button
          type="button"
          @click="addVenue"
          class="add-venue-btn"
        >
          <Icon name="plus" :size="24" />
          <span>Add Another Venue</span>
        </button>
      </form>
        
      <!-- Actions -->
      <div class="action-bar">
        <div class="ab-inner">
          <div class="ab-left">
            <span v-if="draftSaveError" class="ab-status ab-status--error">
              Draft save failed — please try again
            </span>
            <span v-else-if="draftSavedAt" class="ab-status ab-status--ok">
              <Icon name="check" :size="14" />
              Draft saved {{ draftSavedAt }}
            </span>
            <span v-else class="ab-status">
              Ready when you are —
              {{ totalVenues }} {{ totalVenues === 1 ? 'venue' : 'venues' }},
              {{ totalEntries }} {{ totalEntries === 1 ? 'entry' : 'entries' }}
            </span>
          </div>
          <div class="ab-right">
            <button
              type="button"
              @click="router.visit('/')"
              class="btn ghost"
            >
              <span>Cancel</span>
            </button>
            <button
              type="button"
              @click="showDraftModal = true"
              class="btn ghost"
              :disabled="isSavingDraft"
            >
              <Icon name="save" :size="16" />
              <span>{{ isSavingDraft ? 'Saving...' : 'Save Draft' }}</span>
            </button>
            <button
              type="submit"
              @click="handleSubmit"
              class="btn solid"
              :disabled="isSubmitting"
            >
              <Icon v-if="!isSubmitting" name="check" :size="20" />
              <span>{{ isSubmitting ? 'Submitting...' : 'Submit Report' }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Save Draft confirmation modal -->
    <Teleport to="body">
      <div v-if="showDraftModal" class="modal-scrim" @click.self="showDraftModal = false">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modal-title">
          <div class="modal-head">
            <Icon name="save" :size="22" />
            <h2 id="modal-title">Save as Draft</h2>
          </div>
          <p class="modal-body">
            Your report will be saved as a draft — you can come back and continue editing it at any time.
            Nothing will be submitted until you click <strong>Submit Report</strong>.
          </p>
          <div class="modal-foot">
            <button type="button" class="btn ghost" @click="showDraftModal = false">Cancel</button>
            <button
              type="button"
              class="btn solid"
              :disabled="isSavingDraft"
              @click="showDraftModal = false; saveDraft()"
            >
              <Icon name="save" :size="16" />
              <span>{{ isSavingDraft ? 'Saving…' : 'Save Draft' }}</span>
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Delete Draft confirmation modal -->
    <Teleport to="body">
      <div v-if="showDeleteModal" class="modal-scrim" @click.self="showDeleteModal = false; draftToDelete = null">
        <div class="modal-card modal-delete" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
          <div class="modal-head modal-head-delete">
            <Icon name="trash" :size="22" />
            <h2 id="delete-modal-title">Delete Draft?</h2>
          </div>
          <div class="modal-body">
            <p class="delete-name">{{ draftToDelete?.reporter?.name || 'Untitled report' }}</p>
            <p class="delete-meta">
              This draft contains <strong>{{ draftToDelete?.totalEntries || 0 }}</strong> 
              {{ draftToDelete?.totalEntries === 1 ? 'entry' : 'entries' }}.
            </p>
            <p class="delete-warning">This action cannot be undone.</p>
          </div>
          <div class="modal-foot">
            <button 
              type="button" 
              class="btn ghost" 
              @click="showDeleteModal = false; draftToDelete = null"
              :disabled="isDeletingDraft"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn solid btn-danger"
              :disabled="isDeletingDraft"
              @click="confirmDeleteDraft()"
            >
              <Icon name="trash" :size="16" />
              <span>{{ isDeletingDraft ? 'Deleting…' : 'Delete Draft' }}</span>
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Clear Form confirmation modal -->
    <Teleport to="body">
      <div v-if="showClearModal" class="modal-scrim" @click.self="showClearModal = false">
        <div class="modal-card modal-delete" role="dialog" aria-modal="true" aria-labelledby="clear-modal-title">
          <div class="modal-head modal-head-delete">
            <Icon name="x" :size="22" />
            <h2 id="clear-modal-title">Clear Form?</h2>
          </div>
          <div class="modal-body">
            <p class="delete-meta">
              This will remove all your current work including:
            </p>
            <p class="delete-meta">
              <strong>{{ totalVenues }}</strong> {{ totalVenues === 1 ? 'venue' : 'venues' }}, 
              <strong>{{ totalEntries }}</strong> {{ totalEntries === 1 ? 'entry' : 'entries' }}, 
              and reporter information.
            </p>
            <p class="delete-warning">This action cannot be undone.</p>
          </div>
          <div class="modal-foot">
            <button 
              type="button" 
              class="btn ghost" 
              @click="showClearModal = false"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn solid btn-danger"
              @click="confirmClearForm()"
            >
              <Icon name="x" :size="16" />
              <span>Clear Form</span>
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </ObservationLayout>
</template>

<script setup>
import { ref, reactive, watch, onMounted, computed } from 'vue';

const props = defineProps({
  drafts: { type: Array, default: () => [] },
  functionalAreas: { type: Array, default: () => [] },
});
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import ObservationLayout from '../../Layouts/ObservationLayout.vue';
import Field from '../../Components/Observation/Field.vue';
import Icon from '../../Components/Observation/Icon.vue';
import VenueCard from '../../Components/Observation/VenueCard.vue';
import { useObservationValidation } from '../../composables/useObservationValidation';

// Form state
const form = reactive({
  reporter: {
    name: '',
    role: '',
    programme: ''
  },
  venues: [createNewVenue()]
});

// Validation
const { errors, showErrors, validateForm, getError } = useObservationValidation();

// Submission state
const isSubmitting = ref(false);
const lastSaved = ref(null);

// Draft resume banner
const showDraftBanner = ref(props.drafts.length > 0);

function fmtSaved(iso) {
  return new Date(iso).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
}

function resumeDraft(draft) {
  form.reporter = { ...draft.reporter };
  form.venues = draft.venues.map(v => ({
    id: `venue-${Date.now()}-${Math.random().toString(36).slice(2, 9)}`,
    ...v,
    entries: v.entries.map(e => ({
      id: `entry-${Date.now()}-${Math.random().toString(36).slice(2, 9)}`,
      ...e,
    })),
  }));
  draftId.value = draft.id;
  draftSavedAt.value = fmtSaved(draft.savedAt);
  showDraftBanner.value = false;
}

function deleteDraft(draftIdToDelete) {
  const draft = props.drafts.find(d => d.id === draftIdToDelete);
  draftToDelete.value = draft;
  showDeleteModal.value = true;
}

async function confirmDeleteDraft() {
  if (!draftToDelete.value) return;
  
  isDeletingDraft.value = true;
  
  try {
    await axios.delete(`/observation/draft/${draftToDelete.value.id}`);
    
    // Remove from local drafts array
    const index = props.drafts.findIndex(d => d.id === draftToDelete.value.id);
    if (index > -1) {
      props.drafts.splice(index, 1);
    }
    
    // Hide banner if no drafts left
    if (props.drafts.length === 0) {
      showDraftBanner.value = false;
    }
    
    // Close modal and reset
    showDeleteModal.value = false;
    draftToDelete.value = null;
  } catch (e) {
    console.error('Failed to delete draft:', e);
    alert('Failed to delete draft. Please try again.');
  } finally {
    isDeletingDraft.value = false;
  }
}

function confirmClearForm() {
  // Reset form to initial state
  form.reporter = {
    name: '',
    role: '',
    programme: ''
  };
  form.venues = [createNewVenue()];
  
  // Clear localStorage
  localStorage.removeItem('observation_form_draft_v1');
  
  // Reset validation
  showErrors.value = false;
  errors.value = {};
  
  // Reset save state
  lastSaved.value = null;
  draftId.value = null;
  draftSavedAt.value = null;
  
  // Close modal
  showClearModal.value = false;
}

// Draft save state
const showDraftModal = ref(false);
const isSavingDraft = ref(false);
const draftId = ref(null);
const draftSavedAt = ref(null);
const draftSaveError = ref(false);

// Draft delete state
const showDeleteModal = ref(false);
const draftToDelete = ref(null);
const isDeletingDraft = ref(false);

// Clear form state
const showClearModal = ref(false);

// Autosave state
const autosaveEnabled = ref(true);

// Computed stats
const totalVenues = computed(() => form.venues.length);
const totalEntries = computed(() => {
  return form.venues.reduce((total, venue) => {
    return total + (venue.entries?.length || 0);
  }, 0);
});

// Get field error helper
function getFieldError(path) {
  return getError(path);
}

// Create new venue
function createNewVenue() {
  return {
    id: `venue-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`,
    type: '',
    otherText: '',
    entries: [createNewEntry()]
  };
}

// Create new entry
function createNewEntry() {
  return {
    id: `entry-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`,
    types: {},
    description: '',
    photos: [],
    areas: {},
    areaOther: '',
    applicability: ''
  };
}

// Venue management
function updateVenue(index, updatedVenue) {
  form.venues[index] = updatedVenue;
}

function deleteVenue(index) {
  form.venues.splice(index, 1);
}

function addVenue() {
  form.venues.push(createNewVenue());
}

// Autosave (debounced)
let autosaveTimeout = null;

watch(form, () => {
  if (showErrors.value) {
    validateForm(form);
  }
  
  // Only autosave if enabled
  if (!autosaveEnabled.value) {
    return;
  }
  
  clearTimeout(autosaveTimeout);
  autosaveTimeout = setTimeout(() => {
    autosaveDraft();
  }, 700);
}, { deep: true });

function autosaveDraft() {
  // Save to localStorage (server-side saving would be implemented later)
  const draftData = {
    reporter: form.reporter,
    venues: form.venues.map(v => ({
      ...v,
      entries: v.entries.map(e => ({
        ...e,
        photos: [] // Exclude photos from draft as per CLAUDE.md
      }))
    }))
  };
  
  localStorage.setItem('observation_form_draft_v1', JSON.stringify(draftData));
  
  const now = new Date();
  lastSaved.value = now.toLocaleTimeString('en-US', { 
    hour: '2-digit', 
    minute: '2-digit' 
  });
}

// Load draft on mount
onMounted(() => {
  const savedDraft = localStorage.getItem('observation_form_draft_v1');
  if (savedDraft) {
    try {
      const draft = JSON.parse(savedDraft);
      form.reporter = draft.reporter || form.reporter;
      form.venues = draft.venues || form.venues;
      lastSaved.value = 'from previous session';
    } catch (e) {
      console.error('Failed to load draft:', e);
    }
  }
});

// Save draft
async function saveDraft() {
  isSavingDraft.value = true;
  draftSaveError.value = false;
  try {
    const payload = {
      draftId: draftId.value,
      reporter: form.reporter,
      venues: form.venues.map(v => ({
        ...v,
        entries: v.entries.map(e => ({
          ...e,
          photos: [],
          applicability: e.applicability || null,
        }))
      }))
    };
    const { data } = await axios.post('/observation/draft', payload);
    draftId.value = data.draftId;
    draftSavedAt.value = new Date(data.savedAt).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
  } catch (e) {
    draftSaveError.value = true;
    console.error('Draft save failed:', e);
  } finally {
    isSavingDraft.value = false;
  }
}

// Form submission
function handleSubmit() {
  showErrors.value = true;
  
  if (!validateForm(form)) {
    // Scroll to first error
    setTimeout(() => {
      const firstError = document.querySelector('.has-error');
      firstError?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 100);
    return;
  }
  
  isSubmitting.value = true;
  
  // Include draftId if we're submitting from a resumed draft
  const payload = {
    ...form,
    draftId: draftId.value
  };
  
  // Submit via Inertia
  router.post('/observation/form', payload, {
    onSuccess: () => {
      // Clear draft on successful submission
      localStorage.removeItem('observation_form_draft_v1');
    },
    onError: (errors) => {
      console.error('Submission errors:', errors);
      isSubmitting.value = false;
    },
    onFinish: () => {
      isSubmitting.value = false;
    }
  });
}
</script>

<style scoped>
/* ── Draft resume banner ──────────────────────────────────────── */
.draft-banner {
  background: var(--surface);
  border: 1.5px solid var(--accent);
  border-radius: 12px;
  padding: 18px 20px 14px;
  margin-bottom: 24px;
}

.db-head {
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--accent);
  font-weight: 700;
  font-size: 13px;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  margin-bottom: 14px;
}

.db-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 14px;
}

.db-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  background: var(--field-bg, #f5f7fb);
  border-radius: 8px;
  padding: 12px 14px;
}

.db-info {
  display: flex;
  flex-direction: column;
  gap: 3px;
  min-width: 0;
}

.db-name {
  font-weight: 600;
  font-size: 14px;
  color: var(--ink);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.db-meta {
  font-size: 12px;
  color: var(--ink);
  opacity: 0.55;
}

.db-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}

.db-delete {
  opacity: 0.4;
  transition: opacity 0.2s, color 0.2s;
}

.db-delete:hover {
  opacity: 1;
  color: #ef4444;
}

.db-resume {
  flex-shrink: 0;
  font-size: 13px;
  padding: 8px 16px;
}

.db-dismiss {
  background: none;
  border: none;
  padding: 0;
  font-size: 13px;
  color: var(--ink);
  opacity: 0.5;
  cursor: pointer;
  text-decoration: underline;
}

.db-dismiss:hover {
  opacity: 0.8;
}

.db-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}

/* ── Draft confirmation modal ─────────────────────────────────── */
.modal-scrim {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 900;
  padding: 16px;
}

.modal-card {
  background: var(--surface);
  border-radius: 14px;
  padding: 28px 28px 24px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
}

.modal-head {
  display: flex;
  align-items: center;
  gap: 10px;
  color: var(--accent);
  margin-bottom: 14px;
}

.modal-head-delete {
  color: #ef4444;
}

.modal-head-delete {
  color: #ef4444;
}

.modal-head h2 {
  font-size: 18px;
  font-weight: 700;
  color: var(--ink);
  margin: 0;
}

.modal-body {
  font-size: 14px;
  line-height: 1.6;
  color: var(--ink);
  opacity: 0.8;
  margin: 0 0 24px;
}

.modal-foot {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.modal-foot .btn.ghost {
  color: var(--ink);
  border-color: color-mix(in srgb, var(--ink) 30%, transparent);
}

.modal-foot .btn.ghost:hover {
  border-color: var(--ink);
  background: color-mix(in srgb, var(--ink) 5%, transparent);
}

/* ── Delete modal specific styles ────────────────────────────── */
.delete-name {
  font-weight: 600;
  font-size: 16px;
  color: var(--ink);
  margin-bottom: 8px;
}

.delete-meta {
  font-size: 14px;
  color: var(--ink);
  opacity: 0.7;
  margin-bottom: 12px;
}

.delete-warning {
  font-size: 13px;
  color: #ef4444;
  font-weight: 500;
  margin-bottom: 0;
}

.btn-danger {
  background: #ef4444;
  color: white;
}

.btn-danger:hover:not(:disabled) {
  background: #dc2626;
}

.btn-danger:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
