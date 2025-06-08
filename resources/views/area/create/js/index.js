// import {Area} from './Area.js';
import {Button} from '../../../components/button/Button.js';

class Index {

    constructor() {
        this.init();
    }

    init() {
        this.addEvent();
    }

    addEvent() {
        new Button(document.querySelector('#area'));
    }
}

document.addEventListener('DOMContentLoaded', function() {
    new Index();
});