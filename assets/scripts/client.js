async function saveReg() {
    const form = document.querySelector("#regForm");
    
    if (!form.reportValidity()) {
        return;
    }

    try {   
        const input = new FormData(form);
        const entries = Object.fromEntries(input);

        console.log(entries)

        const response = await fetch("./api/client.php", {
            method: "POST",
            header: {"Content-Type": "application/json"},
            body: JSON.stringify(entries)
        });
        

        if (!response.ok) {
            throw new Error(data.error);
        }

        popNotif("Sekmīgi iesniegts pieteikums!", "text-success");
         document.querySelector("#regSaveBtn").disabled = "disabled";
    } catch (e) {
        popNotif("Nevarēja iesniegt pieteikumu!", "text-danger");
        console.error(e);
    }
}


document.addEventListener("DOMContentLoaded", (e) => {
    
    document.querySelector("#regSaveBtn").addEventListener("click", (e) => {
        saveReg();
    });
});