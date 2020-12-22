// Login page

import FormValidator from './components/formValidator';

const form = document.forms['login'];
/*
Validations object
Each key represents the fields element id
Each value represents the validation function
*/
const validations = {
  username: validateUsername,
  password: validatePassword,
}

/*
username validator function
@param field - The field to validate
@returns {string} error - if the field is invalid return an error message as a string
*/
function validateUsername({value}) {
  if (!value) return 'Tato hodnota nesmí být prázdná.';
}

/*
Password validator function
@param field - The field to validate
@returns {string} error - if the field is invalid return an error message as a string
*/
function validatePassword({value}) {
  if (!value) return 'Tato hodnota nesmí být prázdná.';
  if (value.length < 6) return  'Heslo nesmí být kratší než 6';
}

const formValidator = new FormValidator(form, validations);

// Initialize validations
formValidator.init();