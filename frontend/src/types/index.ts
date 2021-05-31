export interface List {
  id: number;
  name: string;
  movieCount: number;
  movies: Movie[];
}

export interface Movie {
  id: number;
  title: string;
  year: number;
  rating: string;
  genre: string;
  director: string;
  actors: string;
  plot: string;
  reviews: Review[];
  poster: string;
  showings: Showing[];
  next_showing: Showing;
}

export interface Review {
  source: string;
  value: string;
}

export interface Showing {
  id: number;
  movie_id: number;
  show_time: string;
  owner: number;
}

export interface User {
  id: number;
  name: string;
  email: string;
}
