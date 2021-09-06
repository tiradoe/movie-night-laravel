export interface List {
  id: number;
  movieCount: number;
  movies: Movie[];
  name: string;
}

export interface Movie {
  actors: string;
  director: string;
  genre: string;
  id: number;
  plot: string;
  poster: string;
  rating: string;
  reviews: Review[];
  showings: Showing[];
  title: string;
  year: number;
}

export interface Review {
  source: string;
  value: string;
}

export interface Showing {
  id: number;
  movie_id: number;
  owner: number;
  show_time: string;
}

export interface User {
  id: number;
  email: string;
  name: string;
}
