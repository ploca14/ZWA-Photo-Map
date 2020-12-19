

export default class LikeButton extends HTMLElement {
  constructor() {
    super();
    this.classList.add('eva', 'eva-2x', 'eva-heart-outline')
    this.id = this.getAttribute('id');
  }

  connectedCallback() {
    this.addEventListener('click', this._onClick);
  }

  disconnectedCallback() {
    this.removeEventListener('click', this._onClick);
  }

  static get observedAttributes() {
    return ['liked'];
  }

  set liked(value) {
    const isLiked = Boolean(value);
    if (isLiked)
      this.setAttribute('liked', '');
    else
      this.removeAttribute('liked');
  }

  get liked() {
    return this.hasAttribute('liked');
  }

  attributeChangedCallback(name, oldValue, newValue) {
    const hasValue = newValue !== null;
    if (name === 'liked') {
      this.classList.toggle('eva-heart', hasValue);
      this.classList.toggle('eva-heart-outline', !hasValue);
    }
  }

  _onClick() {
    this._sendRequest();
  }

  _sendRequest() {
    fetch(`/post/${this.id}/like`, {
      method: 'POST'
    })
      .then(response => response.json())
      .then(data => {
        this.liked = data;
      })
      .catch((error) => {
        alert('Vyskytla se chyba :(')
      })
  }
}