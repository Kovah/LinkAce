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
    this.filterRole = this.$el.dataset.filterRole;
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
      onInitialize: function () {
        if (!selectObject.$el.value.startsWith('[')) {
          selectObject.$el.value = `[${selectObject.$el.value}]`;
        }
      },
      onChange: function () {
        const items = this.items.map((item) => {
          item = (typeof item === 'string' && /^\d+$/.test(item)) ? Number(item) : item;
          const option = Object.values(this.options).find((option) => option.id === item);
          return option !== undefined ? option.id : item;
        });
        selectObject.$el.value = items.length > 0 ? JSON.stringify(items) : null;
        selectObject.updateSearchFilterControls();
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
      this.config['items'] = JSON.parse(this.$el.dataset.value).map((item) => item.id ?? item);
    }

    this.select = new TomSelect(this.$el, this.config);
    this.setupSearchFilterControls();
  }

  renderItem (item, escape) {
    const userInfo = typeof item.user !== 'undefined' ? `<span class="text-muted">${escape(item.user.name)}&sol;</span>` : '';
    return `<div class="item">${userInfo}${escape(item.name)}</div>`;
  }

  selectAllowsCreation () {
    return typeof this.$el.dataset.allowCreation !== 'undefined';
  }

  setupSearchFilterControls () {
    if (this.filterRole !== 'include') {
      return;
    }

    this.$form = this.$el.closest('form');
    this.$modeInput = this.$form.querySelector(`#${this.type === 'tags' ? 'tag' : 'list'}_mode`);
    this.$excludeWrapper = this.$form.querySelector(`[data-filter-exclude-wrapper="${this.type}"]`);

    if (!this.$modeInput || !this.$excludeWrapper) {
      return;
    }

    this.$hint = document.createElement('div');
    this.$hint.className = 'search-filter-hint text-xs text-pale mt-1 d-none';
    this.$hint.innerHTML = `
      Links must match: <strong data-filter-mode-label></strong>
      &middot;
      <button type="button" class="btn btn-link btn-xs p-0 align-baseline" data-filter-toggle-mode></button>
      &middot;
      <button type="button" class="btn btn-link btn-xs p-0 align-baseline" data-filter-show-exclusion></button>
    `;

    this.$modeLabel = this.$hint.querySelector('[data-filter-mode-label]');
    this.$modeToggle = this.$hint.querySelector('[data-filter-toggle-mode]');
    this.$showExclusion = this.$hint.querySelector('[data-filter-show-exclusion]');
    this.$removeExclusion = this.$form.querySelector(`[data-filter-remove-exclusion="${this.type}"]`);

    this.$el.parentElement.appendChild(this.$hint);

    this.$modeToggle.addEventListener('click', () => {
      this.$modeInput.value = this.$modeInput.value === 'all' ? 'any' : 'all';
      this.updateSearchFilterControls();
    });

    this.$showExclusion.addEventListener('click', () => {
      this.$excludeWrapper.classList.remove('d-none');
      this.updateSearchFilterControls();
    });

    this.$removeExclusion?.addEventListener('click', () => {
      const excludeSelect = this.$excludeWrapper.querySelector('.tag-select')?.tomselect;
      excludeSelect?.clear();
      this.$excludeWrapper.classList.add('d-none');
      this.updateSearchFilterControls();
    });

    this.updateSearchFilterControls();
  }

  updateSearchFilterControls () {
    if (this.filterRole !== 'include' || !this.$hint) {
      return;
    }

    const selectedCount = this.select?.items.length ?? 0;
    const exclusionVisible = !this.$excludeWrapper.classList.contains('d-none');
    const showControls = selectedCount >= 2 || exclusionVisible;
    const mode = this.$modeInput.value === 'any' ? 'any' : 'all';
    const noun = this.type === 'tags' ? 'tag' : 'list';

    this.$hint.classList.toggle('d-none', !showControls);
    this.$modeLabel.textContent = `${mode} ${this.type}`;
    this.$modeToggle.textContent = `change to ${mode === 'all' ? 'any' : 'all'}`;
    this.$showExclusion.textContent = `+ exclude ${this.type}`;
    this.$showExclusion.classList.toggle('d-none', exclusionVisible);

    if (!showControls) {
      this.$modeInput.value = 'all';
    }

    this.$excludeWrapper.querySelector('input')?.setAttribute('aria-label', `Exclude ${noun}s`);
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
