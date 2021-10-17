<template>
  <div class="posts-filter">
    <ul class="main-filter">
      <li
          v-for="(cat, index) in allCats"
          :key="index"
      >
        <button
            href="#"
            @click.prevent="chooseCategory"
            :data-category="index"
            :class="index === $store.getters.getFilter.cat ? 'active' : ''"
            class="sar-btn-grey"
        >{{ cat.name || 'All Categories' }}
        </button>
      </li>
      <li>
        <a
            class="toggle-list"
            :class="tagsVisible ? 'active': ''"
            href="#"
            @click.prevent="tagsVisible = !tagsVisible">
          <svg id="svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
               width="19" height="12.002" viewBox="0, 0, 400,252.63157894736844">
            <g id="svgg">
              <path id="path0"
                    d="M0.000 26.279 L 0.000 52.558 200.000 52.558 L 400.000 52.558 400.000 26.279 L 400.000 0.000 200.000 0.000 L 0.000 0.000 0.000 26.279 M56.222 95.011 C 54.115 95.227,53.650 95.400,53.262 96.113 C 52.265 97.949,52.323 155.137,53.324 156.644 C 54.229 158.007,44.218 157.920,200.918 157.924 L 346.119 157.928 346.256 157.404 C 346.332 157.116,346.629 156.818,346.917 156.743 L 347.442 156.606 347.442 126.945 C 347.442 107.339,347.356 97.284,347.189 97.284 C 347.050 97.284,346.936 97.069,346.936 96.806 C 346.936 95.662,346.180 95.247,343.650 95.002 C 340.823 94.729,58.902 94.738,56.222 95.011 M139.243 200.400 C 136.874 200.868,136.958 199.833,136.957 228.490 L 136.955 252.685 200.000 252.685 L 263.045 252.685 263.026 227.732 C 263.005 200.697,263.038 201.352,261.635 200.632 C 260.787 200.196,141.420 199.971,139.243 200.400 "
                    stroke="none" fill="#045c7c" fill-rule="evenodd"></path>
            </g>
          </svg>
          <span>Tags</span>
        </a>
      </li>
    </ul>
    <ul class="sub-filter" v-if="tagsVisible">
      <li
          v-for="(tag, index) in allTags"
          :key="index"
      >
        <button
            @click.prevent="chooseTag"
            :class="tag === $store.getters.getFilter.tag ? 'active': ''"
            :data-tag="tag"
            class="sar-btn-link"
        >
          {{ tag }}
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'Header',
  data() {
    return {
      tagsVisible: false,
    };
  },
  methods: {
    chooseCategory(e) {
      this.$store.dispatch('selectCategory', e.target.dataset.category);
    },

    chooseTag(e) {
      if (this.$store.getters.getFilter.tag === e.target.dataset.tag) {
        this.$store.dispatch('selectTag', '');
      } else {
        this.$store.dispatch('selectTag', e.target.dataset.tag);
      }
    },
  },

  computed: {
    allCats() {
      return this.$store.getters.getCats;
    },

    allTags() {
      return this.$store.getters.getTags;
    },
  },
};

</script>

<style lang="scss">

</style>