// Like button Webcomponent

export default class LikeButton extends HTMLElement {
  // Create custom element
  constructor() {
    super();
    this.id = this.getAttribute('id');
  }

  // Add event listener once element is created
  connectedCallback() {
    this.addEventListener('click', this.onClick);
  }

  // Remove event listener once element is destroyed
  disconnectedCallback() {
    this.removeEventListener('click', this.onClick);
  }

  // Specify which attributes to observe
  static get observedAttributes() {
    return ['liked'];
  }

  /*
  Sets a new value to the liked attribute
  @param value - the value to set
  */
  set liked(value) {
    const isLiked = Boolean(value);
    if (isLiked)
      this.setAttribute('liked', '');
    else
      this.removeAttribute('liked');
  }

  // gets the current value of the liked attribute
  get liked() {
    return this.hasAttribute('liked');
  }

  onClick() {
    this.sendRequest();
  }

  // Sends an AJAX request to the server and sets the like attribute accordingly
  sendRequest() {
    fetch(`/post/${this.id}/like`, {
      method: 'POST'
    })
      .then(response => response.json())
      .then(data => {
        this.liked = data;
        this.classList.toggle('animate', this.liked); // Start the CSS like animation
      })
      .catch((error) => {
        alert('Vyskytla se chyba :(');
      })
  }
}