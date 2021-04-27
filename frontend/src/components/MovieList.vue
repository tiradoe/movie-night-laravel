<template>
  <ul v-show="movies.length">
    <li :key="movie.id" v-for="movie in movies">{{ movie.title }}</li>
  </ul>
  <p v-show="!movies.length">No movies in list</p>
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import { Movie } from "@/types/index";
import store from "@/store/index";

export default defineComponent({
  name: "MovieList",
  components: {},
  computed: {
    movies(): Movie[] {
      return store.state.currentList.movies;
    },
  },
  mounted() {
    this.$http.get(`/lists/${this.$route.params.id}`).then((response: any) => {
      store.commit("updateList", response.data.list);
    });
  },
});
</script>
