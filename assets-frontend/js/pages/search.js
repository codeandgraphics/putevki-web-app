import $ from 'jquery';
import moment from 'moment';
import BranchesMap from '../common/branches-map';
import { IS_DEV } from '../../app';
import Humanize from '../utils/humanize';
import Search from '../components/search';
import SearchForm from '../components/search-form';

export default class SearchPage {

  constructor() {

    this.form = new SearchForm();

    this.$ = {
      search: $('#search'),
    };

    this.tourvisorId = this.$.search.attr('data-tourvisorId');

    this.search = new Search(this.tourvisorId, this.form);
  }

  init() {
  }
}
