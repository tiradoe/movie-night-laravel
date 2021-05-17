<template>
  <div id="movie-search" class="">
    <h1 class="p-5 text-2xl text-3xl font-bold text-left sm:p-10 sm:text-5xl">
      Lists
    </h1>
    <hr class="mb-10 shadow" />

    <!-- NEW LIST INPUT -->
    <input
      class="p-2 rounded rounded-r-none shadow border-r-none focus:shadow-outline"
      type="text"
      placeholder="List Name"
      aria-placeholder="Enter List Name"
      v-model="listName"
      @keyup.enter="addList"
    />
    <div
      class="inline-block p-2 mt-5 mb-10 text-white rounded rounded-l-none shadow cursor-pointer bg-button border-l-none"
      @click="addList()"
    >
      Create list
    </div>

    <!-- LISTS -->
    <ul v-if="lists.length > 0" class="bg-white rounded shadow sm:m-10">
      <li
        :key="list.id"
        v-for="list in lists"
        class="flex justify-around p-5 even:bg-gray-200"
      >
        <!-- LIST NAME -->
        <span class="flex-1 text-left">{{ list.name }}</span>

        <!-- MOVIE COUNT -->
        <span class="flex-1 text-left">{{ list.movies_count }} movies </span>

        <!-- EDIT AND DELETE -->
        <div class="text-left">
          <router-link :to="`/lists/edit/${list.id}`">
            <font-awesome-icon
              class="mx-5 hover:text-yellow-600"
              icon="pencil-alt"
            />
          </router-link>
          <font-awesome-icon
            @click="deleteList(list.id)"
            class="mx-1 cursor-pointer hover:text-red-600"
            icon="trash-alt"
          />
        </div>
      </li>
    </ul>

    <div v-else class="p-5 bg-white rounded shadow sm:mx-10">
      <movie-quote
        quote="If <i>this</i> is empty, <i>this</i> doesn't matter."
        actor="Tom Cruise"
        movie="Jerry Maguire"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import { List } from "@/types/index";
import MovieQuote from "@/components/MovieQuote.vue";
import { AxiosError, AxiosResponse } from "axios";
import store from "@/store/index";

const lists: List[] = [];

export default defineComponent({
  name: "Lists",
  components: { MovieQuote },
  data: function () {
    return {
      listName: "",
      lists: lists,
    };
  },
  mounted() {
    this.getLists();
  },
  methods: {
    addList() {
      if (this.listName !== "") {
        this.$http
          .post("/api/lists", {
            name: this.listName,
            isPublic: "false",
          })
          .then((response: AxiosResponse) => {
            if (response.status === 200) {
              this.lists = response.data.lists;
            } else {
              alert("Could not create list");
            }
          })
          .catch((error: AxiosError) => {
            if (error.response?.status === 401) {
              store.commit("updateLogin", false);
              this.$router.push("/login");
            }
          });

        this.listName = "";
      } else {
        alert("Please enter list name.");
      }
    },
    deleteList(id: number) {
      this.$http.delete(`/api/lists/${id}`).then((response: AxiosResponse) => {
        if (response.status === 200) {
          this.getLists();
        } else {
          alert("Could not delete list");
        }
      });
    },
    getLists() {
      this.$http
        .get("/api/lists")
        .then((response: AxiosResponse) => (this.lists = response.data))
        .catch((error: AxiosError) => {
          if (error.response?.status === 401) {
            store.commit("updateLogin", false);
            this.$router.push("/login");
          }
        });
    },
  },
});
</script>
