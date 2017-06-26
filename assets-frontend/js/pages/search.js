import $ from 'jquery';
import Search from '../components/search';
import SearchForm from '../components/search-form';

export default class SearchPage {

  constructor() {
    this.$ = {
      search: $('#search'),
    };

    this.searchId = this.$.search.attr('data-searchId');
  }

  init() {
    this.form = new SearchForm();
    this.search = new Search(this.searchId, this.form);
  }
}
