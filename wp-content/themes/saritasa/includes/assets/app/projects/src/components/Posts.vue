<template>
  <div class="posts-container" v-if="this.$store.getters.get_postsNumberByQuery > 0">
    <div class="col span_3 element"
         v-for="post in getPosts"
         :key="post.name"
         :class="[post.type, post.industry]">
      <div class="work-item">
        <div class="bg" :style="`background-image: url(${post.image})`"></div>
        <div class="work-info">
          <p class="work-short-description">{{ post.title }}</p>
          <hr>
          <span class="work-category"></span>
        </div>
        <a :href="post.href"></a>
      </div>
    </div>
  </div>
  <div class="empty" v-else-if="this.$store.getters.get_postsNumberByQuery === 0">
    <span>No projects found</span>
  </div>
  <div class="loading" v-else></div>
</template>

<script>
import {currentFilter, getArrayByMultiFilter} from '@/helpers';

export default {
  name: 'Posts',
  computed: {
    getPosts() {
      if (Object.values(this.$store.getters.getFilter).every(prop => prop === '')) {
        return this.$store.getters.getPosts;
      } else {
        return getArrayByMultiFilter(this.$store.getters.getPosts, currentFilter(this.$store.getters.getFilter));
      }
    },
  },
};
</script>

<style>

</style>