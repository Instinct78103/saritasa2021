<template>
  <isotope class="posts-container" :options="options" :list="chosenPosts">
    <div class="post-item"
         v-for="(item, key) in chosenPosts"
         :key="key"
         :class="[item.cats, item.tags ? item.tags.map(tag => tag.replaceAll(' ', '-')) : '']"
    >
      <a :href="item.permalink">
        <div class="post-img"
             :style="{backgroundImage: `url(${item.image})`}">
        </div>
      </a>
      <div class="content">
        <p class="tags">
            <span v-for="(tag,idx) in item.tags"
                  :key="idx"
            >{{ tag }}</span>
        </p>
        <a :href="item.permalink">
          <h2 class="heading">
            {{ item.title }}
          </h2>
        </a>
        <p class="excerpt">
          {{ item.excerpt }}
        </p>
        <div class="author">
          <img
              :src="item.avatar" alt="">
          <div>
            <h3 class="author-heading">
              {{ item.author }}
            </h3>
            <p class="author-date">
              {{ item.post_date }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </isotope>
</template>

<script>
import isotope from 'vueisotope';

export default {
  name: 'AllPosts',
  components: {
    isotope,
  },
  data() {
    return {
      options: {
        itemSelector: 'post-item',
        masonry: {},
      },
    };
  },
  computed: {
    chosenPosts() {
      let posts = [];
      //if filter empty
      if (Object.values(this.$store.getters.getFilter).every(prop => prop === '')) {
        // return this.$store.getters.getPosts;
        posts = this.$store.getters.getPosts;
        //if one of the props not empty
      } else if (Object.values(this.$store.getters.getFilter).some(prop => prop === '')) {
        //if cat not empty
        if (this.$store.getters.getFilter.cat) {
          posts = this.$store.getters.getPosts.filter(post => post.cats.includes(this.$store.getters.getFilter.cat));
        } else {
          //if tag not empty
          posts = this.$store.getters.getPosts.filter(post => {
            if (post.tags) {
              return post.tags.includes(this.$store.getters.getFilter.tag);
            }
          });
        }
      } else {
        // if both filter props not equal empty string
        posts = this.$store.getters.getPosts.filter(post => {
          // if post has a tag
          if (post.tags) {
            return post.cats.includes(this.$store.getters.getFilter.cat) &&
                post.tags.includes(this.$store.getters.getFilter.tag);
          }
        });
      }

      return posts;
    },
  },

};
</script>

<style>

</style>