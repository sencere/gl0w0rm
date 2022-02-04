import React from 'react';
import ReactDOM from 'react-dom';
import Application from './Application';
import Create from './Create';

const landgrass = document.getElementById('landgrass');
if (landgrass !== null) {
    ReactDOM.render(<Application />, landgrass);
}

const create = document.getElementById('create');
if (create !== null) {
    ReactDOM.render(<Create />, create);
}
