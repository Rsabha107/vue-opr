/**
 * Validation logic for Observation Form
 * SOURCE: CLAUDE.md §4
 * Validates on submit, then live-validates after first submit attempt
 */

import { ref, computed } from 'vue';
import { MIN_DESCRIPTION, VENUE_TYPES } from '../data/observationData';

export function useObservationValidation() {
  const showErrors = ref(false);
  const errors = ref({
    reporter: {},
    venues: []
  });

  /**
   * Validate reporter section
   */
  function validateReporter(reporter) {
    const reporterErrors = {};
    
    if (!reporter.name || !reporter.name.trim()) {
      reporterErrors.name = 'Reporter name is required';
    }
    
    return reporterErrors;
  }

  /**
   * Validate a single venue
   */
  function validateVenue(venue) {
    const venueErrors = {
      type: null,
      otherText: null,
      entries: []
    };
    
    // Venue type is required
    if (!venue.type) {
      venueErrors.type = 'Venue type is required';
    }
    
    // If "Other" is selected, otherText is required
    if (venue.type === 'Other (please specify)' && !venue.otherText?.trim()) {
      venueErrors.otherText = 'Please specify the venue type';
    }
    
    // Validate each entry
    venue.entries.forEach((entry, index) => {
      venueErrors.entries[index] = validateEntry(entry);
    });
    
    return venueErrors;
  }

  /**
   * Validate a single entry (observation)
   */
  function validateEntry(entry) {
    const entryErrors = {
      types: null,
      description: null,
      applicability: null,
      areaOther: null
    };
    
    // At least one type must be selected
    const hasSelectedType = Object.values(entry.types || {}).some(v => v === true);
    if (!hasSelectedType) {
      entryErrors.types = 'Please select at least one observation type';
    }
    
    // Description is required and must be at least MIN_DESCRIPTION chars
    if (!entry.description || !entry.description.trim()) {
      entryErrors.description = 'Description is required';
    } else if (entry.description.trim().length < MIN_DESCRIPTION) {
      entryErrors.description = `At least ${MIN_DESCRIPTION} characters required (currently ${entry.description.trim().length})`;
    }
    
    // Applicability is required
    if (!entry.applicability) {
      entryErrors.applicability = 'Please select applicability';
    }
    
    // If "Other" functional area is selected, areaOther is required
    if (entry.areas?.Other && !entry.areaOther?.trim()) {
      entryErrors.areaOther = 'Please specify the functional area';
    }
    
    return entryErrors;
  }

  /**
   * Validate entire form
   */
  function validateForm(formData) {
    const newErrors = {
      reporter: validateReporter(formData.reporter),
      venues: []
    };
    
    // Validate each venue
    formData.venues.forEach((venue, index) => {
      newErrors.venues[index] = validateVenue(venue);
    });
    
    errors.value = newErrors;
    return isFormValid(newErrors);
  }

  /**
   * Check if the entire form is valid
   */
  function isFormValid(errorsObj) {
    // Check reporter errors
    if (Object.keys(errorsObj.reporter).length > 0) {
      return false;
    }
    
    // Check venue errors
    for (const venueErrors of errorsObj.venues) {
      if (venueErrors.type || venueErrors.otherText) {
        return false;
      }
      
      // Check entry errors
      for (const entryErrors of venueErrors.entries) {
        if (Object.values(entryErrors).some(e => e !== null)) {
          return false;
        }
      }
    }
    
    return true;
  }

  /**
   * Get error for a specific field
   */
  function getError(path) {
    const parts = path.split('.');
    let current = errors.value;
    
    for (const part of parts) {
      if (current === undefined || current === null) return null;
      current = current[part];
    }
    
    return current || null;
  }

  /**
   * Clear all errors
   */
  function clearErrors() {
    errors.value = {
      reporter: {},
      venues: []
    };
    showErrors.value = false;
  }

  return {
    errors,
    showErrors,
    validateForm,
    validateReporter,
    validateVenue,
    validateEntry,
    getError,
    clearErrors,
    isFormValid
  };
}
