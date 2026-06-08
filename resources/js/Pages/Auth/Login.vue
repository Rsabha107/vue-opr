<script setup>
console.log("LOGIN FILE LOADED");
import { onMounted, onUnmounted, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import AuthFooter from "@/Components/AuthFooter.vue";
import PasswordInput from "@/Components/PasswordInput.vue";
import Icon from "@/Components/Observation/Icon.vue";
import { THEMES } from "@/data/observationData";

// onMounted(() => {
//   console.log("Login Page Mounted");
//   document.body.classList.add("login-bg");
// });

// onUnmounted(() => {
//   document.body.classList.remove("login-bg");
// });

const validated = ref(false);

const props = defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
  remember: {
    type: Boolean,
  },
});

// local copy
const canReset = ref(props.canResetPassword);
const remember = ref(props.remember);

// now you can change it
canReset.value = true;
remember.value = false;

const showPassword = ref(false);

const form = useForm({
  email: "",
  password: "",
  remember: props.remember || false,
});

function submit(e) {
  const htmlForm = e.currentTarget;

  if (!htmlForm.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
    validated.value = true;
    return;
  }

  validated.value = true;

  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
}

// give me the current year
const currentYear = new Date().getFullYear();

// Apply Navy theme
const themeVars = computed(() => {
  return THEMES.navy.vars;
});
</script>
<template>
  <div class="obs-auth-page" :style="themeVars">
    <div class="obs-auth-container">
      <div class="obs-auth-card">
        <div class="obs-auth-header">
          <img src="/assets/img/sc_logo_white.png" alt="Logo" class="obs-auth-logo" />
          <!-- <Icon name="shield" :size="48" class="obs-auth-icon" /> -->
          <h1 class="obs-auth-title">Welcome Back</h1>
          <p class="obs-auth-subtitle">Sign in to continue to Observation Programme</p>
        </div>
        
        <div class="obs-auth-body">
          <div class="obs-auth-form">
            <form
              @submit.prevent="submit"
              class="obs-form"
              :class="{ 'was-validated': validated }"
              novalidate
            >
              <div v-if="status" class="obs-alert obs-alert-success" role="alert">
                <Icon name="check" :size="16" />
                {{ status }}
              </div>

              <div v-if="$page.props.flash?.error" class="obs-alert obs-alert-error" role="alert">
                <Icon name="alert" :size="16" />
                {{ $page.props.flash.error }}
              </div>

              <div v-if="form.errors.email" class="obs-alert obs-alert-error" role="alert">
                <Icon name="alert" :size="16" />
                {{ form.errors.email }}
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
                  placeholder="Enter your email"
                  type="email"
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

              <div class="obs-checkbox" v-if="remember">
                <input
                  class="obs-checkbox-input"
                  id="remember-check"
                  type="checkbox"
                  v-model="form.remember"
                />
                <label class="obs-checkbox-label" for="remember-check">
                  Remember me
                </label>
              </div>
              
              <button
                type="submit"
                class="obs-btn obs-btn-primary"
                :disabled="form.processing"
              >
                <Icon v-if="!form.processing" name="check" :size="18" />
                <span v-if="form.processing" class="obs-spinner"></span>
                {{ form.processing ? "Signing in..." : "Sign In" }}
              </button>
              <div class="obs-divider">
                <span>Or continue with</span>
              </div>

              <a
                class="obs-btn obs-btn-microsoft"
                href="/auth/microsoft/redirect"
              >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M0 0h11.377v11.372H0zm12.623 0H24v11.372H12.623zM0 12.623h11.377V24H0zm12.623 0H24V24H12.623z"/>
                </svg>
                Sign in with Microsoft
              </a>

            </form>

            <div class="obs-auth-links">
              <Link
                v-if="canReset"
                class="obs-link"
                :href="route('myforgotpassword')"
              >
                Forgot your password?
              </Link>
            </div>

            <div class="obs-auth-footer">
              Don't have an account?
              <Link class="obs-link obs-link-primary" :href="route('myregister')">
                Sign up now
              </Link>
            </div>
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

.obs-auth-icon {
  color: white;
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

.obs-field {
  margin-bottom: 1.5rem;
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

.obs-checkbox {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.obs-checkbox-input {
  width: 18px;
  height: 18px;
  border: 2px solid var(--border);
  border-radius: 4px;
  cursor: pointer;
}

.obs-checkbox-input:checked {
  background: #7c1d2b;
  border-color: #7c1d2b;
}

.obs-checkbox-label {
  font-size: 14px;
  color: var(--text);
  cursor: pointer;
  user-select: none;
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

.obs-btn-microsoft {
  background: transparent;
  color: var(--text);
  border: 1px solid var(--border);
  text-decoration: none;
}

.obs-btn-microsoft:hover {
  border-color: #7c1d2b;
  background: rgba(124, 29, 43, 0.05);
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

.obs-divider {
  display: flex;
  align-items: center;
  text-align: center;
  margin: 1.5rem 0;
  color: var(--text-muted);
  font-size: 13px;
}

.obs-divider::before,
.obs-divider::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid var(--border);
}

.obs-divider span {
  padding: 0 1rem;
}

.obs-auth-links {
  text-align: center;
  margin-top: 1.5rem;
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

.obs-auth-footer {
  text-align: center;
  padding-top: 2rem;
  border-top: 1px solid var(--border);
  margin-top: 2rem;
  font-size: 14px;
  color: var(--text-muted);
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
}
</style>