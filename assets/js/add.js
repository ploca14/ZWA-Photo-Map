// Add post page

import FormValidator from './components/formValidator';
import PhotoPreview from './components/photoPreview';

const form = document.forms['post'];
/*
Validations object
Each key represents the fields element id
Each value represents the validation function
*/
const validations = {
  post_title: validateTitle,
  post_photo: validatePhoto,
  post_latitude: validateLatitude,
  post_longitude: validateLongitude,
}
const FILE_SIZE_LIMIT = 2 * 1024 * 1024; // 2MB


/*
Title validator function
@param field - The field to validate
@returns {string} error - if the field is invalid return an error message as a string
*/
function validateTitle({value}) {
  if (!value) return 'Tato hodnota nesmí být prázdná.';
}

/*
Photo validator function
@param field - The field to validate
@returns {string} error - if the field is invalid return an error message as a string
*/
function validatePhoto(field) {
  if (!field.value) return 'Tato hodnota nesmí být prázdná.';
  if (field.files[0].size > FILE_SIZE_LIMIT) {
   return 'Soubor je příliš velký. Maximální velikost souboru je 2 MB.'
  }
}

/*
Latitude validator function
@param field - The field to validate
@returns {string} error - if the field is invalid return an error message as a string
*/
function validateLatitude({value}) {
  if (!value) return 'Tato hodnota nesmí být prázdná.';

  const floatValue = parseFloat(value),
        min = -90,
        max = 90;

  if (floatValue < min || floatValue > max) {
    return `Zeměpisná šířka musí být v rozsahu od ${min} do ${max}.`
  }

  if(isNaN(floatValue)) {
    return 'Tato hodnota není platná.'
  }
}

/*
Longitude validator function
@param field - The field to validate
@returns {string} error - if the field is invalid return an error message as a string
*/
function validateLongitude({value}) {
  if (!value) return 'Tato hodnota nesmí být prázdná.';

  const floatValue = parseFloat(value),
    min = -180,
    max = 180;

  if (floatValue < min || floatValue > max) {
    return `Zeměpisná délka musí být v rozsahu od ${min} do ${max}`
  }

  if(isNaN(floatValue)) {
    return 'Tato hodnota není platná.'
  }
}

const formValidator = new FormValidator(form, validations);

// Initialize validations
formValidator.init();

// Define Webcomponent
window.customElements.define('photo-preview', PhotoPreview, {extends: 'img'});

// Display the image preview once uploaded
form['post_photo'].addEventListener('change', (event) => {
  const photoPreview = document.getElementById('photo-preview');
  photoPreview.preview(event.target.files[0]);
});