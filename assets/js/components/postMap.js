// Post map Webcomponent

const MAPOPTIONS = {
  mapTypeControl: false,
  fullscreenControl: false,
  streetViewControl: false,
  zoomControlOptions: {
    position: google.maps.ControlPosition.LEFTTOP,
  },
}

// Create template
const template = document.createElement('div');
template.id = 'map';

export default class PostMap extends HTMLElement {
  // Create custom element
  constructor() {
    super();
    this.appendChild(template.cloneNode(true));
  }

  // Once element is created
  connectedCallback() {
    this.getPosts();
    this.createMap();
  }

  // Parses posts from attribute then removes the attribute to clean up the DOM
  getPosts() {
    this.posts = JSON.parse(this.getAttribute('posts'));
    this.removeAttribute('posts');
  }

  // Initialize the Google Maps map
  createMap() {
    this.map = new google.maps.Map(this.querySelector('#map'), MAPOPTIONS);

    this.bounds = new google.maps.LatLngBounds();

    this.createMarkers();

    this.map.fitBounds(this.bounds);
  }

  // Create markers for all posts
  createMarkers() {
    this.posts.forEach(post => {
      const marker = this.createMarker(post);
      this.bounds.extend(marker.position);
    })
  }

  /*
  Create marker for post
  @param post - object representing the post
  @returns marker - Google Maps Marker object
  */
  createMarker(post) {
    const marker = new google.maps.Marker({
      title: post.title,
      position: this.createLatLong(post.latitude, post.longitude),
      map: this.map
    });

    // Create info window for post
    const infoWindow = this.createInfoWindowForMarker(marker, post);

    // Add event listener to open the info window on click
    marker.addListener("click", () => {
      infoWindow.open(this.map, marker);
    });

    // Add hover listener to the post list item
    this.addHoverListener(post.id, marker);

    return marker;
  }

  /*
  Create Google Maps LatLng object
  @param latitude
  @param longitude
  @returns LatLng - Google Maps LatLng object
  */
  createLatLong(latitude, longitude) {
    return new google.maps.LatLng(latitude, longitude);
  }

  /*
  Create Google Maps InfoWindow object
  @param marker - info windows marker object
  @param post - object representing the post
  @returns LatLng - Google Maps LatLng object
  */
  createInfoWindowForMarker(marker, post) {
    return new google.maps.InfoWindow({
      content: this.createInfoWindowContent(post)
    })
  }

  /*
  Generate the info windows content
  @param post - object representing the post
  @returns {string} content - the HTML content of the info window as a string
  */
  createInfoWindowContent({ title, link, description, photo }) {
    return `
      <figure class="info-window">
        <img class="info-window-image" src="${photo}" alt="${title}">
        <figcaption class="info-window-content">
          <a class="info-window-content-title" href="${link}">${title}</a>
          <p class="info-window-content-description">${description}</p>
        </figcaption>
      </figure>
    `
  }

  /*
  Adds hover listener to the post list item
  @param postId - the post id
  @param marker - the marker associated with the post
  */
  addHoverListener(postId, marker) {
    // Find the post list item element by id
    const postElement = document.getElementById(`post-${postId}`);

    // If there is one
    if (postElement) {
      // Add a hover listener
      postElement.addEventListener('mouseenter', () => {
        marker.setAnimation(google.maps.Animation.BOUNCE); // Marker bounce on hover
      });
      postElement.addEventListener('mouseleave', () => {
        marker.setAnimation(null); // Marker stop bouncing on leave
      })
    }
  }
}