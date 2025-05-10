import {Movie, MoviesResponse} from "../types/Movie.ts";

export const fetchMovies = async (): Promise<Movie[]> => {
    try {
        const response = await fetch('/api/movies?order[title]=asc')
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        let data: MoviesResponse = await response.json()
        return data.member
    } catch (err) {
        console.error('Error fetching movies:', err)
        return []
    }
};
