/**
 * Options pour une modale :
 *      -> title: string
 *      -> route: string
 *      -> isBackdrop: boolean (par défaut il est à défini à `true`)*
 *      -> body:
 *      -> footer:
 *      -> autoForm: true par défaut
 *      -> autoClose: true par défaut
 *      -> size: null par défaut
 *
 * isBackdrop (gestion du clique à l'extérieur de la modale) :
 *      -> l'option `static` pour que la modale ne se ferme pas
 *      -> l'option `true` pour que la modale se ferme
 *
 *  size (taille de la modale) :
 *      https://getbootstrap.com/docs/4.5/components/modal/#optional-sizes
 *      -> small (.modal-sm / 300px)
 *      -> default (none / 500px)
 *      -> large (.modal-lg / 800px)
 *      -> extraLarge (.modal-xl / 1140px)
 */
class Modal {

    /**
     * @param { string } id
     * @param {{ title: string, route: string, ?isBackdrop: boolean, ?footer: any, ?autoForm:boolean, ?autoClose:boolean, ?size: string }} options
     * @param callback
     * @param $modal
     */
    constructor(id, options = {}, callback = null, $modal = null) {
        if (options.isBackdrop === undefined) options.isBackdrop = true;
        if (options.autoForm === undefined) options.autoForm = true;
        if (options.autoClose === undefined) options.autoClose = true;

        if (options.size === undefined) options.size = 'default';

        this.id = id;
        this.options = options;
        this.callback = callback;
        this.$modal = $modal;
    }

    open() {
        this.create();
        this.show();

        return this;
    }

    toggle() {
        this.$modal.modal('toggle');
    }

    /**
     * Options des modals bootstrap.
     * @returns {*}
     */
    show() {
        this.$modal.modal('show');
    }

    hide() {
        this.$modal.modal('hide');
    }

    handleUpdate() {
        this.$modal.modal('handleUpdate');
    }

    dispose() {
        this.$modal.modal('dispose');
    }

    /**
     * Retourne la classe boostrap de la modale.
     * @returns {*}
     */
    getSizeClass() {
        const sizes = {
            small: 'modal-sm',
            default: ' ',
            large: 'modal-lg',
            extraLarge: 'modal-xl'
        };

        return sizes[this.options['size']]
    }

    /**
     * Création de la modale.
     */
    createDom() {
        const body = this.options['body'] ? this.options['body'] : '';
        const footer = this.options['footer'] ? this.options['footer'] : '';

        const template = `<div class="modal fade" id="${this.id}" tabindex="-1" aria-labelledby="${this.id}" role="dialog" aria-hidden="true" style="display: none;">
          <div class="modal-dialog ${this.getSizeClass(this.options['size'])} modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="${this.id}">${this.options['title']}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">${body}</div>
              <div class="modal-footer">${footer}</div>
            </div>
          </div>
        </div>`;

        document.body.insertAdjacentHTML('beforeend', template);

        this.$modal = $('#' + this.id);
    }

    /**
     * @returns {*|jQuery|HTMLElement}
     */
    create() {
        if ($('#' + this.id).length) {
            $('#' + this.id).modal('dispose');
            $('#' + this.id).remove();
        }

        this.createDom();

        this.$modal.on('show.bs.modal', () => {
            this.load();
        });

        this.$modal.on('hidden.bs.modal', (event) => {
            event.target.remove();
        })

        this.$modal.modal({
            backdrop: this.options['isBackdrop'] ? 'static' : true,
            keyboard: false,
            show: false
        });

        return this;
    }

    load(newRoute = null) {
        const route = newRoute ? newRoute : this.options['route'];
        if (!route) {
            return;
        }

        const $modalBody = this.$modal.find('.modal-body');

        const spinner = `
            <div class="d-flex justify-content-center">
                <div class="spinner-border m-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

        $modalBody.html(spinner);

        const $footer = this.$modal.find('.modal-footer');
        if (!this.options['footer']) {
            $footer.html('');
        }

        $footer.hide();

        fetch(route)
            .then(response => response.text())
            .then(data => {
                this.setData(data);
            })
        ;

        this.options['route'] = route;
    }

    /**
     * @param data
     */
    setData(data) {
        if (this.options['autoClose'] && data.trim() === 'true') {
            this.$modal.modal('hide');
            location.reload();
            return;
        }

        const $modalBody = this.$modal.find('.modal-body');
        $modalBody.html(data);

        if (data.search('<\s*form.*>') !== -1) {
            this.initForm();
        }

        const $footer = this.$modal.find('.modal-footer');

        if ($footer.html()) {
            this.$modal.find('.modal-footer').show();
        }

        this.$modal.animate({scrollTop: 0}, 'slow');

        if (this.callback) {
            this.callback(this, data);
        }
    }

    initForm() {
        const $form = this.$modal.find('form');

        $form.on('submit', (event) => {
            event.preventDefault();

            const data = new FormData(event.target); // event.target is the form

            const keyToDelete = [];
            for (const [key, value] of data.entries()) {
                if (value instanceof File && value.size === 0) {
                    keyToDelete.push(key);
                }
            }
            keyToDelete.forEach(key => data.delete(key));

            // send form
            fetch(event.target.action, {
                method: $form.attr('method'),
                body: data
            })
                .then(response => response.text())
                .then(data => this.setData(data))
                .catch((error) => {
                    alert(error);
                })
            ;
        });

        this.initFormButtons();
    }

    initFormButtons() {
        const $footer = this.$modal.find('.modal-footer');
        if (!$footer.html() && this.options.autoForm) {
            const defaultFooter = `
                <button type="button" class="btn btn-secondary form-cancel" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary form-save">Enregistrer</button>
            `;
            $footer.html(defaultFooter);
        }

        this.$modal.find('.form-save').click((event) => {
            event.stopImmediatePropagation();
            this.$modal.find('form').submit();
            return false;
        });
    }
}

export function openModal(id, options = {}, callback = null) {
    return new Modal(id, options, callback).open();
}

console.debug('modal.js loaded');
