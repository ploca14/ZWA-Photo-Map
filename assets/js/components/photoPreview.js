// Photo preview Webcomponent

export default class PhotoPreview extends HTMLImageElement {
  /*
  Create a preview for the uploaded photo
  @param file - the photo to preview
  */
  preview(file) {
    if (file) {
      this.preview(this.getBase64(file));
    } else {
      this.hidePreview();
    }
  }

  /*
  Generates a base64 string from the image
  @param file - the photo to convert
  @returns base64String - the photo converted to base64
  */
  getBase64(file) {
    const reader = new FileReader();

    reader.addEventListener('load', () => {
      // convert image file to base64 string
      const imageSrc = reader.result.toString();

      this.showPreview(imageSrc);

    }, false);

    return reader.readAsDataURL(file);
  }

  /*
  Displays the image preview
  @param src - the photo as a base64 string
  */
  showPreview(src) {
    this.src = src;
    this.style.display = 'block';
  }

  // Hides the image preview
  hidePreview() {
    this.src = '#';
    this.style.display = 'none';
  }
}