<template>
  <div class="posts-filter">
    <ul class="main-filter" data-filter-name="type">
      <li v-for="type in types"
          :key="type.type_slug">
        <button @click.prevent="chooseType" type="button" class="sar-btn-grey"
                :data-filter="type.type_slug"
                :class="(type.type_slug === 'all' && $store.getters.getFilter.type === '')
                || ($store.getters.getFilter.type === type.type_slug) ? 'active': ''">
          {{ type.type_name }}
        </button>
      </li>
      <li><a href="#" class="toggle-list" :class="subfilterVisible ? 'active': ''"
             @click.prevent="subfilterVisible = !subfilterVisible">
        <svg id="svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             width="19" height="12.002" viewBox="0, 0, 400,252.63157894736844">
          <g id="svgg">
            <path id="path0"
                  d="M0.000 26.279 L 0.000 52.558 200.000 52.558 L 400.000 52.558 400.000 26.279 L 400.000 0.000 200.000 0.000 L 0.000 0.000 0.000 26.279 M56.222 95.011 C 54.115 95.227,53.650 95.400,53.262 96.113 C 52.265 97.949,52.323 155.137,53.324 156.644 C 54.229 158.007,44.218 157.920,200.918 157.924 L 346.119 157.928 346.256 157.404 C 346.332 157.116,346.629 156.818,346.917 156.743 L 347.442 156.606 347.442 126.945 C 347.442 107.339,347.356 97.284,347.189 97.284 C 347.050 97.284,346.936 97.069,346.936 96.806 C 346.936 95.662,346.180 95.247,343.650 95.002 C 340.823 94.729,58.902 94.738,56.222 95.011 M139.243 200.400 C 136.874 200.868,136.958 199.833,136.957 228.490 L 136.955 252.685 200.000 252.685 L 263.045 252.685 263.026 227.732 C 263.005 200.697,263.038 201.352,261.635 200.632 C 260.787 200.196,141.420 199.971,139.243 200.400 "
                  stroke="none" fill="#045c7c" fill-rule="evenodd"></path>
          </g>
        </svg>
        <span>Filter by Industry</span></a></li>
    </ul>
    <ul class="sub-filter" v-if="subfilterVisible" data-filter-name="industry">
      <li v-for="industry in industries"
          :key="industry.slug">
        <button @click.prevent="chooseIndustry" type="button" class="sar-btn-link"
                :data-filter="industry.slug"
                :class="industry.slug === $store.getters.getFilter.industry ? 'active' : ''">
          {{ industry.name }}
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
import {currentFilter} from '@/helpers';

export default {
  name: 'PostsFilter',
  data() {
    return {
      subfilterVisible: true,
    };
  },
  methods: {
    chooseType(e) {
      this.$store.dispatch('chooseType', e.target.dataset.filter === 'all' ? '' : e.target.dataset.filter);
      this.updateHistory();
    },
    chooseIndustry(e) {
      this.$store.dispatch('chooseIndustry', e.target.dataset.filter);
      this.updateHistory();
    },
    updateHistory() {
      const currentPage = window.location.pathname.split('/')[1];
      const urlObject = new URL(window.location.href);
      const currFilter = currentFilter(this.$store.getters.getFilter);

      urlObject.pathname = `/${currentPage}/` + Object.keys(currFilter)
          .map(k => `${k}-${currFilter[k]}`)
          .join('/');

      window.history.pushState(currFilter, '', urlObject.toString());
    },
  },
  computed: {
    types() {
      return this.$store.getters.getTypes;
    },
    industries() {
      return this.$store.getters.getIndustries;
    },
  },
};
</script>

<style>

</style>