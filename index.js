const sendRemoveForm = (cod) => {
    let formRemove = document.getElementById("form-remove-game");
    let input = document.createElement("input");

    input.type = "hidden";
    input.name = "cod";
    input.value = cod;
    
    formRemove.appendChild(input);
    formRemove.submit();
}

const msgWarning = info => {
    let modalWrapper = document.getElementsByClassName("modal-wrapper")[0];
    let buttonRemove = document.getElementsByClassName("button-remove")[0];
    let buttonClose = document.getElementsByClassName("close-modal")[0];

    //Close modal warning
    buttonClose.addEventListener("click", () => {
        modalWrapper.className = "modal-wrapper not-visible"
    });

    buttonRemove.addEventListener("click", () => {
        sendRemoveForm(info);
    })

    modalWrapper.className = "modal-wrapper";

}

let gameRemoveList = Array.from(document.getElementsByClassName("game-remove"));
gameRemoveList.forEach(remove => {
    remove.addEventListener("click", () => {
        msgWarning(remove.dataset["cod"]);
    });
})
