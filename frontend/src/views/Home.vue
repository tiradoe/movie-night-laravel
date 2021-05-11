<template>
  <h1 class="p-5 text-3xl font-bold text-left sm:text-5xl sm:p-10">
    {{ heading || "Nothing Scheduled" }}
  </h1>
  <hr class="mb-10 shadow" />
  <MovieDisplay
    class="sm:mx-10"
    v-if="movie"
    :movie="movie"
    :mainDisplay="true"
    :listId="listId"
  />
  <p v-else class="p-10 bg-white rounded shadow sm:m-10">
    <movie-quote
      actor="Heath Ledger"
      movie="The Dark Knight"
      quote="Do I really
    look like a guy with a plan? <br />You know what I am? I'm a dog chasing
    cars. <br />I wouldn't know what to do with one if I caught it! You know, I
    just... <i>do</i> things."
    />
  </p>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import MovieDisplay from "@/components/MovieDisplay.vue";
import { Movie } from "@/types/index";
import MovieQuote from "@/components/MovieQuote.vue";

const movie: Movie | null = null;

export default defineComponent({
  name: "Home",
  components: {
    MovieDisplay,
    MovieQuote,
  },
  data: function () {
    return {
      movie: movie,
      listId: 0,
      heading: "",
    };
  },
  computed: {},
  mounted() {
    this.$http
      .get("/api/showings?next=1")
      .then((response: any) => {
        const showTime = new Date(response.data.showing.show_time);
        this.getHeading(showTime);

        this.movie = response.data.movie;
      })
      .catch((error: Error) => {
        console.error(error.message);
      });
  },
  methods: {
    getHeading(showTime: Date): void {
      const today = new Date();
      const date1 = `${
        showTime.getMonth() + 1
      }/${showTime.getDate()}/${showTime.getFullYear()}`;
      const date2 = `${
        today.getMonth() + 1
      }/${today.getDate()}/${today.getFullYear()}`;

      if (date1 === date2) {
        this.heading = "TONIGHT";
      } else {
        this.heading = `Next up: ${date1}`;
      }
    },
  },
});
</script>
