<script setup>
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import Icon from '@/Components/Observation/Icon.vue'
import { THEMES } from '@/data/observationData'

const props = defineProps({
  status: { type: String }
})

const form = useForm({ email: '' })

function submit() {
  form.post(route('password.email'))
}

// Apply Navy theme
const themeVars = computed(() => {
  return THEMES.navy.vars
})
</script>

<template>
  <Head title="Forgot Password" />
  <div class="obs-auth-page" :style="themeVars">
    <div class="obs-auth-container">
      <div class="obs-auth-card">
        <div class="obs-auth-header">
          <img src="/assets/img/sc_logo_white.png" alt="Logo" class="obs-auth-logo" />
          <h1 class="obs-auth-title">Reset Password</h1>
          <p class="obs-auth-subtitle">
            Enter your email address and we'll send you instructions to reset your password
          </p>
        </div>

        <div class="obs-auth-body">
          <div v-if="status" class="obs-alert obs-alert-success" role="alert">
            <Icon name="check" :size="16" />
            {{ status }}
          </div>

          <div v-if="form.errors.email" class="obs-alert obs-alert-error" role="alert">
            <Icon name="alert" :size="16" />
            {{ form.errors.email }}
          </div>

          <form @submit.prevent="submit" class="obs-form">
            <div class="obs-field">
              <label class="obs-label" for="email">
                Email Address
                <span class="obs-required">*</span>
              </label>
              <input
                class="obs-input"
                :class="{ 'has-error': form.errors.email }"
                id="email"
                type="email"
                placeholder="Enter your email"
                v-model="form.email"
                required
                autofocus
              />
              <div class="obs-error" v-if="form.errors.email">
                {{ form.errors.email }}
              </div>
            </div>

            <button
              type="submit"
              class="obs-btn obs-btn-primary"
              :disabled="form.processing"
            >
              <Icon v-if="!form.processing" name="send" :size="18" />
              <span v-if="form.processing" class="obs-spinner"></span>
              {{ form.processing ? 'Sending...' : 'Send Reset Link' }}
            </button>
          </form>

          <div class="obs-auth-footer">
            <Link class="obs-link obs-back-link" :href="route('mylogin')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M5 12L12 19M5 12L12 5"/>
              </svg>
              Back to Login
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.obs-auth-page {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
  font-family: 'Archivo', sans-serif;
}

.obs-auth-container {
  width: 100%;
  max-width: 480px;
}

.obs-auth-card {
  background: var(--bg-card);
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
  overflow: hidden;
}

.obs-auth-header {
  text-align: center;
  padding: 3rem 2rem 2rem;
  background: linear-gradient(180deg, rgba(124, 29, 43, 0.05) 0%, transparent 100%);
}

.obs-auth-logo {
  max-width: 180px;
  height: auto;
  margin-bottom: 1.5rem;
}

.obs-auth-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text);
  margin: 0 0 0.75rem;
  letter-spacing: -0.02em;
}

.obs-auth-subtitle {
  font-size: 14px;
  color: var(--text-muted);
  margin: 0;
  line-height: 1.6;
}

.obs-auth-body {
  padding: 2rem;
}

.obs-alert {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.875rem 1rem;
  border-radius: 8px;
  font-size: 14px;
  margin-bottom: 1.5rem;
}

.obs-alert-success {
  background: rgba(81, 207, 102, 0.1);
  border: 1px solid rgba(81, 207, 102, 0.3);
  color: var(--success);
}

.obs-alert-error {
  background: rgba(255, 107, 107, 0.1);
  border: 1px solid rgba(255, 107, 107, 0.3);
  color: var(--error);
}

.obs-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.obs-field {
  margin-bottom: 0;
}

.obs-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: var(--text);
  margin-bottom: 0.5rem;
}

.obs-required {
  color: var(--error);
}

.obs-input {
  width: 100%;
  padding: 0.875rem 1rem;
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: 8px;
  color: var(--text);
  font-size: 14px;
  transition: all 0.2s ease;
}

.obs-input:focus {
  outline: none;
  border-color: #7c1d2b;
  box-shadow: 0 0 0 3px rgba(124, 29, 43, 0.15);
}

.obs-input.has-error {
  border-color: var(--error);
}

.obs-input::placeholder {
  color: var(--text-muted);
}

.obs-error {
  font-size: 13px;
  color: var(--error);
  margin-top: 0.5rem;
}

.obs-btn {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.875rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.obs-btn-primary {
  background: #7c1d2b;
  color: white;
}

.obs-btn-primary:hover:not(:disabled) {
  background: #5f1722;
  transform: translateY(-1px);
}

.obs-btn-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.obs-spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.obs-auth-footer {
  text-align: center;
  padding-top: 2rem;
  border-top: 1px solid var(--border);
  margin-top: 2rem;
}

.obs-link {
  color: var(--text-muted);
  text-decoration: none;
  font-size: 14px;
  transition: color 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.obs-link:hover {
  color: white;
}

.obs-back-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

@media (max-width: 640px) {
  .obs-auth-page {
    padding: 1rem;
  }
  
  .obs-auth-header {
    padding: 2rem 1.5rem 1.5rem;
  }
  
  .obs-auth-body {
    padding: 1.5rem;
  }
}
</style>