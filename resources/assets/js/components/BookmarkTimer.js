export default class BookmarkTimer {

  constructor ($el) {
    this.$el = $el;
    this.init();
  }

  init () {
    window.setInterval(function () {
      this.$el.text(parseInt(this.$el.text()) - 1);
    }, 1000);

    window.setTimeout(function () {
      window.close();
    }, 5000);
  }
}
