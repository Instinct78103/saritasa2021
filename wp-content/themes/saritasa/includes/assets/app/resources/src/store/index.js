import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    cats: [],
    tags: [],
    posts: [],
    statusLoaded: false,
    filter: {
      cat: '',
      tag: '',
    },
    postsNumber: 0,
    isButtonVisible: true,
  },
  getters: {
    getCats(state) {
      return {'': 'All Categories', ...state.cats};
    },
    getTags(state) {
      if (state.filter.cat) {
        return Object.entries(state.tags)
          .filter(item => item[1].catsByTag.includes(state.filter.cat))
          .map(item => item[0]);
      } else {
        return Object.entries(state.tags).map(item => item[0]);
      }
    },
    getPosts(state) {
      return state.posts;
    },
    getLoaded(state) {
      return state.statusLoaded;
    },
    getFilter(state) {
      return state.filter;
    },
  },
  mutations: {
    /**
     * initial state
     * @param state
     * @param payload
     */
    set_cats(state, payload) {
      state.cats = payload;
    },
    set_tags(state, payload) {
      state.tags = payload;
    },
    set_loaded(state, payload) {
      state.statusLoaded = payload;
    },
    set_posts(state, payload) {
      state.posts.push(...payload);
    },

    /**
     *
     * @param state
     * @param payload
     */
    set_filter(state, payload) {
      state.filter[Object.keys(payload)[0]] = Object.values(payload)[0];
    },
    set_cat(state, payload) {
      state.filter.cat = payload;
    },
    set_tag(state, payload) {
      state.filter.tag = payload;
    },
    set_show_more_button(state, payload) {
      state.isButtonVisible = payload;
    },
    set_posts_number(state, payload) {
      state.postsNumber = payload;
    },
  },
  actions: {
    async loadCatsAndTags({commit}) {
      try {
        const promise = await fetch('/wp-json/sar/v2/resources/cats_tags');
        const receivedData = await promise.json();

        commit('set_cats', receivedData.cats);
        commit('set_tags', receivedData.tags);
      } finally {
        commit('set_loaded', true);
      }
    },

    async loadPosts({commit}, params) {
      try {
        const url = '/wp-json/sar/v2/resources/posts?';
        const url2 = '/wp-json/sar/v2/resources/count_posts?';

        const params = {
          category: this.getters.getFilter.cat,
          tag: this.getters.getFilter.tag,
          loaded: this.getters.getPosts.map(post => post.ID),
        };
        const query = Object.keys(params).map(k => `${k}=${params[k]}`).join('&').replaceAll(' ', '-');

        const promises = await Promise.all([
          fetch(url + query),
          fetch(url2 + query),
        ]);

        const dataPromises = promises.map(result => result.json());
        const finalData = await Promise.all(dataPromises);

        commit('set_posts', finalData[0]);
        commit('set_posts_number', finalData[1]);

        let store = this;
        setTimeout(function () {
          if (document.querySelectorAll('.post-item').length === store.state.postsNumber) {
            store.dispatch('displayShowMoreButton', false);
          } else {
            store.dispatch('displayShowMoreButton', true);
          }
        }, 500);
      } catch (error) {
        console.warn(error);
      }
    },

    async showTagsByCat({commit}, tags) {
      commit('set_tags', tags);
    },

    async selectCategory({commit, state}, selectedCategory) {
      if (selectedCategory !== state.filter.cat) {
        commit('set_cat', selectedCategory);
        commit('set_tag', '');
        this.dispatch('loadPosts', this.getters.getPosts.map(item => item.ID));
      }
    },

    async selectTag({commit}, selectedTag) {
      commit('set_tag', selectedTag);
      this.dispatch('loadPosts', this.getters.getPosts.map(item => item.ID));
    },

    async displayShowMoreButton({commit}, status) {
      commit('set_show_more_button', status);
    },
  },
});