// Form validator class

export default class FormValidator {
  /*
  FormValidator constructor
  @param form - The form to validate
  @param validations  - An object with the field name to validate as a key and a validator function
                      - as a value.
  */
  constructor(form, validations) {
    this.form = form;
    this.validations = validations;
    this.errors = [];
  }

  init() {
    this.createEventListeners();
  }

  // Adds an onsubmit event listeners to the form
  createEventListeners() {
    this.form.addEventListener('submit', (event) => {
      // Clear any previous errors before validating
      this.validateForm();

      // If there is at least on error
      if (this.errors.length) {
        // Go through all the errors
        this.errors.forEach(({ field, error }) => {
          // Get the invalid element
          const element = this.form[field];
          this.createError(element, error)
        })
        event.preventDefault();
      }
    })
  }

  // Validates the entire form
  validateForm() {
    this.clearErrors();

    // For each validation in the validations object
    for (const [field, validator] of Object.entries(this.validations)) {
      // Check if the field is valid
      const error = validator(this.form[field]);

      if (error) {
        this.errors.push({field, error});
      }
    }
  }

  // Clears all the errors
  clearErrors() {
    this.errors = [];
    this.form.querySelectorAll('.error')
      .forEach(element => element.remove());
  }

  /*
  Creates an error and appends it to the field element
  @param element - The invalid field element
  @param error - The error message to display
  */
  createError(element, error) {
    const errorElement = document.createElement('p');
    errorElement.classList.add('error');
    errorElement.innerText = error;

    element.classList.add('invalid');
    element.after(errorElement);
  }
}