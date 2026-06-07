<template>
  <svg
    :width="size"
    :height="size"
    :viewBox="viewBox"
    :class="['obs-icon', `obs-icon--${name}`]"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <g v-if="name === 'plus'">
      <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
    </g>
    
    <g v-else-if="name === 'trash'">
      <path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>
    
    <g v-else-if="name === 'photo'">
      <path d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M8.5 10a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM21 15l-5-5L5 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>
    
    <g v-else-if="name === 'check'">
      <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>
    
    <g v-else-if="name === 'alert'">
      <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
      <path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>
    
    <g v-else-if="name === 'info'">
      <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
      <path d="M12 16v-4M12 8h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>
    
    <g v-else-if="name === 'chevron-down'">
      <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>
    
    <g v-else-if="name === 'chevron-up'">
      <path d="M18 15l-6-6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>
    
    <g v-else-if="name === 'document'">
      <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M13 3v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>
    
    <g v-else-if="name === 'shield'">
      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>
    
    <g v-else-if="name === 'save'">
      <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M7 3v5h8M17 21v-8H7v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>
    
    <g v-else-if="name === 'clock'">
      <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
      <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>

    <g v-else-if="name === 'copy'">
      <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>

    <g v-else-if="name === 'chevron-right'">
      <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>

    <g v-else-if="name === 'chevron'">
      <polyline points="6 9 12 15 18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>

    <g v-else-if="name === 'search'">
      <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
      <line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>

    <g v-else-if="name === 'download'">
      <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>

    <g v-else-if="name === 'camera'">
      <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <circle cx="12" cy="13" r="4" stroke="currentColor" stroke-width="2"/>
    </g>

    <g v-else-if="name === 'x'">
      <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>

    <g v-else-if="name === 'eye'">
      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
    </g>

    <g v-else-if="name === 'eye-off'">
      <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>

    <!-- Venue glyphs -->
    <g v-else-if="name === 'stadium'">
      <ellipse cx="12" cy="9" rx="9" ry="4" stroke="currentColor" stroke-width="2"/>
      <path d="M3 9v5c0 2.2 4 4 9 4s9-1.8 9-4V9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      <path d="M8 11v6M16 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>

    <g v-else-if="name === 'ball'">
      <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
      <path d="M12 7l4 3-1.5 5h-5L8 10z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
    </g>

    <g v-else-if="name === 'broadcast'">
      <circle cx="12" cy="12" r="2" stroke="currentColor" stroke-width="2"/>
      <path d="M16.24 7.76a6 6 0 010 8.49M7.76 16.24a6 6 0 010-8.49M19.07 4.93a10 10 0 010 14.14M4.93 19.07a10 10 0 010-14.14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>

    <g v-else-if="name === 'whistle'">
      <circle cx="9" cy="14" r="5" stroke="currentColor" stroke-width="2"/>
      <path d="M14 12h7l-1 4h-5M9 9V6h3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </g>

    <g v-else-if="name === 'cone'">
      <path d="M10 3h4l4 18H6z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      <line x1="7.5" y1="12" x2="16.5" y2="12" stroke="currentColor" stroke-width="2"/>
      <line x1="3" y1="21" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>

    <g v-else-if="name === 'badge'">
      <path d="M8 2h8v5l-4 2-4-2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      <circle cx="12" cy="15" r="5" stroke="currentColor" stroke-width="2"/>
    </g>

    <g v-else-if="name === 'people'">
      <circle cx="9" cy="8" r="3" stroke="currentColor" stroke-width="2"/>
      <path d="M3 20a6 6 0 0112 0M16 6a3 3 0 010 6M21 20a6 6 0 00-4-5.6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>

    <g v-else-if="name === 'pin'">
      <path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
    </g>

    <g v-else-if="name === 'plane'">
      <path d="M17.8 19.2L16 11l3.5-3.5a2.12 2.12 0 00-3-3L13 8l-8.2-1.8a.5.5 0 00-.5.8l4 3.2-2.3 2.3-1.5-.3a.5.5 0 00-.5.8L6 17l1.2 2.5a.5.5 0 00.8.1l2.3-2.3 3.2 4a.5.5 0 00.8-.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </g>

    <g v-else-if="name === 'flag'">
      <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <line x1="4" y1="22" x2="4" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>

    <g v-else-if="name === 'logout'">
      <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <polyline points="16 17 21 12 16 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <line x1="21" y1="12" x2="9" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>

    <g v-else-if="name === 'settings'">
      <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
      <path d="M12 1v6m0 6v10M3.93 3.93l4.24 4.24m5.66 5.66l4.24 4.24M1 12h6m6 0h10M3.93 20.07l4.24-4.24m5.66-5.66l4.24-4.24" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </g>
  </svg>
</template>

<script setup>
defineProps({
  name: {
    type: String,
    required: true
  },
  size: {
    type: [Number, String],
    default: 24
  },
  viewBox: {
    type: String,
    default: '0 0 24 24'
  }
});
</script>

<style scoped>
.obs-icon {
  display: inline-block;
  vertical-align: middle;
  flex-shrink: 0;
}
</style>
