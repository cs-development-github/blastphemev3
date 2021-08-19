const routes = require('../public/js/fos_js_routes');
import Routing from '../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

class Checkbox {

    /**
     * @param { integer } id 
     * @param {{ route: string, attribute: string }} options 
     */
    constructor(id, options = {}) {
        this.id = id;
        this.options = options;
    }

    toggle() {
        let options = [];
        let attribute = this.options['attribute'];
        console.log(this.options['route']);
        options.push({
            [attribute]: this.id
        });
        const route = Routing.generate(this.options['route'], options[0]);

        fetch(route).then(r => r.status !== 200 ? console.log(r) : null);
    }
}


export function checkbox(id, options = {}) {
    return new Checkbox(id, options).toggle();
}

console.debug('checkbox.js loaded');