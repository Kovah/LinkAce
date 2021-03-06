export default class TagsSelect {

  constructor ($el) {
    if (!$el.dataset.tagType) {
      return;
    }

    this.$el = $el;
    this.type = this.$el.dataset.tagType;
    this.selectize = null;

    this.$suggestions = $el.parentElement.querySelector('.tag-suggestions');
    this.$suggestionsContent = $el.parentElement.querySelector('.tag-suggestions-content');

    this.config = {
      delimiter: ',',
      persist: false,
      load: (query, callback) => {
        this.handleTagLoading(query, callback);
      },
      create: function (input) {
        return {
          value: input.replace(',', ''),
          text: input.replace(',', '')
        };
      }
    };

    this.init();
  }

  init () {
    this.prepareConfig();

    const $select = $(this.$el).selectize(this.config);
    this.selectize = $select[0].selectize;
  }

  prepareConfig () {
    if (this.selectAllowsCreation()) {
      this.config.create = function (input) {
        return {
          value: input,
          text: input
        };
      };
    }
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
      $tag.classList.add('badge', 'badge-light', 'badge-pill', 'cursor-pointer', 'badge-wrap');
      $tag.innerText = newTag;

      $tag.onclick = this.onSuggestionClick.bind(this, $tag);

      this.$suggestionsContent.appendChild($tag);
    });

    this.$suggestions.classList.remove('d-none');
  }

  onSuggestionClick ($tag) {
    const value = $tag.innerText;

    this.selectize.addOption({value: value, text: value});
    this.selectize.refreshOptions();
    this.selectize.addItem(value);

    $tag.onclick = null;
    $tag.classList.remove('cursor-pointer');
    $tag.classList.add('text-success');
  }
}
