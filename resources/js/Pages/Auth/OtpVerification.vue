<script setup>
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import OtpInput from '@/Components/OtpInput.vue'
import Icon from '@/Components/Observation/Icon.vue'
import { THEMES } from '@/data/observationData'

const props = defineProps({
  email: { type: String, required: true },
  length: { type: Number, default: 4 },
})

const otpInput = ref(null)

const form = useForm({ otp: '' })

function submit() {
  if (form.otp.length < props.length) return
  form.post(route('otp.verify'), {
    onError: () => { form.otp = ''; otpInput.value?.focus() },
  })
}

function resend() {
  useForm({}).post(route('otp.resend'), {
    onSuccess: () => { form.otp = ''; otpInput.value?.focus() },
  })
}

// Apply Navy theme
const themeVars = computed(() => {
  return THEMES.navy.vars;
});
</script>

<template>
  <Head title="Two-Step Verification" />
  <div class="obs-auth-page" :style="themeVars">
    <div class="obs-auth-container">
      <div class="obs-auth-card">
        <div class="obs-auth-header">
          <img src="/assets/img/sc_logo_white.png" alt="Logo" class="obs-auth-logo" />
          <!-- <div class="obs-otp-icon-wrapper">
            <Icon name="shield" :size="48" class="obs-auth-icon" />
          </div> -->
          <h1 class="obs-auth-title">Verify Your Email</h1>
          <p class="obs-auth-subtitle">
            Please enter the {{ length }}-digit code sent to<br/>
            <strong class="obs-email">{{ email }}</strong>
          </p>
        </div>

        <div class="obs-auth-body">
          <div v-if="form.errors.otp" class="obs-alert obs-alert-error" role="alert">
            <Icon name="alert" :size="16" />
            {{ form.errors.otp }}
          </div>

          <form @submit.prevent="submit" class="obs-otp-form">
            <OtpInput
              ref="otpInput"
              v-model="form.otp"
              :length="length"
              :has-error="!!form.errors.otp"
              @complete="submit"
            />

            <button
              type="submit"
              class="obs-btn obs-btn-primary"
              :disabled="form.otp.length < length || form.processing"
            >
              <span v-if="form.processing" class="obs-spinner"></span>
              <Icon v-else name="check" :size="18" />
              {{ form.processing ? 'Verifying...' : 'Verify Code' }}
            </button>
          </form>

          <div class="obs-auth-footer obs-auth-footer-centered">
            <div class="obs-resend-text">
              Didn't receive a code?
              <button
                type="button"
                class="obs-link obs-link-primary obs-link-button"
                @click="resend"
              >
                Resend Code
              </button>
            </div>
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

.obs-otp-icon-wrapper {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 80px;
  height: 80px;
  background: rgba(124, 29, 43, 0.1);
  border-radius: 50%;
  margin-bottom: 1.5rem;
}

.obs-auth-icon {
  color: white;
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

.obs-email {
  color: white;
  font-weight: 600;
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

.obs-alert-error {
  background: rgba(255, 107, 107, 0.1);
  border: 1px solid rgba(255, 107, 107, 0.3);
  color: var(--error);
}

.obs-otp-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
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
  padding-top: 2rem;
  border-top: 1px solid var(--border);
  margin-top: 2rem;
}

.obs-auth-footer-centered {
  text-align: center;
}

.obs-resend-text {
  font-size: 14px;
  color: var(--text-muted);
  margin-bottom: 1rem;
}

.obs-link {
  color: var(--text-muted);
  text-decoration: none;
  font-size: 14px;
  transition: color 0.2s ease;
}

.obs-link:hover {
  color: white;
}

.obs-link-primary {
  color: white;
  font-weight: 600;
}

.obs-link-primary:hover {
  color: rgba(255, 255, 255, 0.8);
}

.obs-link-button {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  font-size: inherit;
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
  
  .obs-auth-logo {
    max-width: 140px;
  }
  
  .obs-otp-icon-wrapper {
    width: 64px;
    height: 64px;
  }
  
  .obs-auth-icon {
    width: 40px;
    height: 40px;
  }
}
</style>
