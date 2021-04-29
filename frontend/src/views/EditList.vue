<template>
  <div class="p-5 sm:p-10">
    <h1 class="text-left text-3xl text-gray-700 sm:text-5xl font-bold">
      {{ currentList.name }}
    </h1>
  </div>
  <hr class="shadow" />
  <div id="movie-search" class="sm:m-10">
    <search-form v-if="currentList.id" class="" :listId="currentList.id" />
  </div>
  <movie-list />
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import SearchForm from "@/components/SearchForm.vue";
import MovieList from "@/components/MovieList.vue";
import store from "@/store/index";

export default defineComponent({
  name: "EditList",
  components: {
    SearchForm,
    MovieList,
  },
  computed: {
    currentList() {
      return store.state.currentList;
    },
  },
});
</script>
