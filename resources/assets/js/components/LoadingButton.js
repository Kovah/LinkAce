export default class LoadingButton {

  constructor ($el) {
    this.$el = $el;
    this.$form = this.$el.form;

    this.$el.addEventListener('click', this.onClick.bind(this))
  }

  onClick () {
    if (this.formIsValid()) {
      this.$el.disabled = true;
    }
  }

  formIsValid() {
    return this.$form.checkValidity();
  }
}
