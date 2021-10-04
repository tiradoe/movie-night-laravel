<template>
  <h1 class="p-5 text-3xl font-bold text-left sm:text-5xl sm:p-10">Schedule</h1>
  <hr class="mb-10 shadow" />

  <loader v-if="loading" item="schedule" />
  <div v-else>
    <!-- TABS -->
    <div id="tabs" class="mt-5">
      <span
        class="mr-5 underline cursor-pointer hover:bg-blue-400"
        @click="updateTab('schedule')"
        >Schedule</span
      >
      <span
        class="underline cursor-pointer hover:bg-blue-400"
        @click="updateTab('settings')"
        >Settings</span
      >
    </div>

    <div
      v-if="showings.length > 0 || previousShowings.length > 0"
      class="sm:m-10"
    >
      <div v-show="!loading && selectedTab === 'schedule'">
        <!-- UPCOMING SHOWINGS -->
        <h2 class="m-5 font-semibold text-left sm:m-0 sm:mb-5">
          Upcoming Showings
        </h2>
        <ul
          v-if="showings.length"
          class="flex flex-col bg-gray-300 rounded sm:px-5"
        >
          <li
            :key="showing.id"
            v-for="showing in showings"
            class="flex m-5 sm:m-10"
          >
            <span class="w-1/2 text-left">{{ showing.movie.title }}</span>
            <span class="w-1/2 text-right sm:text-left">{{
              prettyDate(showing.show_time)
            }}</span>
            <div @click="deleteShowing(showing.id)">
              <font-awesome-icon
                class="ml-5 cursor-pointer hover:text-red-700"
                icon="trash-alt"
              />
            </div>
          </li>
        </ul>
        <span v-else class="block text-left">Nothing scheduled</span>

        <!-- PREVIOUS SHOWINGS -->
        <details class="flex pt-10 text-left sm:">
          <summary class="flex flex-row list-none cursor-pointer">
            <h2
              v-if="previousShowings.length > 0"
              class="flex flex-row m-5 font-semibold underline sm:m-0 sm:mb-5"
            >
              Previous Showings
            </h2>
          </summary>
          <ul
            v-if="previousShowings.length > 0"
            class="flex flex-col bg-gray-300 rounded sm:px-5"
          >
            <li
              :key="showing.id"
              v-for="showing in previousShowings"
              class="flex m-5 sm:m-10"
            >
              <span class="w-1/2 text-left">{{ showing.movie.title }}</span>
              <span class="w-1/2 text-right sm:text-left">{{
                prettyDate(showing.show_time)
              }}</span>
              <font-awesome-icon
                @click="deleteShowing(showing.id)"
                class="ml-5 cursor-pointer hover:text-red-700"
                icon="trash-alt"
              />
            </li>
          </ul>
        </details>
      </div>

      <!-- SETTINGS TAB -->
      <div
        class="flex flex-col items-center mt-5 bg-gray-300"
        v-show="!loading && selectedTab === 'settings'"
      >
        <div class="flex">
          <label for="is-public" class="mr-2">Public:</label>
          <input
            v-if="schedule"
            v-show="schedule"
            @change="updateAccess"
            type="checkbox"
            v-model="schedule.isPublic"
            id="is-public"
          />
        </div>
        <div class="flex">
          <span class="mr-2">URL:</span>
          <span>{{ appHost }}/u/{{ identifier }}/s/{{ schedule.slug }}</span>
        </div>
      </div>
    </div>
    <div v-else class="p-10 bg-gray-300 rounded shadow sm:m-10">
      <movie-quote
        actor="Tommy Wiseau"
        movie="The Room"
        quote="Anyway, how's
    your sex life?"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { AxiosError, AxiosResponse } from "axios";
import { defineComponent } from "vue";
import MovieQuote from "@/components/MovieQuote.vue";
import Loader from "@/components/Loader.vue";
import store from "@/store/index";
import { Showing } from "@/types/index";
import { Schedule, User } from "@/types/index";

let previousShowings: Showing[] = [];
let showings: Showing[] = [];
let schedule: Schedule;

export default defineComponent({
  name: "Schedule",
  components: { MovieQuote, Loader },
  computed: {
    appHost(): string {
      return window.location.origin;
    },
    identifier(): string {
      if (typeof this.user.username !== "undefined") {
        return this.user.username;
      } else {
        return this.user.uuid;
      }
    },
  },
  data: function () {
    return {
      schedule: schedule,
      showings: showings,
      previousShowings: previousShowings,
      loading: true,
      selectedTab: "schedule",
      user: { id: 0, name: "", email: "", uuid: "", username: "" },
    };
  },
  methods: {
    prettyDate(showTime: Date): string {
      const formatted = new Date(showTime).toLocaleString("en-US", {
        month: "2-digit",
        day: "2-digit",
        year: "2-digit",
      });

      return formatted;
    },
    deleteShowing(showingId: number): void {
      if (confirm("Delete showing?") === true) {
        this.$http
          .delete(`/api/showings/${showingId}`)
          .then(this.getSchedule());
      }
    },
    getSchedule(): void {
      this.$http
        .get(`/api/schedules`)
        .then((response: AxiosResponse) => {
          this.previousShowings = [];
          this.showings = [];

          response.data.showings.forEach((showing: Showing) => {
            const show_time = new Date(showing.show_time.toString());
            let today = new Date();
            today.setHours(0, 0, 0, 0);

            if (show_time.getTime() < today.getTime()) {
              this.previousShowings.push(showing);
            } else {
              this.schedule = response.data;
              this.showings.push(showing);
            }
          });

          this.loading = false;
        })
        .catch((error: AxiosError) => {
          if (error.response?.status === 401) {
            store.commit("updateLogin", false);
            this.$router.push("/login");
          }
        });
    },
    getUser(): void {
      this.$http
        .get("/api/user")
        .then((response: AxiosResponse) => {
          let userData: User = {
            id: response.data.id,
            name: response.data.name,
            email: response.data.email,
            username: response.data.username,
            uuid: response.data.uuid,
          };

          this.user = userData;
          this.loading = false;
        })
        .catch((error: AxiosError) => {
          if (error.response?.status === 401) {
            store.commit("updateLogin", false);
            this.$router.push("/login");
          }
        });
    },
    updateAccess(): void {
      this.$http
        .put("/api/schedules", this.schedule)
        .catch((error: AxiosError) => {
          if (error.response?.status === 401) {
            store.commit("updateLogin", false);
            this.$router.push("/login");
          }
        });
    },
    updateTab(page: string) {
      this.selectedTab = page;
    },
  },
  mounted() {
    this.getSchedule();
    this.getUser();
  },
});
</script>
