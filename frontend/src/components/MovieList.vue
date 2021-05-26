<template>
  <!-- FILTER FIELD-->
  <input
    id="filter"
    class="p-0 m-5 sm:mx-10 p-2 rounded rounded-r-none shadow border-r-none focus:shadow-outline"
    type="text"
    v-model="filterString"
    aria-label="Filter movie list"
    placeholder="Filter Movie List"
    aria-placeholder="Filter Movie List"
  />

  <!-- MOVIE LIST -->
  <ul
    class="p-0 mx-5 grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-5 gap-4 sm:p-5 xl:mx-auto"
    v-show="movies.length"
  >
    <li
      class="bg-gray-300 border border-gray-300 rounded-lg shadow"
      :key="movie.id"
      v-for="movie in filterList"
    >
      <!-- MOVIE POSTER -->
      <img class="hidden object-cover w-full sm:block" :src="movie.poster" />

      <!-- TITLE AND YEAR -->
      <div class="flex flex-col bg-gray-300 sm:px-5">
        <span class="py-2 font-semibold">
          {{ movie.title }} ({{ movie.year }})</span
        >
        <!-- NEXT SHOWING-->
        <span class="py-2" v-if="movie.next_showing">
          {{ showTime(movie.next_showing) }}
        </span>

        <add-showing
          class="py-2"
          :movieId="movie.id"
          @updated-list="updateList"
        />
        <font-awesome-icon
          @click="deleteMovie(movie.id)"
          class="mx-auto my-2 cursor-pointer hover:text-red-700"
          icon="trash-alt"
        />
      </div>
    </li>
  </ul>
  <p class="p-10" v-show="!movies.length">No movies in list</p>
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import { Movie } from "@/types/index";
import store from "@/store/index";
import { AxiosError, AxiosResponse } from "axios";
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
    filterList(): Movie[] {
      return store.state.currentList.movies.filter(
        (movie) =>
          movie.title.toLowerCase().search(this.filterString.toLowerCase()) > -1
      );
    },
  },
  data: function () {
    return {
      listId: this.$route.params.id,
      filterString: "",
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
        const displayDate = new Date(dtTime[0].show_time).toLocaleString(
          "en-US",
          {
            month: "2-digit",
            day: "2-digit",
            year: "2-digit",
          }
        );
        return `Next Showing: ${displayDate}`;
      } else {
        return "Not Scheduled";
      }
    },
    updateList() {
      this.$http
        .get(`/api/lists/${this.$route.params.id}`)
        .then((response: AxiosResponse) => {
          store.commit("updateList", response.data.list);
        })
        .catch((error: AxiosError) => {
          if (error.response?.status === 401) {
            store.commit("updateLogin", false);
            this.$router.push("/login");
          }
        });
    },
  },
  mounted() {
    this.updateList();
  },
});
</script>
