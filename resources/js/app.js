import './bootstrap';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

import.meta.glob(['../images/**', '../favicon/**']);

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
