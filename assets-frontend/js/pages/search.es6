import $ from 'jquery';
import Search from '../components/search.es6';
import SearchForm from '../components/search-form.es6';

export default class SearchPage {

  constructor() {
    this.$ = {
      search: $('#search'),
    };

    this.searchId = this.$.search.attr('data-searchId');
  }

  init() {
    this.form = new SearchForm();
    this.form.init();
    this.search = new Search(this.searchId, this.form);
  }
}
