import React from 'react';
import ReactDOM from 'react-dom';
import {lazy, Suspense} from 'react';
import { BrowserRouter, Routes, Route } from "react-router-dom";

const landgrass = document.getElementById('landgrass');
const App = lazy(() => import('./Application'))

const WrapperApp = () => {
    return (
        <div>
            <Suspense fallback={<div>Loading...</div>}>
                <App />
            </Suspense>
        </div>
    );
}
if (landgrass !== null) {
    ReactDOM.render(<>
        <BrowserRouter>
            <Routes>
                <Route>
                  <Route path="/posts/:id" element={<WrapperApp />} />
                </Route>
            </Routes>
        </BrowserRouter></>,
    landgrass);
}
