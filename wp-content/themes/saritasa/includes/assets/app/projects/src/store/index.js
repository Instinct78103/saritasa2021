import Vue from 'vue';
import Vuex from 'vuex';
import {currentFilter, getArrayByMultiFilter} from '@/helpers';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    types: [],
    industries: [],
    posts: [],
    filter: {
      type: '',
      industry: '',
    },
    postsNumber: null,
    paginationVisible: true,
  },
  getters: {
    getTypes(state) {
      return state.types;
    },
    getIndustries(state) {
      return state.industries;
    },
    getFilter(state) {
      return state.filter;
    },
    getPosts(state) {
      return state.posts;
    },
    getPaginationStatus(state) {
      return state.paginationVisible;
    },
    get_postsNumberByQuery(state) {
      return state.postsNumber;
    },
  },
  mutations: {
    /**
     * initial state
     * @param state
     * @param payload
     */
    set_types(state, payload) {
      state.types = payload;
    },
    set_industries(state, payload) {
      state.industries = payload;
    },
    set_posts(state, payload) {
      state.posts.push(...payload);
    },

    /**
     *
     * @param state
     * @param payload
     */
    set_type(state, payload) {
      state.filter.type = payload;
    },
    set_industry(state, payload) {
      state.filter.industry = payload;
    },
    set_paginationStatus(state, payload) {
      state.paginationVisible = payload;
    },
    set_postsNumber(state, payload) {
      state.postsNumber = payload;
    },
  },
  actions: {
    async loadFilter({commit}) {
      try {
        const promise = await fetch('/wp-json/sar/v2/projects/types_industries');
        const receivedData = await promise.json();

        /**
         * There is no need to create another endpoint, therefore we use this method
         */
        const allIndustries = Object.values(receivedData)
          .filter(item => item.industries.length) // if category (type) has tags (industries)
          .reduce((final, item) => { // flatten the array to [{name: a, slug: b}, {name: c, slug: d}] to gather all tags (industries)
            return final.concat(item.industries);
          }, [])
          .filter((item, index, self) => self.findIndex(obj => obj.name === item.name && obj.slug === item.slug) === index) //remove duplicates
          .sort((a, b) => a.slug.localeCompare(b.slug)); //Sort by slug in alphabet order

        commit('set_types', [
          {
            'type_name': 'All Categories',
            'type_slug': '',
            'industries': allIndustries,
          }, ...receivedData]);

        const splitted = window.location.pathname.split('/').filter(item => item);
        const objectByUrl = splitted.reduce((arr, item) => {
          if (item.includes('type-')) {
            arr['type'] = '';
            arr['type'] = item.split('type-')[1];
          } else if (item.includes('industry-')) {
            arr['industry'] = '';
            arr['industry'] = item.split('industry-')[1];
          }
          return {...arr};
        }, []);

        if (objectByUrl.hasOwnProperty('type') && objectByUrl.hasOwnProperty('industry')) {
          commit('set_type', objectByUrl.type);
          const industriesByType = getArrayByMultiFilter(this.getters.getTypes, {type_slug: objectByUrl.type})[0]?.industries;
          commit('set_industries', industriesByType);
          commit('set_industry', objectByUrl.industry);
        } else if (objectByUrl.hasOwnProperty('type')) {
          commit('set_type', objectByUrl.type);
          const industriesByType = getArrayByMultiFilter(this.getters.getTypes, {type_slug: objectByUrl.type})[0]?.industries;
          commit('set_industries', industriesByType);
        } else if (objectByUrl.hasOwnProperty('industry')) {
          commit('set_industries', allIndustries);
          commit('set_industry', objectByUrl.industry);
        } else {
          commit('set_industries', allIndustries);
        }
        this.dispatch('loadPosts');
      } catch (err) {
        console.log(err);
      }
    },
    async loadPosts({commit}) {
      try {
        const endpoint_posts = '/wp-json/sar/v2/projects/posts?';

        const currentFilterIsEmpty = Object.keys(currentFilter(this.getters.getFilter)).length === 0;

        const posts_per_page = currentFilterIsEmpty
          ? 8 - this.getters.getPosts.length % 8
          : 8 - getArrayByMultiFilter(this.getters.getPosts, currentFilter(this.getters.getFilter)).length % 8;

        const params = {
          'project-type': this.getters.getFilter.type,
          'project-industry': this.getters.getFilter.industry,
          'exclude': this.getters.getPosts.map(post => post.id),
          posts_per_page,
        };

        const query = Object.keys(params).map(k => `${k}=${params[k]}`).join('&');
        const promises = await Promise.all([
          fetch(endpoint_posts + query),
        ]);
        const p = promises.map(res => res.json());
        const data = await Promise.all(p);

        commit('set_postsNumber', data[0].count);
        commit('set_posts', data[0].posts ? data[0].posts : []);

        const re_countPostsByFilterAmongAlreadyLoaded = getArrayByMultiFilter(this.getters.getPosts, currentFilter(this.getters.getFilter)).length;

        this.dispatch('displayPagination', !(re_countPostsByFilterAmongAlreadyLoaded === this.getters.get_postsNumberByQuery));
      } catch (err) {
        console.log(err);
      }
    },

    async displayPagination({commit}, status) {
      commit('set_paginationStatus', status);
    },

    async chooseType({commit, state}, selectedType) {
      if (!selectedType && state.filter.industry) {
        commit('set_industry', '');
        this.dispatch('loadPosts');
      } else if (selectedType !== state.filter.type) {
        commit('set_type', selectedType);
        commit('set_industry', '');

        const industriesByType = getArrayByMultiFilter(this.getters.getTypes, {'type_slug': this.getters.getFilter.type})[0]?.industries;
        commit('set_industries', industriesByType);

        this.dispatch('loadPosts');
      }
    },

    async chooseIndustry({commit, state}, selectedIndustry) {
      commit('set_industry', selectedIndustry === state.filter.industry ? '' : selectedIndustry);
      this.dispatch('loadPosts');
    },

  },
});