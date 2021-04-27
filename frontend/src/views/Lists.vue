<template>
  <!--
    Only show the new movie form if the movie can't be found
    using the API.  Otherwise show the details and let user add them.
  -->
  <div>
    <div id="movie-search" class="m-5">
      <h1 class="text-2xl font-bold text-left">Lists</h1>
      <ul v-if="lists.length > 0" class="py-5">
        <li
          :key="list.id"
          v-for="list in lists"
          class="flex justify-around mb-5"
        >
          <!-- LIST NAME -->
          <span class="flex-1 text-left">{{ list.name }}</span>

          <!-- MOVIE COUNT -->
          <span class="flex-1 text-left">{{ list.movies_count }} movies </span>

          <!-- EDIT AND DELETE -->
          <div class="text-left">
            <router-link :to="`/lists/edit/${list.id}`">
              <font-awesome-icon class="mx-1" icon="pencil-alt" />
            </router-link>
            <font-awesome-icon
              @click="deleteList(list.id)"
              class="mx-1 cursor-pointer"
              icon="trash-alt"
            />
          </div>
        </li>
      </ul>

      <div v-else>
        <div id="movie-quote">
          <span id="quote" class="block"
            >“If this is empty, this doesn't matter.”</span
          >
          <span id="quote-actor" class="inline-block">Tom Cruise - </span>
          <span id="quote-movie-title" class="inline-block"
            >&nbsp;Jerry Maguire</span
          >
        </div>
      </div>

      <input
        class="p-2 shadow rounded rounded-r-none border-r-none focus:outline-none focus:shadow-outline"
        type="text"
        placeholder="List Name"
        aria-placeholder="Enter List Name"
        v-model="listName"
        @keyup.enter="addList"
      />
      <div
        class="inline-block shadow p-2 mt-5 text-white bg-button rounded rounded-l-none cursor-pointer border-l-none"
        @click="addList()"
      >
        Create list
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import { List } from "@/types/index";

const lists: List[] = [];

export default defineComponent({
  name: "Lists",
  components: {},
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
          .post("/lists", {
            name: this.listName,
            isPublic: false,
            owner: 1,
          })
          .then(() => {
            this.$http.get("/lists").then((response: any) => {
              if (response.status === 200) {
                this.getLists();
              } else {
                alert("Could not create list");
              }
            });
          });

        this.listName = "";
      } else {
        alert("Please enter list name.");
      }
    },
    deleteList(id: number) {
      this.$http.delete(`/lists/${id}`).then((response: any) => {
        if (response.status === 200) {
          this.getLists();
        } else {
          alert("Could not delete list");
        }
      });
    },
    getLists() {
      this.$http
        .get("/lists")
        .then((response: any) => (this.lists = response.data));
    },
  },
});
</script>
