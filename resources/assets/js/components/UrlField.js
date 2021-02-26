import { debounce } from '../lib/helper';
import { getInstance } from '../lib/views';
import TagsSelect from './TagsSelect';

export default class UrlField {

  constructor ($el) {
    this.$field = $el;

    this.$field.addEventListener('keyup', this.onKeyup.bind(this));

    const $tags = document.querySelector('#tags');
    this.tagSuggestions = $tags ? getInstance($tags, TagsSelect) : null;
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
    const checkUrl = window.appData.routes.fetch.existingLinks;

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

    fetch(window.appData.routes.fetch.htmlForUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        _token: window.appData.user.token,
        url: url
      })
    })
      .then(response => response.text())
      .then(body => {
        if (body !== null) {
          this.parseHtmlForKeywords(body)
        }
      });
  }

  parseHtmlForKeywords (body) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(body, 'text/html');

    if (doc.head.children.length > 0) {
      const keywords = doc.head.children.namedItem('keywords');

      if (keywords !== null && keywords.content.length > 0) {
        this.tagSuggestions.displayNewSuggestions(keywords.content.split(','));
      }
    }
  }
}
