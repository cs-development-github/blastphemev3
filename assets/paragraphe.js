function getPhoto() {
    Array.prototype.forEach.call(document.querySelectorAll(".file-upload__button"), function(button) {
        const hiddenInput = button.parentElement.querySelector(".file-upload__input");
        const label = button.parentElement.querySelector(".file-upload__label");
        const defaultLabelText = "Aucun fichier.";

        label.textContent = defaultLabelText;
        label.title = defaultLabelText;

        button.addEventListener("click", function() {
            hiddenInput.click();
        });

        hiddenInput.addEventListener("change", function() {
            const filenameList = Array.prototype.map.call(hiddenInput.files, function(file) {
                return file.name;
            });

            label.textContent = filenameList.join(", ") || defaultLabelText;
            label.title = label.textContent;
        });
    });
}

function nbParagraphe() {
    var parent1 = document.getElementById('para');
    var sectionPageRemove = document.getElementById('panelAddPara');

    function clear() {
        parent1.querySelectorAll('*').forEach(n => n.remove());
        sectionPageRemove.removeChild(sectionPageRemove.lastChild);
    }
    clear();

    var nbParagraphe = document.getElementById('nbParagraphe').value;
    var div = document.getElementById("para");
    var input = document.createElement("textarea");
    var inputlink = document.createElement("input");
    var selectTypeLien = document.createElement("select");
    var inputInterview = document.createElement("select");

    input.maxLength = "15000";
    input.cols = "12";
    input.rows = "12";

    for (var i = 1; i <= nbParagraphe; i++) {
        var divItemPara = document.createElement("div");
        divItemPara.setAttribute("id", "itemPara" + i);
        div.appendChild(divItemPara.cloneNode(true));
    }

    var count = 1;

    while (count <= nbParagraphe) {


        var divItem = document.getElementById('itemPara' + count);
        divItem.setAttribute("class", "itemPara");


        input.placeholder = "Paragraphe " + count;
        input.name = "inputParagraphe[" + count + "]";
        divItem.appendChild(input.cloneNode(true));



        inputlink.placeholder = "Liens pour le paragraphe " + count;
        inputlink.name = "inputLiens[" + count + "]";
        divItem.appendChild(inputlink.cloneNode(true));


        selectTypeLien.setAttribute("id", "customLink" + count);
        selectTypeLien.setAttribute("name", "customLinkInput[" + count + "]");
        selectTypeLien.setAttribute("class", "inputSelectLien");
        divItem.appendChild(selectTypeLien.cloneNode(true));

        inputInterview.setAttribute("id", "inputInterview" + count);
        inputInterview.setAttribute("name", "inputInterview[" + count + "]");
        inputInterview.setAttribute("class", "inputInterview");
        divItem.appendChild(inputInterview.cloneNode(true));


        var divItemPhoto = document.createElement("div");
        divItemPhoto.setAttribute("id", "itemPhoto" + count);
        divItem.appendChild(divItemPhoto.cloneNode(true));
        divItemPhoto.setAttribute("class", "chooseFile");

        count += 1;
    }

    var inputPhoto = document.createElement("input");
    inputPhoto.setAttribute("class", "file-upload__input");
    inputPhoto.setAttribute("id", "real-file");
    inputPhoto.setAttribute("hidden", "hidden");
    inputPhoto.setAttribute("type", "file");

    var buttonPhoto = document.createElement("button");
    buttonPhoto.setAttribute("class", "file-upload__button");
    buttonPhoto.setAttribute("id", "custom-btn");
    buttonPhoto.setAttribute("type", "button");

    var spanPhoto = document.createElement("span");
    spanPhoto.setAttribute("class", "file-upload__label");
    spanPhoto.setAttribute("id", "custom-text");

    var selectPhoto = document.createElement("select");
    selectPhoto.setAttribute("id", "custom-text" + count);

    count = 1;

    while (count <= nbParagraphe) {

        var divItemPhoto = document.getElementById('itemPhoto' + count);
        divItemPhoto.setAttribute("class", "chooseFile");

        inputPhoto.setAttribute("name", "photo" + count);
        divItemPhoto.appendChild(inputPhoto.cloneNode(true));

        buttonPhoto.innerHTML = "Photo " + count;
        divItemPhoto.appendChild(buttonPhoto.cloneNode(true));

        divItemPhoto.appendChild(spanPhoto.cloneNode(true));

        selectPhoto.setAttribute("id", "custom-text" + count);
        selectPhoto.setAttribute("name", "inputPosition" + count);
        divItemPhoto.appendChild(selectPhoto.cloneNode(true));

        count += 1;
    }

    var selectOption1 = document.createElement("option");
    selectOption1.setAttribute("value", "0");
    var textOption1 = document.createTextNode(" -- Position -- ")

    selectOption1.appendChild(textOption1);

    var selectOption2 = document.createElement("option");
    selectOption2.setAttribute("value", "1");
    var textOption2 = document.createTextNode("Droite")
    selectOption2.appendChild(textOption2);

    var selectOption3 = document.createElement("option");
    selectOption3.setAttribute("value", "2");
    var textOption3 = document.createTextNode("Gauche")
    selectOption3.appendChild(textOption3);

    var selectOption4 = document.createElement("option");
    selectOption4.setAttribute("value", "3");
    var textOption4 = document.createTextNode("Que la photo")
    selectOption4.appendChild(textOption4);



    var selectLinkOption1 = document.createElement("option");
    selectLinkOption1.setAttribute("value", "0");
    var textLinkOption1 = document.createTextNode(" -- Autre -- ")
    selectLinkOption1.appendChild(textLinkOption1);

    var selectLinkOption2 = document.createElement("option");
    selectLinkOption2.setAttribute("value", "1");
    var textLinkOption2 = document.createTextNode("Youtube")
    selectLinkOption2.appendChild(textLinkOption2);

    //Interview    
    var selectInterview1 = document.createElement("option");
    selectInterview1.setAttribute("value", "0");
    var textselectInterview1 = document.createTextNode(" -- Interview -- ")
    selectInterview1.appendChild(textselectInterview1);

    var selectInterview2 = document.createElement("option");
    selectInterview2.setAttribute("value", "1");
    var textselectInterview2 = document.createTextNode("Question")
    selectInterview2.appendChild(textselectInterview2);

    var selectInterview3 = document.createElement("option");
    selectInterview3.setAttribute("value", "2");
    var textselectInterview3 = document.createTextNode("Réponse")
    selectInterview3.appendChild(textselectInterview3);

    count = 1;

    while (count <= nbParagraphe) {

        var getSelectOption = document.getElementById("custom-text" + count);
        var getSelectLinkOption = document.getElementById("customLink" + count);
        var getSelectInterviewOption = document.getElementById("inputInterview" + count);

        getSelectOption.appendChild(selectOption1.cloneNode(true));
        getSelectOption.appendChild(selectOption2.cloneNode(true));
        getSelectOption.appendChild(selectOption3.cloneNode(true));
        getSelectOption.appendChild(selectOption4.cloneNode(true));

        getSelectLinkOption.appendChild(selectLinkOption1.cloneNode(true));
        getSelectLinkOption.appendChild(selectLinkOption2.cloneNode(true));


        getSelectInterviewOption.appendChild(selectInterview1.cloneNode(true));
        getSelectInterviewOption.appendChild(selectInterview2.cloneNode(true));
        getSelectInterviewOption.appendChild(selectInterview3.cloneNode(true));


        count += 1;
    }
    //bouton a la con 

    var divButton = document.getElementById('divButton');

    var buttonSend = document.createElement("button");
    buttonSend.setAttribute("class", "sendModifArticle");
    buttonSend.setAttribute("name", "sendparaphoto")
    buttonSend.setAttribute("type", "submit")
    var textButtonSend = document.createTextNode("Terminer")
    buttonSend.appendChild(textButtonSend);


    divButton.appendChild(buttonSend);



}

const button = document.getElementById('addParagraphe');

if (button) {
    button.addEventListener('click', (e) => {
        nbParagraphe();
        getPhoto();
    })
}