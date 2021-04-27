<template>
  <div class="bg-white mt-10 sm:m-10 mb-10 rounded shadow">
    <ul v-show="movies.length">
      <li
        class="flex p-5 even:bg-gray-200"
        :key="movie.id"
        v-for="movie in movies"
      >
        <span> {{ movie.title }}</span>
        <span class="flex-1 text-right"> {{ movie.year }}</span>
      </li>
    </ul>
    <p class="p-10" v-show="!movies.length">No movies in list</p>
  </div>
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
