<template>
  <div class="observation-layout" :style="themeVars">
    <!-- Optional Navigation Bar -->
    <nav v-if="showNav" class="observation-nav">
      <div class="observation-nav__container">
        <div class="observation-nav__left">
          <button 
            @click="router.visit('/')" 
            class="observation-nav__back"
            type="button"
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Back to Dashboard</span>
          </button>
        </div>
        
        <div v-if="$page.props.auth?.user" class="observation-nav__right">
          <span class="observation-nav__user">{{ $page.props.auth.user.name }}</span>
          <button type="button" class="observation-nav__logout" @click="logout" title="Log out">
            <Icon name="logout" :size="18" />
            <span>Log out</span>
          </button>
        </div>
      </div>
    </nav>
    
    <slot></slot>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { THEMES } from '../data/observationData';
import Icon from '../Components/Observation/Icon.vue';

function logout() {
  router.post('/logout');
}

const props = defineProps({
  showNav: {
    type: Boolean,
    default: true
  }
});

const page = usePage();

// Apply Navy theme (Stadium Navy) as default
const themeVars = computed(() => {
  const theme = THEMES.navy;
  return theme.vars;
});
</script>

<style scoped>
.observation-layout {
  min-height: 100vh;
  background: var(--primary);
  font-family: var(--font-heading, 'Archivo', sans-serif);
}

/* Optional Navigation */
.observation-nav {
  background: var(--primary-light);
  border-bottom: 1px solid var(--border);
  padding: 0.75rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
}

.observation-nav__container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.observation-nav__back {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: transparent;
  border: 1px solid var(--border);
  color: var(--text);
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.observation-nav__back:hover {
  background: var(--bg-card);
  border-color: var(--accent);
  color: var(--accent);
}

.observation-nav__right {
  display: flex;
  align-items: center;
  gap: 14px;
}

.observation-nav__user {
  color: var(--text-muted);
  font-size: 0.9rem;
}

.observation-nav__logout {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: transparent;
  border: 1px solid var(--border);
  color: var(--text-muted);
  padding: 0.4rem 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.observation-nav__logout:hover {
  color: var(--accent);
  border-color: var(--accent);
  background: var(--bg-card);
}

/* Apply theme to all children */
.observation-layout :deep(*) {
  box-sizing: border-box;
}

@media (max-width: 768px) {
  .observation-nav__container {
    padding: 0 1rem;
  }
}
</style>
