import TomSelect from 'tom-select';
import TomSelect_caret_position
  from 'tom-select/dist/js/plugins/caret_position';
import TomSelect_input_autogrow
  from 'tom-select/dist/js/plugins/input_autogrow';

TomSelect.define('caret_position', TomSelect_caret_position);
TomSelect.define('input_autogrow', TomSelect_input_autogrow);

export default class TagsSelect {

  constructor ($el) {
    if (!$el.dataset.tagType) {
      return;
    }

    this.$el = $el;
    this.type = this.$el.dataset.tagType;
    this.select = null;

    this.$suggestions = $el.parentElement.querySelector('.tag-suggestions');
    this.$suggestionsContent = $el.parentElement.querySelector('.tag-suggestions-content');

    this.config = {
      plugins: ['caret_position', 'input_autogrow'],
      delimiter: ',',
      persist: false,
      create: this.selectAllowsCreation(),
      maxOptions: null,
      onItemAdd:function(){
        this.setTextboxValue('');
        this.refreshOptions();
      },
      load: (query, callback) => {
        this.handleTagLoading(query, callback);
      }
    };

    this.select = new TomSelect(this.$el, this.config);
  }

  selectAllowsCreation () {
    return typeof this.$el.dataset.allowCreation !== 'undefined';
  }

  handleTagLoading (query, callback) {
    if (!query.length) return callback();

    fetch(this.getFetchUrl(), {
      method: 'POST',
      credentials: 'same-origin',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        _token: window.appData.user.token,
        query: query
      })
    }).then((response) => {
      return response.json();
    }).then((results) => {
      callback(results);
    }).catch(() => {
      callback();
    });
  }

  getFetchUrl () {
    return this.type === 'tags'
      ? window.appData.routes.fetch.searchTags
      : window.appData.routes.fetch.searchLists;
  }

  displayNewSuggestions (tags) {
    if (typeof tags !== 'object' || tags.length === 0) {
      return;
    }

    this.$suggestionsContent.innerHTML = '';

    tags.forEach(newTag => {
      newTag = newTag.trim();

      const $tag = document.createElement('span');
      $tag.classList.add('btn', 'btn-outline-secondary', 'btn-xs');
      $tag.innerText = newTag;

      $tag.onclick = this.onSuggestionClick.bind(this, $tag);

      this.$suggestionsContent.appendChild($tag);
    });

    this.$suggestions.classList.remove('d-none');
  }

  onSuggestionClick ($tag) {
    const value = $tag.innerText;

    this.select.addOption({value: value, text: value});
    this.select.addItem(value);

    $tag.onclick = null;
    $tag.classList.remove('cursor-pointer');
    $tag.classList.add('text-success');
  }
}
