<template>
  <div class="bg-gray-300 p-5 rounded">
    <!-- FILTER FIELD-->
    <input
      id="filter"
      class="w-full p-2 mb-5 rounded shadow focus:shadow-outline"
      type="text"
      v-model="filterString"
      aria-label="Filter movie list"
      placeholder="Filter Movie List"
      aria-placeholder="Filter Movie List"
      v-show="movies.length"
    />

    <!-- MOVIE LIST -->
    <ul class="mb-10 grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-5 gap-4">
      <!-- LIST ITEM -->
      <li
        class="flex flex-col border border-gray-300 rounded-lg shadow"
        :key="movie.id"
        v-for="movie in filterList"
      >
        <!-- MOVIE POSTER -->
        <img
          class="cursor-pointer rounded-t object-fill w-full sm:h-1/2 md:h-2/3"
          @click="showDetails(movie, $event)"
          :src="movie.poster"
        />

        <!-- TITLE AND YEAR -->
        <div
          class="h-full sm:text-sm flex flex-col rounded sm:rounded-none text-gray-200 bg-header p-2 sm:h-1/2 md:h-1/3"
        >
          <span
            class="mx-auto font-semibold h-2/4 text-xs lg:text-base overflow-hidden"
          >
            {{ movie.title }}
          </span>

          <span class="my-2 h-1/4"> {{ movie.year }}</span>

          <!-- TRASH ICON -->
          <font-awesome-icon
            @click="deleteMovie(movie.id)"
            class="mx-auto mt-2 cursor-pointer hover:text-red-700 h-1/4"
            icon="trash-alt"
          />
        </div>
      </li>

      <!-- MOVIE DETAILS -->
      <div
        class="wtf sm:p-5 sm:h-full w-full bg-blue-300 sm:row-span-1 sm:col-span-4"
        id="movie-details"
        v-show="displayMovie"
      >
        <h2 class="font-semibold" v-if="displayMovie">
          {{ displayMovie.title }}
          ({{ displayMovie.year }})
        </h2>
        <div class="flex flex-col">
          <div class="text-xs mb-5">
            <span v-if="displayMovie">{{ displayMovie.plot }}</span>
          </div>
          <div>
            <ul class="text-left">
              <li>Showing 1</li>
              <li>Showing 2</li>
              <li>Showing 3</li>
              <li>Showing 4</li>
            </ul>
            <add-showing
              v-if="displayMovie"
              class="py-2 w-36"
              :movieId="displayMovie.id"
              @updated-list="updateList"
            />
          </div>
        </div>
      </div>
    </ul>
    <p class="p-10" v-show="!movies.length">No movies in list</p>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import { Movie } from "@/types/index";
import { AxiosError, AxiosResponse } from "axios";
import { Showing } from "@/types/index";
import AddShowing from "./AddShowing.vue";
import store from "@/store/index";

let displayMovie: Movie | null;

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
      displayMovie: displayMovie,
    };
  },
  emits: ["loaded"],
  methods: {
    deleteMovie(movieId: number): void {
      this.$http
        .delete(`/api/lists/${this.listId}/movie/${movieId}`)
        .then((response: AxiosResponse) => {
          store.commit("updateList", response.data.list);
        });
    },
    showDetails(movie: Movie, event: any): void {
      const listItem = event.currentTarget?.parentNode;
      this.displayMovie = movie;

      listItem.insertAdjacentElement(
        "afterend",
        document.getElementById("movie-details")
      );
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

          this.$emit("loaded");
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


