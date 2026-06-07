<template>
  <ObservationLayout :showNav="false">
    <div class="app-root admin-page-root">
      <div class="band"></div>

      <!-- Masthead -->
      <header class="masthead">
        <div class="mast-inner">
          <div class="mast-brand">
            <svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true">
              <path d="M24 2l18 7v12c0 12-8.5 19.5-18 25C14.5 40.5 6 33 6 21V9z" fill="var(--accent)" />
              <path d="M24 2l18 7v12c0 12-8.5 19.5-18 25z" fill="rgba(0,0,0,0.14)" />
              <path d="M16 19h16M16 25h16M16 31h10" stroke="var(--accent-ink)" stroke-width="2.4" stroke-linecap="round" />
              <circle cx="24" cy="13" r="3" fill="var(--accent-ink)" />
            </svg>
            <div class="mast-titles">
              <span class="mast-kicker">Observation Programme</span>
              <h1 class="mast-title">Submissions Console</h1>
            </div>
          </div>
          <button type="button" class="btn solid" @click="printAll">
            <Icon name="download" :size="17" />
            Export PDF
          </button>
        </div>

        <div class="stat-strip">
          <div class="stat"><b>{{ stats.reports }}</b><span>Reports</span></div>
          <div class="stat"><b>{{ stats.venues }}</b><span>Venues</span></div>
          <div class="stat"><b>{{ stats.entries }}</b><span>Entries</span></div>
          <div class="stat"><b>{{ stats.photos }}</b><span>Photos</span></div>
          <div class="stat-spacer"></div>
          <button
            v-for="a in APPLICABILITY"
            :key="a.id"
            type="button"
            :class="['stat-appl', 'tone-' + applTone(a.id), fAppl === a.id ? 'is-on' : '']"
            :title="a.label"
            @click="fAppl = fAppl === a.id ? '' : a.id"
          >
            <b>{{ stats.byAppl[a.id] || 0 }}</b>
            <span>{{ a.label }}</span>
          </button>
        </div>
      </header>

      <!-- Main console -->
      <main class="console">
        <!-- Toolbar -->
        <div class="toolbar">
          <div class="search">
            <Icon name="search" :size="18" />
            <input
              v-model="q"
              type="text"
              placeholder="Search descriptions, venues, reporters…"
            />
            <button v-if="q" type="button" class="search-x" @click="q = ''">
              <Icon name="x" :size="14" />
            </button>
          </div>

          <div :class="['filter', fVenue ? 'is-active' : '']">
            <div class="select-wrap">
              <select v-model="fVenue" class="admin-select">
                <option value="">Venue: All</option>
                <option v-for="v in activeVenues" :key="v" :value="v">{{ v }}</option>
              </select>
              <Icon name="chevron" :size="16" class="select-caret" />
            </div>
          </div>

          <div :class="['filter', fArea ? 'is-active' : '']">
            <div class="select-wrap">
              <select v-model="fArea" class="admin-select">
                <option value="">Functional area: All</option>
                <option
                  v-for="f in functionalAreas.filter(x => x.code !== 'Other')"
                  :key="f.code"
                  :value="f.code"
                >{{ f.code }} — {{ f.name }}</option>
              </select>
              <Icon name="chevron" :size="16" class="select-caret" />
            </div>
          </div>

          <div :class="['filter', fType ? 'is-active' : '']">
            <div class="select-wrap">
              <select v-model="fType" class="admin-select">
                <option value="">Type: All</option>
                <option v-for="t in ENTRY_TYPES" :key="t.id" :value="t.id">{{ t.label }}</option>
              </select>
              <Icon name="chevron" :size="16" class="select-caret" />
            </div>
          </div>

          <div class="filter">
            <div class="select-wrap">
              <select v-model="sort" class="admin-select">
                <option value="date">Newest first</option>
                <option value="venue">Venue A–Z</option>
                <option value="appl">Applicability</option>
              </select>
              <Icon name="chevron" :size="16" class="select-caret" />
            </div>
          </div>

          <button v-if="anyFilter" type="button" class="reset-btn" @click="reset">
            <Icon name="x" :size="14" /> Clear
          </button>
        </div>

        <div class="result-count">
          {{ filteredEntries.length }}
          {{ filteredEntries.length === 1 ? 'entry' : 'entries' }}{{ anyFilter ? ' match your filters' : ' total' }}
        </div>

        <!-- Entry rows -->
        <div class="entry-rows">
          <article
            v-for="entry in filteredEntries"
            :key="entry.id"
            class="erow"
            @click="selected = entry"
          >
            <div class="erow-glyph">
              <Icon :name="venueIcon(entry.venue)" :size="22" />
            </div>
            <div class="erow-main">
              <div class="erow-top">
                <span class="erow-venue">{{ entry.venue }}</span>
                <span class="erow-sep"></span>
                <span v-for="tp in entry.types" :key="tp" class="type-tag">{{ typeLabel(tp) }}</span>
                <span :class="['appl-chip', 'tone-' + applTone(entry.applicability)]">
                  {{ applLabel(entry.applicability) }}
                </span>
              </div>
              <p class="erow-desc">{{ entry.description }}</p>
              <div class="erow-foot">
                <span class="erow-areas">
                  <span v-for="c in entry.areas" :key="c" class="fa-pill">{{ c }}</span>
                </span>
                <span class="erow-meta">
                  <span v-if="entry.photos && entry.photos.length > 0" class="meta-bit">
                    <Icon name="camera" :size="14" /> {{ entry.photos.length }}
                  </span>
                  <span class="meta-bit">{{ entry.reporter.name }}</span>
                  <span class="meta-bit">{{ fmtDate(entry.submittedAt) }}</span>
                </span>
              </div>
            </div>
            <div v-if="entry.photos && entry.photos[0]" class="erow-thumb">
              <img :src="entry.photos[0].url" :alt="entry.photos[0].caption || ''" />
            </div>
            <button
              type="button"
              class="erow-print"
              title="Export this entry as PDF"
              @click.stop="printEntry(entry)"
            >
              <Icon name="download" :size="17" />
            </button>
          </article>

          <div v-if="filteredEntries.length === 0" class="empty">
            <Icon name="search" :size="28" />
            <p>No entries match your filters.</p>
            <button type="button" class="btn ghost" @click="reset">Clear filters</button>
          </div>
        </div>
      </main>

      <!-- Detail Drawer -->
      <Teleport to="body">
        <div v-if="selected" class="drawer-scrim" @click="selected = null">
          <aside class="drawer" @click.stop>
            <header class="drawer-head">
              <div class="drawer-venue">
                <span class="venue-glyph">
                  <Icon :name="venueIcon(selected.venue)" :size="22" />
                </span>
                <div>
                  <span class="venue-kicker">Venue</span>
                  <span class="dh-name">{{ selected.venue }}</span>
                </div>
              </div>
              <button type="button" class="icon-btn" @click="selected = null">
                <Icon name="x" :size="18" />
              </button>
            </header>

            <div class="drawer-body">
              <div class="dh-tags">
                <span v-for="tp in selected.types" :key="tp" class="type-tag big">{{ typeLabel(tp) }}</span>
                <span :class="['appl-chip', 'big', 'tone-' + applTone(selected.applicability)]">
                  {{ applLabel(selected.applicability) }}
                </span>
              </div>

              <section class="d-sec">
                <h4>Description</h4>
                <p class="d-desc">{{ selected.description }}</p>
              </section>

              <section v-if="selected.photos && selected.photos.length > 0" class="d-sec">
                <h4>Photo attachments · {{ selected.photos.length }}</h4>
                <div class="d-gallery">
                  <figure v-for="(p, i) in selected.photos" :key="i" class="d-photo">
                    <img :src="p.url" :alt="p.caption || ''" />
                    <figcaption>{{ p.caption }}</figcaption>
                  </figure>
                </div>
              </section>

              <section class="d-sec">
                <h4>Functional area impact</h4>
                <div class="d-fa">
                  <div v-for="c in selected.areas" :key="c" class="d-fa-row">
                    <span class="d-fa-code">{{ c }}</span>
                    <span class="d-fa-name">{{ faName(c) }}</span>
                  </div>
                </div>
              </section>

              <section class="d-sec d-meta">
                <div>
                  <span class="d-meta-k">Reporter</span>
                  <span class="d-meta-v">{{ selected.reporter.name }}</span>
                </div>
                <div>
                  <span class="d-meta-k">Role</span>
                  <span class="d-meta-v">{{ selected.reporter.role || '—' }}</span>
                </div>
                <div>
                  <span class="d-meta-k">Submitted</span>
                  <span class="d-meta-v">{{ fmtDate(selected.submittedAt) }}</span>
                </div>
                <div>
                  <span class="d-meta-k">Reference</span>
                  <span class="d-meta-v mono">{{ selected.id.toUpperCase() }}</span>
                </div>
              </section>
            </div>

            <footer class="drawer-foot">
              <button type="button" class="btn ghost" @click="selected = null">Close</button>
              <button type="button" class="btn solid" @click="printEntry(selected)">
                <Icon name="download" :size="16" /> Export PDF
              </button>
            </footer>
          </aside>
        </div>
      </Teleport>

      <!-- Print root (hidden, shown via @media print) -->
      <div class="print-root">
        <div v-if="printTarget" class="print-doc">
          <div class="pr-head">
            <svg width="40" height="40" viewBox="0 0 48 48" fill="none">
              <path d="M24 2l18 7v12c0 12-8.5 19.5-18 25C14.5 40.5 6 33 6 21V9z" fill="#C6F24E" />
              <path d="M24 2l18 7v12c0 12-8.5 19.5-18 25z" fill="rgba(0,0,0,0.14)" />
              <path d="M16 19h16M16 25h16M16 31h10" stroke="#16320a" stroke-width="2.4" stroke-linecap="round" />
              <circle cx="24" cy="13" r="3" fill="#16320a" />
            </svg>
            <div class="pr-title-wrap">
              <span class="pr-kicker">Observation Programme</span>
              <h1 class="pr-title">{{ printTarget.mode === 'one' ? 'Observation Entry' : 'Observation Report Export' }}</h1>
            </div>
            <div class="pr-meta">
              <span>Generated {{ todayFormatted }}</span>
              <span>{{ printTarget.entries.length }} {{ printTarget.entries.length === 1 ? 'entry' : 'entries' }}</span>
            </div>
          </div>

          <div v-for="(e, i) in printTarget.entries" :key="e.id" class="pr-entry">
            <div class="pr-entry-head">
              <span class="pr-no">{{ i + 1 }}</span>
              <div class="pr-eh-text">
                <span class="pr-eh-venue">{{ e.venue }}</span>
                <span class="pr-eh-sub">{{ e.types.map(typeLabel).join(' · ') }} — {{ applLabel(e.applicability) }}</span>
              </div>
              <span class="pr-eh-date">{{ fmtDate(e.submittedAt) }}</span>
            </div>
            <p class="pr-desc">{{ e.description }}</p>
            <div class="pr-fa">
              <span v-for="c in e.areas" :key="c" class="pr-fa-item"><b>{{ c }}</b> {{ faName(c) }}</span>
            </div>
            <div v-if="e.photos && e.photos.length > 0" class="pr-photos">
              <figure v-for="(p, pi) in e.photos" :key="pi">
                <img :src="p.url" alt="" />
                <figcaption>{{ p.caption }}</figcaption>
              </figure>
            </div>
            <div class="pr-by">
              Reported by {{ e.reporter.name }} · {{ e.reporter.role || '' }} · Ref {{ e.id.toUpperCase() }}
            </div>
          </div>

          <div class="pr-footer">Observation Programme — confidential review document</div>
        </div>
      </div>
    </div>
  </ObservationLayout>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import ObservationLayout from '../../Layouts/ObservationLayout.vue';
import Icon from '../../Components/Observation/Icon.vue';
import { ENTRY_TYPES, APPLICABILITY, VENUE_TYPES } from '../../data/observationData';

const props = defineProps({
  submissions: { type: Array, default: () => [] },
  functionalAreas: { type: Array, default: () => [] },
});

// Flatten all entries from submissions with full context
const allEntries = computed(() => {
  const list = [];
  props.submissions.forEach((r) => {
    r.venues.forEach((v) => {
      v.entries.forEach((e, i) => {
        list.push({
          ...e,
          id: `${r.id}-${v.type.replace(/\W+/g, '')}-${i}`,
          venue: v.type,
          reporter: r.reporter,
          reportId: r.id,
          submittedAt: r.submittedAt,
        });
      });
    });
  });
  return list;
});

// Filters
const q = ref('');
const fVenue = ref('');
const fArea = ref('');
const fAppl = ref('');
const fType = ref('');
const sort = ref('date');
const selected = ref(null);
const printTarget = ref(null);

const anyFilter = computed(() => q.value || fVenue.value || fArea.value || fAppl.value || fType.value);

const activeVenues = computed(() => {
  const seen = new Set(allEntries.value.map((e) => e.venue));
  return VENUE_TYPES.filter((v) => seen.has(v));
});

const applOrder = APPLICABILITY.map((a) => a.id);

const filteredEntries = computed(() => {
  let list = allEntries.value.filter((e) => {
    if (fVenue.value && e.venue !== fVenue.value) return false;
    if (fArea.value && !e.areas.includes(fArea.value)) return false;
    if (fAppl.value && e.applicability !== fAppl.value) return false;
    if (fType.value && !e.types.includes(fType.value)) return false;
    if (q.value) {
      const hay = `${e.description} ${e.venue} ${e.reporter.name}`.toLowerCase();
      if (!hay.includes(q.value.toLowerCase())) return false;
    }
    return true;
  });

  return [...list].sort((a, b) => {
    if (sort.value === 'date') return b.submittedAt - a.submittedAt;
    if (sort.value === 'venue') return a.venue.localeCompare(b.venue);
    if (sort.value === 'appl') return applOrder.indexOf(a.applicability) - applOrder.indexOf(b.applicability);
    return 0;
  });
});

// Stats
const stats = computed(() => {
  const byAppl = {};
  APPLICABILITY.forEach((a) => (byAppl[a.id] = 0));
  allEntries.value.forEach((e) => {
    if (byAppl[e.applicability] !== undefined) byAppl[e.applicability]++;
  });
  return {
    reports: props.submissions.length,
    venues: new Set(allEntries.value.map((e) => e.venue)).size,
    entries: allEntries.value.length,
    photos: allEntries.value.reduce((n, e) => n + (e.photos ? e.photos.length : 0), 0),
    byAppl,
  };
});

// Helpers
const APPL_TONE = { must_have: 'must', good_to_have: 'good', already: 'done', na: 'na', assess: 'assess' };
const VENUE_ICON_MAP = {
  'Stadium': 'stadium',
  'Training Ground': 'ball',
  'Club Headquarters': 'shield',
  'Academy / Youth Development Centre': 'badge',
  'Medical / Sports Science Facility': 'whistle',
  'Fan Zone / Stadium Precinct': 'flag',
  'Community Programme Venue': 'people',
  'Supporter Trust / Fan Organisation': 'people',
  'Broadcasting / Media Centre': 'broadcast',
  'Retail / Merchandising Outlet': 'badge',
  'Other (please specify)': 'pin',
};

const applTone = (id) => APPL_TONE[id] || 'na';
const applLabel = (id) => APPLICABILITY.find((a) => a.id === id)?.label || id;
const typeLabel = (id) => ENTRY_TYPES.find((t) => t.id === id)?.label || id;
const faName = (code) => props.functionalAreas.find((f) => f.code === code)?.name || code;
const venueIcon = (venue) => VENUE_ICON_MAP[venue] || 'pin';
const fmtDate = (ms) => new Date(ms).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

const todayFormatted = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });

function reset() {
  q.value = '';
  fVenue.value = '';
  fArea.value = '';
  fAppl.value = '';
  fType.value = '';
}

// Print
function printEntry(entry) {
  printTarget.value = { mode: 'one', entries: [entry] };
  triggerPrint();
}

function printAll() {
  printTarget.value = { mode: 'list', entries: filteredEntries.value };
  triggerPrint();
}

function triggerPrint() {
  const cleanup = () => {
    printTarget.value = null;
    window.removeEventListener('afterprint', cleanup);
  };
  window.addEventListener('afterprint', cleanup);
  setTimeout(() => {
    try { window.print(); } catch (_) {}
  }, 350);
}

// Escape key to close drawer
function onKeyDown(e) {
  if (e.key === 'Escape') selected.value = null;
}

onMounted(() => window.addEventListener('keydown', onKeyDown));
onBeforeUnmount(() => window.removeEventListener('keydown', onKeyDown));
</script>

<style scoped>
</style>
