import { debounce } from '../lib/helper';
import { getInstance } from '../lib/views';
import TagsSelect from './TagsSelect';

export default class UrlField {

  constructor ($el) {
    this.$field = $el;

    this.$linkExistsWarning = document.querySelector('.link-exists');

    if (!this.$linkExistsWarning) {
      return;
    }

    this.$linkExistsLink = this.$linkExistsWarning.querySelector('a');

    const $tags = document.querySelector('#tags');
    this.tagSuggestions = $tags ? getInstance($tags, TagsSelect) : null;

    this.$field.addEventListener('keyup', this.onKeyup.bind(this));
  }

  onKeyup () {
    // Debounce the keyup function to wait 500ms until the last input was typed
    debounce(() => {
      const value = this.$field.value;

      // Check for existing links if the value is longer than http://
      if (value.length > 6) {
        this.checkForExistingUrl(value);
      } else {
        this.resetField();
      }
    });
  }

  checkForExistingUrl (url) {
    const checkUrl = window.appData.routes.fetch.existingLinks;

    fetch(checkUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        _token: window.appData.user.token,
        ignore_id: this.$field.dataset.ignoreId ?? null,
        query: url
      })
    }).then((response) => {
      return response.json();
    }).then((result) => {

      // If the link already exist, mark the field as invalid
      if (result.linkFound === true) {
        this.$field.classList.add('is-invalid');
        this.$linkExistsLink.href = result.editLink;
        this.$linkExistsWarning.classList.remove('d-none');
      } else {
        this.$field.classList.remove('is-invalid');
        this.$linkExistsWarning.classList.add('d-none');
        this.$linkExistsLink.href = '';
        this.querySiteForMetaTags(url);
      }

    });
  }

  resetField () {
    this.$field.classList.remove('is-invalid');
  }

  querySiteForMetaTags (url) {
    if (this.tagSuggestions === null) {
      // Abort if tag suggestions are not available
      return;
    }

    fetch(window.appData.routes.fetch.keywordsForUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        _token: window.appData.user.token,
        url: url
      })
    })
      .then(response => response.json())
      .then(data => {
        if (data.keywords !== null) {
          this.tagSuggestions.displayNewSuggestions(data.keywords);
        }
      });
  }
}
