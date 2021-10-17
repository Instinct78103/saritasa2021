<template>
  <div id="app">
    <vue-ins-progress-bar v-if="!this.$store.getters.getLoaded"></vue-ins-progress-bar>
    <div v-else>
      <Header/>
      <AllPosts
          :category="$store.state.filter.cat"
          :tag="$store.state.filter.tag"
      />
      <div class="pagination" v-if="$store.state.isButtonVisible">
        <button type="button" class="sar-btn" @click.prevent="showMore">View More</button>
      </div>
    </div>
  </div>
</template>

<script>
import Header from '@/components/Header';
import AllPosts from '@/components/AllPosts';

export default {
  name: 'App',
  components: {
    Header,
    AllPosts,
  },
  methods: {
    showMore() {
      this.$store.dispatch('loadPosts', this.$store.getters.getPosts.map(item => item.ID));
    },
  },
  created() {
    this.$insProgress.start();
    this.$store.dispatch('loadCatsAndTags');
    this.$store.dispatch('loadPosts', '');
  },
  mounted() {
    this.$insProgress.finish();
  },
};
</script>

<style lang="scss">

</style>
