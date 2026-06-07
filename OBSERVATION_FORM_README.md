# Observation Programme Reporting Form - Implementation Guide

## Overview
A complete Inertia Vue 3 application for the Observation Programme Reporting Form, built according to CLAUDE.md specifications. The application allows authenticated users to submit observation reports with multiple venues and detailed observations.

## File Structure Created

### Data & Configuration
- `resources/js/data/observationData.js` - All reference data (VENUE_TYPES, ENTRY_TYPES, FUNCTIONAL_AREAS, APPLICABILITY, themes)

### Composables
- `resources/js/composables/useObservationValidation.js` - Form validation logic with live validation after first submit

### UI Components
- `resources/js/Components/Observation/Field.vue` - Form field wrapper with label, hint, and error display
- `resources/js/Components/Observation/Icon.vue` - SVG icon component with multiple icons
- `resources/js/Components/Observation/PillCheck.vue` - Pill-shaped checkbox for entry types
- `resources/js/Components/Observation/CheckTile.vue` - Tile-based checkbox for functional areas
- `resources/js/Components/Observation/RadioPills.vue` - Pill-shaped radio buttons for applicability
- `resources/js/Components/Observation/PhotoUploader.vue` - Photo upload component with preview

### Composite Components
- `resources/js/Components/Observation/EntryCard.vue` - Individual observation entry card
- `resources/js/Components/Observation/VenueCard.vue` - Venue card containing multiple entries

### Pages
- `resources/js/Pages/Observation/Form.vue` - Main form page with autosave functionality
- `resources/js/Pages/Observation/Success.vue` - Success confirmation page after submission

### Styles
- `resources/css/observation.css` - Complete theme system with Navy (default), Championship, and Slate themes

### Backend
- `app/Http/Controllers/ObservationController.php` - Controller with create, store, and success methods
- Routes added to `routes/web.php` under `/observation` prefix

## Routes

### Available Routes (authenticated users only):
- `GET /observation/form` - Display the observation form
- `POST /observation/form` - Submit the observation report
- `GET /observation/success?reportId=XXX` - Success confirmation page

## Features Implemented

### Form Features
✅ Reporter information section (name, role, programme)
✅ Multiple venues support (add/delete venues)
✅ Multiple observations per venue (add/delete entries)
✅ Observation type selection (5 types with pill checkboxes)
✅ Rich text description with character count (min 40 characters)
✅ Photo upload with preview and delete
✅ Functional area selection (24 areas in grid layout)
✅ Applicability radio buttons with color-coded tones
✅ Autosave to localStorage every 700ms
✅ Draft restoration on page load
✅ Complete validation with error display
✅ Responsive design (mobile, tablet, desktop)

### Validation Rules (from CLAUDE.md §4)
- Reporter name: required
- Venue type: required
- Venue "Other" text: required if "Other" is selected
- Entry types: at least one must be selected
- Description: required, minimum 40 characters
- Applicability: required
- Functional area "Other": required if "Other" is selected

### Theme System
- Default: Navy (Stadium Navy) - #0A1733, #C6F24E accent
- Alternative themes: Championship, Slate
- Google Fonts: Archivo (headings) + Barlow (body)
- Fully customizable via CSS custom properties

## Usage

### 1. Build Assets
```bash
npm run build
# or for development with hot reload
npm run dev
```

### 2. Access the Form
Navigate to: `http://your-domain/observation/form`

**Note:** User must be authenticated (logged in)

### 3. Fill Out the Form
1. Enter reporter information
2. Select venue type
3. Add observations for each venue
4. Select observation types (pills)
5. Write detailed description (min 40 chars)
6. Optionally add photos
7. Select functional areas impacted
8. Choose applicability
9. Add more observations or venues as needed
10. Submit the form

### 4. Autosave
- Form automatically saves to localStorage every 700ms
- Draft is restored when returning to the form
- "Saved HH:MM" indicator appears in the header
- Photos are NOT saved in draft (as per CLAUDE.md)

### 5. Submission
- Form validates on submit attempt
- Shows errors for invalid fields
- Scrolls to first error automatically
- On success, redirects to success page with reference ID
- Clears draft after successful submission

## Data Structure

### Form Data Shape
```javascript
{
  reporter: {
    name: string,      // required
    role: string,      // optional
    programme: string  // optional
  },
  venues: [
    {
      id: string,
      type: string,      // required, one of VENUE_TYPES
      otherText: string, // required if type is "Other"
      entries: [
        {
          id: string,
          types: {         // at least one must be true
            observation: boolean,
            best_practice: boolean,
            innovation: boolean,
            challenge: boolean,
            recommendation: boolean
          },
          description: string,  // required, min 40 chars
          photos: File[],       // optional, images
          areas: {              // optional
            "ACS & ACR": boolean,
            "BRO": boolean,
            // ... 24 functional areas
          },
          areaOther: string,    // required if areas.Other is true
          applicability: string // required, one of: must_have, good_to_have, already, na, assess
        }
      ]
    }
  ]
}
```

## Next Steps (Database Implementation)

To complete the application with database persistence:

1. **Create Migrations** (from CLAUDE.md §2):
   - `reports` table (reporter info, status, timestamps)
   - `venues` table (report_id, type, other_text, position)
   - `entries` table (venue_id, description, area_other, applicability, position)
   - `entry_types` pivot/JSON
   - `entry_functional_areas` pivot/JSON
   - `photos` table (entry_id, path, original_name)

2. **Create Eloquent Models**:
   - `App\Models\Report`
   - `App\Models\Venue`
   - `App\Models\Entry`
   - `App\Models\Photo`

3. **Update Controller**:
   - Implement `store()` method to save to database
   - Handle photo uploads to storage
   - Add server-side draft saving endpoint

4. **Add Admin View** (optional):
   - List all submitted reports
   - Filter and search functionality
   - Detailed report view
   - Based on `Observation Admin.html` from prototype

## Testing Checklist

- [ ] Form loads correctly at `/observation/form`
- [ ] All fields are visible and styled correctly
- [ ] Can add/delete venues
- [ ] Can add/delete entries within venues
- [ ] Validation shows errors on submit
- [ ] Autosave works (check localStorage)
- [ ] Draft restores on page reload
- [ ] Photos can be uploaded and previewed
- [ ] Photos can be removed
- [ ] Form submits successfully
- [ ] Success page displays with reference ID
- [ ] Draft is cleared after submission
- [ ] Responsive design works on mobile
- [ ] All theme colors match Navy theme
- [ ] Icons render correctly

## Customization

### Change Theme
Edit `resources/js/Pages/Observation/Form.vue`:
```javascript
const themeVars = computed(() => {
  const theme = THEMES.championship; // or THEMES.slate
  return theme.vars;
});
```

### Modify Validation Rules
Edit `resources/js/composables/useObservationValidation.js`

### Add More Icons
Edit `resources/js/Components/Observation/Icon.vue`

### Adjust Autosave Delay
Edit `resources/js/Pages/Observation/Form.vue`:
```javascript
autosaveTimeout = setTimeout(() => {
  autosaveDraft();
}, 1000); // Change from 700 to 1000ms
```

## Support

For questions or issues, refer to:
- CLAUDE.md for detailed specifications
- Prototype: `Observation Form (standalone).html`
- Laravel Inertia docs: https://inertiajs.com
- Vue 3 docs: https://vuejs.org

## License

Same as the parent Laravel application.
