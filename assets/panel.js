import { openModal } from './modal';
global.openModal = openModal;

import './styles/panel.scss';


import '@popperjs/core';
require('bootstrap');

// start the Stimulus application
import './bootstrap';
import './main';
import './panel';
import './selectTag';
import './paragraphe';


console.debug('panel.js loaded');