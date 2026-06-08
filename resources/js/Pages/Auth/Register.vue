<script setup>
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import Icon from '@/Components/Observation/Icon.vue'
import { THEMES } from '@/data/observationData'

const showPassword = ref(false)
const showPasswordConfirm = ref(false)

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

function submit() {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}

const themeVars = computed(() => {
  return THEMES.navy.vars
})
</script>
<template>
  <Head title="Register" />
  <div class="obs-auth-page" :style="themeVars">
    <div class="obs-auth-container">
      <div class="obs-auth-card">
        <div class="obs-auth-header">
          <img src="/assets/img/sc_logo_white.png" alt="Logo" class="obs-auth-logo" />
          <h1 class="obs-auth-title">Create Account</h1>
          <p class="obs-auth-subtitle">Sign up to get started with Observation Programme</p>
        </div>

        <div class="obs-auth-body">
          <form @submit.prevent="submit" class="obs-form">
            <div class="obs-field">
              <label class="obs-label" for="name">
                Name
                <span class="obs-required">*</span>
              </label>
              <input
                class="obs-input"
                :class="{ 'has-error': form.errors.name }"
                id="name"
                type="text"
                placeholder="Enter your name"
                v-model="form.name"
                required
              />
              <div class="obs-error" v-if="form.errors.name">
                {{ form.errors.name }}
              </div>
            </div>

            <div class="obs-field">
              <label class="obs-label" for="email">
                Email
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
              />
              <div class="obs-error" v-if="form.errors.email">
                {{ form.errors.email }}
              </div>
            </div>

            <div class="obs-field">
              <label class="obs-label" for="password">
                Password
                <span class="obs-required">*</span>
              </label>
              <div class="obs-password-wrapper">
                <input
                  class="obs-input"
                  :class="{ 'has-error': form.errors.password }"
                  id="password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Enter your password"
                  v-model="form.password"
                  required
                />
                <button
                  type="button"
                  class="obs-password-toggle"
                  @click="showPassword = !showPassword"
                  :title="showPassword ? 'Hide password' : 'Show password'"
                >
                  <Icon :name="showPassword ? 'eye-off' : 'eye'" :size="18" />
                </button>
              </div>
              <div class="obs-error" v-if="form.errors.password">
                {{ form.errors.password }}
              </div>
            </div>

            <div class="obs-field">
              <label class="obs-label" for="password_confirmation">
                Confirm Password
                <span class="obs-required">*</span>
              </label>
              <div class="obs-password-wrapper">
                <input
                  class="obs-input"
                  :class="{ 'has-error': form.errors.password_confirmation }"
                  id="password_confirmation"
                  :type="showPasswordConfirm ? 'text' : 'password'"
                  placeholder="Confirm your password"
                  v-model="form.password_confirmation"
                  required
                />
                <button
                  type="button"
                  class="obs-password-toggle"
                  @click="showPasswordConfirm = !showPasswordConfirm"
                  :title="showPasswordConfirm ? 'Hide password' : 'Show password'"
                >
                  <Icon :name="showPasswordConfirm ? 'eye-off' : 'eye'" :size="18" />
                </button>
              </div>
              <div class="obs-error" v-if="form.errors.password_confirmation">
                {{ form.errors.password_confirmation }}
              </div>
            </div>

            <button
              type="submit"
              class="obs-btn obs-btn-primary"
              :disabled="form.processing"
            >
              <Icon v-if="!form.processing" name="user-plus" :size="18" />
              <span v-if="form.processing" class="obs-spinner"></span>
              {{ form.processing ? 'Creating Account...' : 'Sign Up' }}
            </button>
          </form>

          <div class="obs-auth-footer">
            Already have an account?
            <Link class="obs-link obs-link-primary" :href="route('mylogin')">
              Sign in
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
  margin: 0 0 0.5rem;
  letter-spacing: -0.02em;
}

.obs-auth-subtitle {
  font-size: 14px;
  color: var(--text-muted);
  margin: 0;
}

.obs-auth-body {
  padding: 2rem;
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

.obs-password-wrapper {
  position: relative;
}

.obs-password-toggle {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: var(--text-muted);
  cursor: pointer;
  padding: 0.25rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s ease;
}

.obs-password-toggle:hover {
  color: var(--text);
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
  font-size: 14px;
  color: var(--text-muted);
}

.obs-link {
  text-decoration: none;
  transition: color 0.2s ease;
}

.obs-link-primary {
  color: white;
  font-weight: 600;
}

.obs-link-primary:hover {
  color: rgba(255, 255, 255, 0.8);
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
