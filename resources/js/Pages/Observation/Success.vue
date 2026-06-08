<template>
  <ObservationLayout :showNav="false">
    <div class="success-page">
      <div class="success-card">

        <!-- Check circle -->
        <div class="success-icon">
          <Icon name="check" :size="38" />
        </div>

        <!-- Kicker -->
        <div class="success-kicker">REPORT SUBMITTED</div>

        <!-- Heading -->
        <h1 class="success-title">Thank you, {{ reporterName || 'Observer' }}.</h1>

        <!-- Summary sentence -->
        <p class="success-sub">
          Your observation report covering
          <strong>{{ venues.length }} {{ venues.length === 1 ? 'venue' : 'venues' }}</strong>
          and
          <strong>{{ totalEntries }} {{ totalEntries === 1 ? 'entry' : 'entries' }}</strong>
          has been received.
        </p>

        <!-- Venue rows -->
        <div class="venue-summary">
          <div v-for="(venue, i) in venues" :key="i" class="venue-row">
            <span class="venue-row__num">{{ String(i + 1).padStart(2, '0') }}</span>
            <span class="venue-row__name">{{ venue.otherText || venue.type || `Venue ${i + 1}` }}</span>
            <span class="venue-row__count">{{ venue.entryCount }} {{ venue.entryCount === 1 ? 'entry' : 'entries' }}</span>
          </div>
        </div>

        <!-- Actions -->
        <div class="success-actions">
          <button type="button" @click="router.visit('/observation/form')" class="btn-ghost">
            Back to report
          </button>
          <button type="button" @click="router.visit('/observation/form')" class="btn-solid">
            Start a new report
          </button>
        </div>

        <!-- Footer note -->
        <p class="success-note">
          Data is structured for Excel / PDF export and filtering by venue,
          functional area, and applicability.
        </p>

      </div>
    </div>
  </ObservationLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import ObservationLayout from '../../Layouts/ObservationLayout.vue';
import Icon from '../../Components/Observation/Icon.vue';

defineProps({
  reportId: { type: String, default: null },
  reporterName: { type: String, default: null },
  venues: { type: Array, default: () => [] },
  totalEntries: { type: Number, default: 0 },
});
</script>

<style scoped>
.success-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
  background:
    radial-gradient(1200px 600px at 78% -8%, #1a2744, transparent 60%),
    repeating-linear-gradient(135deg, rgba(255,255,255,0.02) 0 2px, transparent 2px 22px),
    #0a1733;
}

.success-card {
  width: 100%;
  max-width: 520px;
  background: #ffffff;
  border-radius: 20px;
  padding: 48px 44px 36px;
  text-align: center;
  box-shadow: 0 32px 80px -20px rgba(0, 0, 0, 0.5);
}

/* Check icon */
.success-icon {
  width: 76px;
  height: 76px;
  border-radius: 50%;
  background: #7c1d2b;
  color: #ffffff;
  display: grid;
  place-items: center;
  margin: 0 auto 22px;
  animation: pop 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes pop {
  from { transform: scale(0); opacity: 0; }
  to   { transform: scale(1); opacity: 1; }
}

/* Kicker */
.success-kicker {
  font-family: 'Archivo', system-ui, sans-serif;
  font-weight: 700;
  font-size: 11px;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: #64748b;
  margin-bottom: 10px;
}

/* Title */
.success-title {
  font-family: 'Archivo', system-ui, sans-serif;
  font-weight: 800;
  font-size: 28px;
  color: #1e293b;
  margin: 0 0 12px;
  line-height: 1.15;
}

/* Subtitle */
.success-sub {
  font-size: 14.5px;
  color: #64748b;
  line-height: 1.6;
  margin: 0 0 24px;
}

.success-sub strong {
  color: #1e293b;
  font-weight: 700;
}

/* Venue rows */
.venue-summary {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 28px;
}

.venue-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 11px 16px;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  text-align: left;
}

.venue-row__num {
  font-family: 'Archivo', system-ui, sans-serif;
  font-weight: 800;
  font-size: 13px;
  color: #1e293b;
  flex: none;
}

.venue-row__name {
  flex: 1;
  font-weight: 600;
  font-size: 14px;
  color: #1e293b;
  min-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.venue-row__count {
  font-size: 13px;
  color: #94a3b8;
  flex: none;
}

/* Buttons */
.success-actions {
  display: flex;
  gap: 10px;
  margin-bottom: 22px;
}

.btn-ghost,
.btn-solid {
  flex: 1;
  padding: 13px 20px;
  border-radius: 11px;
  font-family: 'Archivo', system-ui, sans-serif;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: 0.15s;
  white-space: nowrap;
}

.btn-ghost {
  background: transparent;
  border: 1.5px solid #e2e8f0;
  color: #1e293b;
}

.btn-ghost:hover {
  border-color: #94a3b8;
}

.btn-solid {
  background: #7c1d2b;
  border: 1.5px solid #7c1d2b;
  color: #ffffff;
}

.btn-solid:hover {
  filter: brightness(1.06);
  transform: translateY(-1px);
}

/* Footer note */
.success-note {
  font-size: 12.5px;
  color: #94a3b8;
  line-height: 1.6;
  margin: 0;
}
</style>
