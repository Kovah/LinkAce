export default class BulkEdit {

  constructor ($el) {
    this.$el = $el;
    this.$form = $el.querySelector('.bulk-edit-form');
    this.$submit = $el.querySelector('.bulk-edit-submit');
    this.$selectAll = $el.querySelector('.bulk-edit-select-all');
    this.$models = $el.querySelectorAll('.bulk-edit-model');

    this.$form.querySelector('[name="type"]').value = $el.dataset.type;
    this.selectedModels = [];

    this.init();
  }

  init () {
    this.$models.forEach($model => {
      $model.addEventListener('change', this.toggleBulkEdit.bind(this));
    });
    this.$selectAll.addEventListener('click', this.selectAll.bind(this));
    this.$submit.addEventListener('click', this.submitEdit.bind(this));
  }

  toggleBulkEdit (event) {
    const newModel = event.target.dataset.id;
    if (event.target.checked) {
      this.selectedModels.push(newModel);
    } else {
      this.selectedModels = this.selectedModels.filter(existingModel => newModel !== existingModel);
    }
    this.toggleHeader();
  }

  toggleHeader () {
    if (this.selectedModels.length > 0) {
      this.$form.classList.remove('visually-hidden');
    } else {
      this.$form.classList.add('visually-hidden');
    }
  }

  selectAll () {
    if (this.selectedModels.length === this.$models.length) {
      this.selectedModels = [];
      this.$models.forEach($model => {
        $model.checked = false;
      });
      this.toggleHeader();
    } else {
      this.selectedModels = [];
      this.$models.forEach($model => {
        this.selectedModels.push($model.dataset.id);
        $model.checked = true;
      });
    }
  }

  submitEdit () {
    this.$form.querySelector('[name="models"]').value = this.selectedModels.join(',');
    this.$form.submit();
  }
}
