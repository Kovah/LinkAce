export default class SimpleSelect {

  constructor ($el) {
    $($el).selectize({
      create: false
    });
  }
}
