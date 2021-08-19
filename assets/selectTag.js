function initForm() {
    collection = document.querySelector("#tags");
    span = collection.querySelector("span");
    if (span) {
        boutonAjout = document.createElement("button");
        boutonAjout.type = "button";
        boutonAjout.className = "ajout-tag btn-primary mt-2";
        boutonAjout.innerText = "Ajouter un tag";
        let nouveauBouton = span.append(boutonAjout);
        collection.dataset.index = collection.querySelectorAll("input").length;
        boutonAjout.addEventListener("click", function() {
            addButton(collection, nouveauBouton);
        });
    }

}

function addButton(collection, nouveauBouton) {
    let prototype = collection.dataset.prototype;
    let index = collection.dataset.index;
    prototype = prototype.replace(/__name__/g, index);
    let content = document.createElement("html");
    content.innerHTML = prototype;
    let newForm = content.querySelector("div");
    let boutonSuppr = document.createElement("button");
    boutonSuppr.type = "button";
    boutonSuppr.className = "btn btn-danger mt-2";
    boutonSuppr.id = "delete-tag-" + index;
    boutonSuppr.innerText = "Supprimer ce tag";
    newForm.append(boutonSuppr);
    collection.dataset.index++;
    let boutonAjout = collection.querySelector(".ajout-tag");
    span.insertBefore(newForm, boutonAjout);
    boutonSuppr.addEventListener("click", function() {
        this.previousElementSibling.parentElement.remove();
    })
}

global.initForm = initForm;
console.debug('selectTag.js loaded');