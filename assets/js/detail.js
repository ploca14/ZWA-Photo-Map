// Post detail page

const form = document.forms['delete'];

// Ask for confirmation when deleting
form.addEventListener('submit', (event) => {
  if (!confirm('Opravdu chcete tento příspěvek smazat?')) {
    event.preventDefault()
  }
});