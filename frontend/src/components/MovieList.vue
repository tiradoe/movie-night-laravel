<template>
  <div class="p-5 mb-10 bg-gray-300 rounded">
    <!-- FILTER FIELD-->
    <input
      aria-label="Filter movie list"
      aria-placeholder="Filter Movie List"
      class="w-full p-2 mb-5 rounded shadow focus:shadow-outline"
      id="filter"
      placeholder="Filter Movie List"
      type="text"
      v-model="filterString"
      v-show="movies.length || filterActivated"
    />

    <div
      id="filter-options"
      class="mb-5 font-semibold text-left"
      v-show="(movies.length || filterActivated) && loggedIn"
    >
      <label for="hide-scheduled" class="pr-2">Hide Scheduled</label>
      <input id="hide-scheduled" type="checkbox" v-model="hideScheduled" />
    </div>
    <!-- MOVIE LIST -->

    <ul
      id="movie-list"
      class="mb-10 grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-5 gap-4"
    >
      <!-- LIST ITEM -->
      <li
        class="flex flex-col border border-gray-300 rounded-lg shadow"
        :data-movie="movie.id"
        :key="movie.id"
        v-for="movie in movies"
      >
        <!-- MOVIE POSTER -->
        <img
          class="object-fill w-full rounded-t cursor-pointer sm:h-1/2 md:h-2/3"
          @click="showDetails(movie, $event)"
          :src="movie.poster"
        />

        <!-- TITLE AND YEAR -->
        <div
          class="flex flex-col h-full p-3 text-gray-200 rounded-none rounded-b sm:text-sm bg-header sm:h-1/2 md:h-1/3"
        >
          <span
            class="mx-auto overflow-hidden text-xs font-semibold h-2/4 lg:text-base"
          >
            {{ movie.title }}
          </span>

          <span class="my-2 h-1/4"> {{ movie.year }}</span>

          <!-- TRASH ICON -->
          <font-awesome-icon
            v-show="loggedIn"
            @click="deleteMovie(movie.id)"
            class="mx-auto mt-2 cursor-pointer hover:text-red-700 h-1/4"
            icon="trash-alt"
          />
        </div>
      </li>

      <!-- MOVIE DETAILS -->
      <div
        class="w-full p-2 bg-blue-100 sm:p-5 sm:h-full col-span-2 sm:col-span-4 xl:col-span-5"
        id="movie-details"
        v-show="displayMovie"
      >
        <h2 class="font-semibold" v-if="displayMovie">
          {{ displayMovie.title }}
          ({{ displayMovie.year }})
        </h2>
        <div class="flex flex-col">
          <div class="mb-5 text-base text-left">
            <h3 class="py-5 font-semibold">Plot</h3>
            <span v-if="displayMovie">{{ displayMovie.plot }}</span>
            <span v-if="displayMovie" class="block pt-3"
              >Rated {{ displayMovie.rated }}</span
            >
          </div>
          <div>
            <add-showing
              v-if="displayMovie"
              v-show="loggedIn"
              class="py-2 mx-auto w-36"
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
import { defineComponent } from "vue";
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
    filterActivated(): boolean {
      return this.filterString !== "" || this.hideScheduled === true;
    },
    loggedIn(): boolean {
      return store.state.loggedIn;
    },
    movies(): Movie[] {
      this.resetDetails();
      let movies: Movie[] = store.state.currentList.movies;

      if (this.filterString !== "") {
        movies = movies.filter(
          (movie) =>
            movie.title.toLowerCase().search(this.filterString.toLowerCase()) >
            -1
        );
      }

      if (this.hideScheduled) {
        movies = movies.filter((movie) => movie.showings.length === 0);
      }

      return movies;
    },
  },
  data: function () {
    return {
      hideScheduled: false,
      filterString: "",
      listId: this.$route.params.id,
      displayMovie: displayMovie,
    };
  },
  emits: ["loaded"],

  methods: {
    deleteMovie(movieId: number): void {
      this.resetDetails();
      this.$http
        .delete(`/api/lists/${this.listId}/movie/${movieId}`)
        .then((response: AxiosResponse) => {
          store.commit("updateList", response.data);
        });
    },
    resetDetails(): void {
      this.displayMovie = null;
    },
    showDetails(movie: Movie, event: any): void {
      const windowWidth: number = window.innerWidth;
      const listItem: HTMLLIElement = event.currentTarget.parentNode;
      const movieDetailsDiv: HTMLElement | null = document.getElementById(
        "movie-details"
      );
      let listWidth = 2;

      this.displayMovie = movie;

      if (windowWidth >= 640 && windowWidth < 1280) {
        // sm - lg
        listWidth = 4;
      } else if (windowWidth > 1280) {
        // xl
        listWidth = 5;
      }

      const selectedIndex: number = this.movies.findIndex((movie) => {
        return movie.id === Number(listItem.getAttribute("data-movie"));
      });

      // Calculate the end of the row
      const movieRowLocation: number = (selectedIndex + 1) % listWidth;
      let rowEnd: HTMLLIElement | null;

      if (movieRowLocation === 0) {
        rowEnd = listItem;
      } else {
        const rowEndIndex = selectedIndex + (listWidth - movieRowLocation);
        let rowEndElement;

        if (rowEndIndex >= this.movies.length) {
          rowEndElement = this.movies[this.movies.length - 1];
        } else {
          rowEndElement = this.movies[rowEndIndex];
        }

        rowEnd = document.querySelector(`[data-movie='${rowEndElement.id}']`);
      }

      if (movieDetailsDiv && rowEnd) {
        rowEnd.insertAdjacentElement("afterend", movieDetailsDiv);
      }
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
      this.resetDetails();
      let list_id = this.$route.params.id;

      // Use slug in public view
      if (typeof list_id === "undefined") {
        list_id = this.$route.params.slug;
      }

      this.$http
        .get(`/api/lists/${list_id}`)
        .then((response: AxiosResponse) => {
          store.commit("updateList", response.data);

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
