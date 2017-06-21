import $ from 'jquery';
import Search from '../components/search';
import SearchForm from '../components/search-form';

export default class SearchPage {

  constructor() {
    this.$ = {
      search: $('#search'),
    };

    this.tourvisorId = this.$.search.attr('data-tourvisorId');
  }

  init() {
    this.form = new SearchForm();
    this.search = new Search(this.tourvisorId, this.form);
  }
}
