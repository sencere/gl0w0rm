import React from 'react';
import ReactDOM from 'react-dom';
import Application from './Application';

var landgrass = document.getElementById('landgrass');

if (landgrass === null) {
    ReactDOM.render(<Application />, landgrass);
}
