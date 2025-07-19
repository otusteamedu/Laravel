import { Fibonachi } from "./Fibonachi";

export class Index {
    
    constructor() {
        this.init();
    }

    init() {
        new Fibonachi();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Index();
});