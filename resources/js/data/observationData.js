/**
 * Reference data for Observation Programme Reporting Form
 * SOURCE: CLAUDE.md §3 - these must match the prototype exactly
 * DO NOT paraphrase, reorder, or invent options
 */

export const VENUE_TYPES = [
  "Stadium",
  "Training Ground",
  "Club Headquarters",
  "Academy / Youth Development Centre",
  "Medical / Sports Science Facility",
  "Fan Zone / Stadium Precinct",
  "Community Programme Venue",
  "Supporter Trust / Fan Organisation",
  "Broadcasting / Media Centre",
  "Retail / Merchandising Outlet",
  "Other (please specify)"
];

export const VENUE_GLYPH = {
  "Stadium": "🏟️",
  "Training Ground": "⚽",
  "Club Headquarters": "🏢",
  "Academy / Youth Development Centre": "🎓",
  "Medical / Sports Science Facility": "🏥",
  "Fan Zone / Stadium Precinct": "🎉",
  "Community Programme Venue": "🤝",
  "Supporter Trust / Fan Organisation": "👥",
  "Broadcasting / Media Centre": "📺",
  "Retail / Merchandising Outlet": "🛍️",
  "Other (please specify)": "📍"
};

export const ENTRY_TYPES = [
  { id: "observation", label: "Observation", color: "#4A90E2" },
  { id: "best_practice", label: "Best Practice", color: "#7ED321" },
  { id: "innovation", label: "Innovation", color: "#F5A623" },
  { id: "challenge", label: "Challenge", color: "#D0021B" },
  { id: "recommendation", label: "Recommendation", color: "#BD10E0" }
];

export const FUNCTIONAL_AREAS = [
  { code: "ACS & ACR", name: "Away Club Support & Away Club Relations" },
  { code: "BRO", name: "Broadcasting" },
  { code: "CAT", name: "Catering" },
  { code: "CLO", name: "Club Liaison" },
  { code: "COM", name: "Communications" },
  { code: "CSR", name: "Corporate Social Responsibility" },
  { code: "CUS", name: "Customer Service" },
  { code: "DAT", name: "Data & Analytics" },
  { code: "DIS", name: "Disability Access" },
  { code: "ENV", name: "Environmental Sustainability" },
  { code: "EVE", name: "Event Management & Operations" },
  { code: "FAN", name: "Fan Engagement & Experience" },
  { code: "FIN", name: "Finance & Commercial" },
  { code: "GOV", name: "Governance & Compliance" },
  { code: "HOS", name: "Hospitality" },
  { code: "HR", name: "Human Resources" },
  { code: "IT", name: "Information Technology & Digital" },
  { code: "MAR", name: "Marketing & Brand" },
  { code: "MED", name: "Medical & Sports Science" },
  { code: "RET", name: "Retail & Merchandising" },
  { code: "SAF", name: "Safety & Security" },
  { code: "STA", name: "Stadium & Facilities" },
  { code: "TIC", name: "Ticketing" },
  { code: "Other", name: "Other" }
];

export const APPLICABILITY = [
  { id: "must_have", label: "Must Have", tone: "danger" },
  { id: "good_to_have", label: "Good to Have", tone: "success" },
  { id: "already", label: "Already Implemented in-Venue", tone: "info" },
  { id: "na", label: "Not Applicable", tone: "secondary" },
  { id: "assess", label: "Requires Further Assessment", tone: "warning" }
];

export const MIN_DESCRIPTION = 40;

/**
 * Theme presets with CSS custom properties
 * Default: navy (Stadium Navy)
 */
export const THEMES = {
  navy: {
    name: "Stadium Navy",
    vars: {
      "--primary": "#0A1733",
      "--primary-light": "#1A2F52",
      "--accent": "#C6F24E",
      "--accent-dark": "#A8D13A",
      "--text": "#FFFFFF",
      "--text-muted": "#8B9AB8",
      "--border": "#2A3F5F",
      "--bg-card": "#162844",
      "--bg-input": "#0F1F3A",
      "--error": "#FF6B6B",
      "--success": "#51CF66",
      "--warning": "#FFD43B"
    }
  },
  championship: {
    name: "Championship",
    vars: {
      "--primary": "#1E3A5F",
      "--primary-light": "#2E4A6F",
      "--accent": "#FFD700",
      "--accent-dark": "#E5C100",
      "--text": "#FFFFFF",
      "--text-muted": "#98A8C8",
      "--border": "#3A5A8F",
      "--bg-card": "#263E64",
      "--bg-input": "#1A2F52",
      "--error": "#FF6B6B",
      "--success": "#51CF66",
      "--warning": "#FFD43B"
    }
  },
  slate: {
    name: "Slate",
    vars: {
      "--primary": "#1A202C",
      "--primary-light": "#2D3748",
      "--accent": "#4FD1C5",
      "--accent-dark": "#38B2AC",
      "--text": "#FFFFFF",
      "--text-muted": "#A0AEC0",
      "--border": "#4A5568",
      "--bg-card": "#2D3748",
      "--bg-input": "#1A202C",
      "--error": "#FC8181",
      "--success": "#68D391",
      "--warning": "#F6AD55"
    }
  }
};

/**
 * Font pairings
 * Default: Archivo / Barlow
 */
export const FONT_PAIRS = {
  "archivo-barlow": {
    heading: "'Archivo', sans-serif",
    body: "'Barlow', sans-serif"
  },
  "saira-public": {
    heading: "'Saira', sans-serif",
    body: "'Public Sans', sans-serif"
  },
  "oswald-barlow": {
    heading: "'Oswald', sans-serif",
    body: "'Barlow', sans-serif"
  }
};

/**
 * Density modes
 * Default: comfortable
 */
export const DENSITIES = {
  comfortable: { spacing: "1rem", fontSize: "1rem" },
  compact: { spacing: "0.75rem", fontSize: "0.9rem" },
  spacious: { spacing: "1.25rem", fontSize: "1.1rem" }
};
