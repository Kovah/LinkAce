export default class LoadingButton {

  constructor ($el) {
    this.$el = $el;

    this.$el.addEventListener('click', this.onClick.bind(this))
  }

  onClick () {
    this.$el.disabled = true;
  }
}
