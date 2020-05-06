export default class BookmarkTimer {

  constructor ($el) {
    this.$el = $el;
    this.init();
  }

  init () {
    window.setInterval(() => {
      this.$el.innerText = parseInt(this.$el.innerText) - 1;
    }, 1000);

    window.setTimeout(() => {
      window.close();
    }, 5000);
  }
}
