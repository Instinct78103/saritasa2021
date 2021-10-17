<template>
  <div id="app">
    <PostsFilter></PostsFilter>
    <Posts></Posts>
    <Pagination></Pagination>
  </div>
</template>

<script>
import PostsFilter from '@/components/PostsFilter';
import Posts from '@/components/Posts';
import Pagination from '@/components/Pagination';

export default {
  name: 'App',
  components: {
    PostsFilter,
    Posts,
    Pagination,
  },
  created() {
    this.$store.dispatch('loadFilter');
  },
};
</script>

<style lang="scss">

</style>
