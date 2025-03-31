import { useEffect, useState } from 'react';
import './App.css';

interface Movie {
  id_movie: number;
  title: string;
  original_title: string;
  duration: string;
  description: string;
  poster: string;
  id_language: number;
  id_dubbing: number;
  id_subtitles: number;
}

function App() {
  const [movies, setMovies] = useState<Movie[]>([]);
  const [loading, setLoading] = useState(true);
  const [formData, setFormData] = useState({
    title: '',
    original_title: '',
    duration: '',
    description: '',
    poster: '',
    id_language: 1,
    id_dubbing: 2,
    id_subtitles: 2,
  });

  const fetchMovies = () => {
    setLoading(true);
    fetch('/api/movies')
      .then((res) => {
        if (!res.ok) {
          throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
      })
      .then((data) => {
        setMovies(data);
        setLoading(false);
      })
      .catch((err) => {
        console.error('Error fetching movies:', err);
        setLoading(false);
      });
  };

  useEffect(() => {
    fetchMovies();
  }, []);

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleAddMovie = (e: React.FormEvent) => {
    e.preventDefault();
    fetch('/api/movies', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formData),
    })
      .then((res) => {
        if (res.ok) {
          fetchMovies(); // Refresh the movie list
          setFormData({
            title: '',
            original_title: '',
            duration: '',
            description: '',
            poster: '',
            id_language: 1,
            id_dubbing: 2,
            id_subtitles: 2
          });
        } else {
          console.error('Failed to add movie');
        }
      })
      .catch((err) => console.error('Error adding movie:', err));
  };

  return (
    <div className="wrapper">
      <div className="pane left">
        <h2>Add Movie</h2>
        <form onSubmit={handleAddMovie}>
          <label>
            Title:
            <input type="text" name="title" value={formData.title} onChange={handleInputChange} required />
          </label>
          <label>
            Original Title:
            <input type="text" name="original_title" value={formData.original_title} onChange={handleInputChange} required />
          </label>
          <label>
            Duration:
            <input
              type="time"
              step="1"
              name="duration"
              value={formData.duration}
              onChange={handleInputChange}
              required
            />
          </label>
          <label>
            Description:
            <textarea name="description" value={formData.description} onChange={handleInputChange} required />
          </label>
          <label>
            Poster filename:
            <input type="text" name="poster" value={formData.poster} onChange={handleInputChange} required />
          </label>
          <label>
            Language:
            <select name="id_language" value={formData.id_language} onChange={handleInputChange} required>
              <option value="2">Polish</option>
              <option value="1">English</option>
            </select>
          </label>
          <label>
            Dubbing:
            <select name="id_dubbing" value={formData.id_dubbing} onChange={handleInputChange} required>
              <option value="2">Polish</option>
              <option value="1">English</option>
            </select>
          </label>
          <label>
            Subtitles:
            <select name="id_subtitles" value={formData.id_subtitles} onChange={handleInputChange} required>
              <option value="2">Polish</option>
              <option value="1">English</option>
            </select>
          </label>
          <button type="submit">Add Movie</button>
        </form>
      </div>
      <div className="pane right">
        <div className="header">
          <h2>Movies</h2>
          <button onClick={fetchMovies}>Refresh</button>
        </div>

        {loading ? (
          <p>Loading movies...</p>
        ) : (
          <ul>
            {movies.map((movie) => (
              <li key={movie.id_movie}>
                <a href={`/api/movies/${movie.id_movie}`}>{movie.title}</a>
                <button
                  onClick={() => {
                    fetch(`/api/movies/${movie.id_movie}`, {
                      method: 'DELETE',
                      headers: { 'Content-Type': 'application/json' },
                    })
                      .then((res) => {
                        if (res.ok) {
                          fetchMovies(); // Refresh the movie list
                        } else {
                          console.error('Failed to delete movie');
                        }
                      })
                      .catch((err) => console.error('Error deleting movie:', err));
                  }}
                >
                  Delete
                </button>
              </li>
            ))}
          </ul>
        )}
      </div>
    </div>
  );
}

export default App;