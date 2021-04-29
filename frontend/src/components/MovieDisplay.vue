<template>
  <div class="text-left bg-white rounded shadow sm:p-5">
    <div class="mb-10">
      <!-- MOVIE POSTER -->
      <img
        class="object-scale-down pt-5 mx-auto my-10 max-h-32 sm:pt-0 sm:my-0 sm:align-top sm:inline-block sm:w-2/5 lg:w-1/5 sm:max-h-96"
        :alt="moviePosterAlt"
        :src="movie.poster"
      />
      <div class="inline-block mb-10 align-top sm:w-3/5 lg:w-4/5">
        <!-- MOVIE TITLE -->
        <span
          class="block p-1 mb-5 text-3xl font-bold text-center sm:text-left sm:pt-0 sm:pl-4 sm:mb-0"
        >
          {{ movie.title }}
          <div class="sm:hidden">
            <button
              v-if="movie"
              @click="addToList(movie)"
              class="p-2 mt-5 text-lg text-white rounded bg-button"
            >
              Add to List
            </button>
          </div>
        </span>

        <!-- MOVIE INFO -->
        <div class="p-4">
          <span class="block"
            ><span class="font-semibold">Director:</span>
            {{ movie.director }}</span
          >
          <span class="block">
            <span class="font-semibold">Genre:</span>
            {{ movie.genre }}
          </span>
          <span class="block">
            <span class="font-semibold">Rating:</span>
            {{ movie.rated }}
          </span>
          <span class="block">
            <span class="font-semibold">Year:</span>
            {{ movie.year }}
          </span>
          <span v-if="movie.ratings && movie.ratings.length > 1" class="block">
            <span class="font-semibold">Rotten Tomatoes Score:</span>
            {{ movie.ratings[1].Value }}
          </span>
          <span class="block mt-5">{{ movie.plot }}</span>
        </div>
      </div>
    </div>

    <!-- ADD TO LIST -->
    <div v-if="!mainDisplay" class="mx-4">
      <span class="font-semibold">Currently on these lists:</span>
      <div class="flex justify-between">
        <ul class="">
          <li>List one</li>
          <li>List two</li>
        </ul>

        <div>
          <button
            v-if="movie"
            @click="addToList(movie)"
            class="hidden p-2 text-white rounded sm:block bg-button"
          >
            Add to List
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";
import { Movie } from "@/types/index";
import store from "@/store/index";

export default defineComponent({
  name: "MovieDisplay",
  emits: ["resetMovie"],
  computed: {
    moviePosterAlt(): string {
      return `movie poster for ${this.movie.title}`;
    },
  },
  methods: {
    addToList(movie: Movie): void {
      this.$http.post(`/lists/${this.listId}`, movie).then((response: any) => {
        this.$emit("resetMovie");
        store.commit("updateList", response.data.list);
      });
    },
  },
  props: {
    movie: {
      type: Object as PropType<Movie>,
      required: true,
    },
    mainDisplay: {
      type: Boolean,
      required: false,
    },
    listId: {
      type: Number,
      required: true,
    },
  },
});
</script>
