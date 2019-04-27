import { debounce } from '../lib/helper';

export default class UrlField {

  constructor ($el) {
    this.$field = $el;

    this.$field.addEventListener('keyup', this.onKeyup.bind(this));
  }

  onKeyup () {
    // Debounce the keyup function to wait 500ms until the last input was typed
    debounce(() => {
      const value = this.$field.value;

      // Check for existing links if the value is longer than http://
      if (value.length > 6) {
        this.checkforExistingUrl(value);
      } else {
        this.resetField();
      }
    });
  }

  checkforExistingUrl (url) {
    const checkUrl = window.appData.routes.ajax.existingLinks;

    fetch(checkUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        _token: window.appData.user.token,
        query: url
      })
    }).then((response) => {
      return response.json();
    }).then((result) => {

      // If the link already exist, mark the field as invalid
      if (result.linkFound === true) {
        this.$field.classList.add('is-invalid');
      } else {
        this.$field.classList.remove('is-invalid');
      }

    });
  }

  resetField () {
    this.$field.classList.remove('is-invalid');
  }
}
