<template>
  <div class="p-5 sm:p-10">
    <h1 class="text-left text-3xl text-gray-700 sm:text-5xl font-bold">
      {{ listName }}
    </h1>
  </div>
  <hr class="shadow" />

  <loader v-show="loading" item="movies" />

  <div v-show="!loading" class="text-center mx-5 sm:mx-10 xl:mx-auto max-w-7xl">
    <search-form v-if="currentList.id" :listId="currentList.id" />
    <movie-list @loaded="updateLoading" />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import SearchForm from "@/components/SearchForm.vue";
import MovieList from "@/components/MovieList.vue";
import Loader from "@/components/Loader.vue";
import store from "@/store/index";

export default defineComponent({
  name: "EditList",
  components: {
    SearchForm,
    MovieList,
    Loader,
  },
  data: function () {
    return {
      loading: true,
    };
  },
  computed: {
    currentList() {
      return store.state.currentList;
    },
    listName() {
      if (!this.loading) {
        return store.state.currentList.name;
      }
      return "Loading...";
    },
  },
  methods: {
    updateLoading(): void {
      this.loading = false;
    },
  },
});
</script>
