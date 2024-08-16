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
    const selectObject = this;

    this.$suggestions = $el.parentElement.querySelector('.tag-suggestions');
    this.$suggestionsContent = $el.parentElement.querySelector('.tag-suggestions-content');

    this.config = {
      plugins: ['caret_position', 'input_autogrow'],
      delimiter: ',',
      persist: false,
      create: this.selectAllowsCreation(),
      valueField: 'id',
      labelField: 'name',
      searchField: 'name',
      maxOptions: 100000,
      onItemAdd: function () {
        this.setTextboxValue('');
        this.refreshOptions();
      },
      render: {
        option: function (item, escape) {
          return selectObject.renderItem(item, escape);
        },
        item: function (item, escape) {
          return selectObject.renderItem(item, escape);
        }
      }
    };

    if (this.$el.dataset.tagData) {
      this.config['options'] = JSON.parse(this.$el.dataset.tagData);
    }

    if (typeof this.$el.dataset.value !== 'undefined' && this.$el.dataset.value !== '') {
      this.config['items'] = JSON.parse(this.$el.dataset.value).map((item) => item.id);
    }

    this.select = new TomSelect(this.$el, this.config);
  }

  renderItem (item, escape) {
    const userInfo = typeof item.user !== 'undefined' ? `<span class="text-muted">${escape(item.user.name)}&sol;</span>` : '';
    return `<div class="item">${userInfo}${escape(item.name)}</div>`;
  }

  selectAllowsCreation () {
    return typeof this.$el.dataset.allowCreation !== 'undefined';
  }

  displayNewSuggestions (tags) {
    if (typeof tags !== 'object' || tags.length === 0) {
      return;
    }

    this.$suggestionsContent.innerHTML = '';

    tags.slice(0, 20).forEach(newTag => {
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
    this.select.createItem(value);

    $tag.classList.remove('cursor-pointer');
    $tag.classList.add('text-success');
  }
}
