const sendRestoreForm = (cod) => {
    let formRestore = document.getElementById("form-restore-game");
    let input = document.createElement("input");

    input.type = "hidden";
    input.name = "cod";
    input.value = cod;
    
    formRestore.appendChild(input);
    formRestore.submit();
}

const msgWarning = info => {
    let modalWrapper = document.getElementsByClassName("modal-wrapper")[0];
    let buttonRestore = document.getElementsByClassName("button-restore")[0];
    let buttonClose = document.getElementsByClassName("close-modal")[0];

    //Close modal warning
    buttonClose.addEventListener("click", () => {
        modalWrapper.className = "modal-wrapper not-visible"
    });

    buttonRestore.addEventListener("click", () => {
        sendRestoreForm(info);
    })

    modalWrapper.className = "modal-wrapper";
}

let gameRestoreList = Array.from(document.getElementsByClassName("game-restore"));
gameRestoreList.forEach(restore => {
    restore.addEventListener("click", () => {
        msgWarning(restore.dataset["cod"]);
    });
})
