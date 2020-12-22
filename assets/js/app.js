// Main JS file - entry pint for Webcomponents used throughout the app.

import LikeButton from "./components/likeButton";
import PostMap from "./components/postMap";

// Define Webcomponents
window.customElements.define('like-button', LikeButton);
window.customElements.define('post-map', PostMap);