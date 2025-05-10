import {StrictMode} from 'react'
import {createRoot} from 'react-dom/client'

import {BrowserRouter, Route, Routes} from "react-router";
import {AllowedRoutes} from "./types/Routes.ts";
import Movies from './pages/movies/Movies.tsx'
import "./index.css"
import Login, {AllowedVariants} from "./pages/login/Login.tsx";
import Dashboard from "./pages/dashboard/Dashboard.tsx";
import Register from "./pages/register/Register.tsx";
import Messages from "./components/messages/Messages.tsx";

// Updates the scroll position in the CSS variable --scrollY for global use, for example in Messages component
window.addEventListener("scroll", () => {
    document.documentElement.style.setProperty("--scrollY", `${window.scrollY}px`);
});


createRoot(document.getElementById('root')!).render(
    <StrictMode>
        <BrowserRouter>
            <Messages/>
            <Routes>
                {/*<Route path="/" element={<Navigate to="/movies" replace />} />*/}
                <Route path={AllowedRoutes.Home} element={<Movies/>}/>
                <Route path={AllowedRoutes.SelectPlace} element={<div> Select place page </div>}/>
                <Route path={AllowedRoutes.Login} element={<Login variant={AllowedVariants.Client}/>}/>
                <Route path={AllowedRoutes.Register} element={<Register/>}/>
                <Route path={AllowedRoutes.WorkerLogin} element={<Login variant={AllowedVariants.Worker}/>}/>
                <Route path={AllowedRoutes.Dashboard} element={<Dashboard/>}/>
                <Route path="*" element={<div style={{color: "white"}}>404 - Page Not Found</div>}/>
            </Routes>
        </BrowserRouter>
    </StrictMode>,
)
