<template>
  <div class="pagination" v-if="$store.state.paginationVisible">
    <button type="button" class="sar-btn" @click.prevent="viewMore">View More</button>
  </div>
</template>

<script>
export default {
  name: 'Pagination',
  methods: {
    viewMore() {
      this.$store.dispatch('loadPosts', this.$store.getters.getPosts.map(post => post.id));
    },
  },
};
</script>

<style>

</style>