<template>
  <div class="p-5 sm:p-10">
    <h1 class="text-3xl font-bold text-left text-gray-700 sm:text-5xl">
      {{ listName }}
    </h1>
  </div>
  <hr class="shadow" />

  <loader v-show="loading" item="movies" />
  <!-- TABS -->
  <div id="tabs" class="mt-5">
    <span
      class="mr-5 underline cursor-pointer hover:bg-blue-400"
      @click="updateTab('list')"
      >List</span
    >
    <span
      class="underline cursor-pointer hover:bg-blue-400"
      @click="updateTab('settings')"
      >Settings</span
    >
  </div>

  <div
    v-show="!loading && selectedTab === 'list'"
    class="mx-5 sm:mx-10 xl:mx-auto max-w-7xl"
  >
    <search-form v-if="currentList.uuid" :listId="currentList.uuid" />
    <movie-list @loaded="updateLoading" />
  </div>

  <!-- SETTINGS TAB -->
  <div
    class="flex flex-col items-center mt-5 bg-gray-300"
    v-show="!loading && selectedTab === 'settings'"
  >
    <div class="flex">
      <label for="is-public" class="mr-2">Public:</label>
      <input
        @change="updateAccess"
        type="checkbox"
        v-model="currentList.isPublic"
        id="is-public"
      />
    </div>

    <div class="flex">
      <span class="mr-2">URL:</span>
      <span>{{ appHost }}/u/{{ identifier }}/l/{{ currentList.slug }}</span>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import SearchForm from "@/components/SearchForm.vue";
import MovieList from "@/components/MovieList.vue";
import Loader from "@/components/Loader.vue";
import store from "@/store/index";
import {AxiosError, AxiosResponse} from "axios";

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
      selectedTab: "list",
      user: { id: 0, name: "", email: "", uuid: "", username: "" },
    };
  },
  computed: {
    currentList() {
      return store.state.currentList;
    },
    appHost() {
      return window.location.origin;
    },
    identifier(): string {
      if (this.user.username) {
        return this.user.username;
      } else {
        return this.user.uuid;
      }
    },
    listName() {
      if (!this.loading) {
        return store.state.currentList.name;
      }
      return "Loading...";
    },
  },
  methods: {
    getUser(): void {
      this.$http
        .get("/api/user")
        .then((response: AxiosResponse) => {
          this.user = {
            id: response.data.id,
            name: response.data.name,
            email: response.data.email,
            username: response.data.username,
            uuid: response.data.uuid,
          };
          this.loading = false;
        })
        .catch((error: AxiosError) => {
          if (error.response?.status === 401) {
            store.commit("updateLogin", false);
            this.$router.push("/login");
          }
        });
    },
    updateLoading(): void {
      this.loading = false;
    },
    updateTab(page: string) {
      this.selectedTab = page;
    },
    updateAccess(): void {
      store.commit("updateListAccess", this.currentList.isPublic);

      this.$http
        .put("/api/lists", this.currentList)
        .catch((error: AxiosError) => {
          if (error.response?.status === 401) {
            store.commit("updateLogin", false);
            this.$router.push("/login");
          }
        });
    },
  },
  mounted() {
    this.getUser();
  },
});
</script>
