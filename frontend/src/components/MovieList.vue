<template>
  <div class="bg-white sm:mx-10 mb-10 rounded shadow">
    <ul v-show="movies.length">
      <li
        class="flex items-stretch p-5 even:bg-gray-200"
        :key="movie.id"
        v-for="movie in movies"
      >
        <span class="text-left sm:w-1/3">
          {{ movie.title }} ({{ movie.year }})</span
        >
        <span class="text-left sm:w-1/3" v-if="movie.next_showing">
          {{ showTime(movie.next_showing) }}
        </span>

        <div class="flex justify-end sm:w-1/3 space-x-4">
          <add-showing :movieId="movie.id" @updated-list="updateList" />
          <font-awesome-icon
            @click="deleteMovie(movie.id)"
            class="cursor-pointer hover:text-red-700"
            icon="trash-alt"
          />
        </div>
      </li>
    </ul>
    <p class="p-10" v-show="!movies.length">No movies in list</p>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import { Movie } from "@/types/index";
import store from "@/store/index";
import { AxiosResponse } from "axios";
import AddShowing from "./AddShowing.vue";
import { Showing } from "@/types/index";

export default defineComponent({
  name: "MovieList",
  components: {
    AddShowing,
  },
  computed: {
    movies(): Movie[] {
      return store.state.currentList.movies;
    },
  },
  data: function () {
    return {
      listId: this.$route.params.id,
    };
  },
  methods: {
    deleteMovie(movieId: number): void {
      this.$http
        .delete(`/api/lists/${this.listId}/movie/${movieId}`)
        .then((response: AxiosResponse) => {
          store.commit("updateList", response.data.list);
        });
    },
    showTime(dtTime: Showing[]): string {
      if (dtTime.length > 0) {
        return `Next Showing: ${new Date(
          dtTime[0].show_time
        ).toLocaleString()}`;
      } else {
        return "Not Scheduled";
      }
    },
    updateList() {
      this.$http
        .get(`/api/lists/${this.$route.params.id}`)
        .then((response: AxiosResponse) => {
          store.commit("updateList", response.data.list);
        });
    },
  },
  mounted() {
    this.updateList();
  },
});
</script>
