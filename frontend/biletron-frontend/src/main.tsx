import {StrictMode} from 'react'
import {createRoot} from 'react-dom/client'

import {BrowserRouter, Route, Routes} from "react-router";
import {AllowedRoutes} from "./types/Routes.ts";
import Movies from './pages/movies/Movies.tsx'
import "./index.css"
import Login, {AllowedVariants} from "./pages/login/Login.tsx";
import Register from "./pages/register/Register.tsx";

createRoot(document.getElementById('root')!).render(
    <StrictMode>
        <BrowserRouter>
            <Routes>
                {/*<Route path="/" element={<Navigate to="/movies" replace />} />*/}
                <Route path={AllowedRoutes.Home} element={<Movies/>}/>
                <Route path={AllowedRoutes.Login} element={<Login variant={AllowedVariants.Client}/>}/>
                <Route path={AllowedRoutes.Register} element={<Register/>}/>
                <Route path="*" element={<div style={{color: "white"}}>404 - Page Not Found</div>}/>
            </Routes>
        </BrowserRouter>
    </StrictMode>,
)
