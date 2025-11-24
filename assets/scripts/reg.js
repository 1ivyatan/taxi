let regModal;
let bstrpRegModal;
let mode;

async function fetchRegs(query) {
    try {
        const response = await fetch( (query == null) ? "../api/reg.php" : "../api/reg.php?query=" + query );
        const data = await response.json();


        const regTable = document.getElementById("regTable");


        let template = "";

        data.forEach(reg => {

            let statusText = "";
            switch (reg.status) {
                case "pending": statusText = "Gaida"; break;
                case "viewed": statusText = "Skatīts"; break;
                case "completed": statusText = "Pabeigts"; break;
                case "rejected": statusText = "Atlikts"; break;
                default: break;
            }

            template += `
            <tr data-id="${reg.res_id}">
                <td>${reg.res_id}</td>
                <td>${reg.phone}</td>
                <td>${reg.name} ${reg.fname}</td>
                <td>${statusText}</td>
                <td class="d-flex justify-content-end">
                    <a href="#" class="btn usr-edit-btn">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="#" class="btn usr-del-btn">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>`;
        });

        regTable.querySelector("tbody").innerHTML = template;
    } catch (e) {
        popNotif("Nevarēja atvērt datus!", "text-danger");
        console.error(e);
    }
}

function openCreateDialog() {
    const form = document.querySelector("#regForm");
    form.reset();

    const date = new Date();
    const min = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
    regForm.querySelector("#regDateField").min = min;

    mode = "create";
    bstrpRegModal.show();
}



async function prepareDisableReg(id) {
    if (confirm("Tiešām noņemt šo pieteikumu?")) {
        try {

            const response = await fetch("../api/reg.php?id=" + id, {
                method: "DELETE",
            });
        
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error);
            }

            popNotif("Sekmīgi noņemts pieteikums!", "text-success");


            fetchRegs(null);

        } catch (e) {
            popNotif("Nevarēja noņemt pieteikumu!", "text-danger");
            console.error(e);

        }
    }

}

async function createReg() {
    const regForm = regModal.querySelector("#regForm");
    const input = new FormData(regForm);

    try {
        const entries = Object.fromEntries(input);

        const response = await fetch("../api/reg.php", {
            method: "POST",
            header: {"Content-Type": "application/json"},
            body: JSON.stringify(entries)
        });
        
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error);
        }

        popNotif("Sekmīgi pievienots pieteikums!", "text-success");

    } catch (e) {
        popNotif("Nevarēja pievienot pieteikumu!", "text-danger");
        console.error(e);
    }
}

async function openEditDialog(id) {
    const form = document.querySelector("#regForm");
    form.reset();

    form.querySelector("#regDateField").min = "";

    try {
        const response = await fetch("../api/reg.php?id=" + id);
        const data = await response.json();

        form.querySelector("#regNameField").value = data.name;
        form.querySelector("#regFnameField").value = data.fname;
        form.querySelector("#regPhoneField").value = data.phone;
        form.querySelector("#regEmailField").value = data.email;
        form.querySelector("#regDateField").value = data.date;
        form.querySelector("#regStatusField").value = data.status;
        form.querySelector("#regNotesField").value = data.notes;
        form.querySelector("#regIdField").value = data.res_id;
        

        const moddedDates = document.querySelector("#moddedDates");
        moddedDates.classList.remove("d-none");

        moddedDates.querySelector("#moddedDateStart").textContent = "Izveidota: " + data.created;

        if (data.edited) {
            moddedDates.querySelector("#moddedDateEdit").textContent = "Rediģēta: " + data.edited;
        }

        mode = "edit";
        bstrpRegModal.show();
    } catch (e) {
        popNotif("Nevarēja atvērt datus!", "text-danger");
        console.error(e);
    }

}

async function updateReg() {
    const regForm = regModal.querySelector("#regForm");
    const input = new FormData(regForm);

    try {
        const entries = Object.fromEntries(input);
        console.log(entries)

        const response = await fetch("../api/reg.php?id=" + entries.id, {
            method: "PUT",
            header: {"Content-Type": "application/json"},
            body: JSON.stringify(entries)
        });
        
        const data = await response.json();


        if (!response.ok) {
            throw new Error(data.error);
        }

        popNotif("Sekmīgi rediģēts pieteikums!", "text-success");


    } catch (e) {
        popNotif("Nevarēja rediģēt pieteikmu!", "text-danger");
        console.error(e);
    }

}

async function handleSubmit() {
    const regForm = regModal.querySelector("#regForm");

    if (!regForm.reportValidity()) {
        return;
    }
    
    if (mode == "edit") {
        await updateReg();
    } else if (mode == "create") {
        await createReg();
    }

    mode = "";
    
    bstrpRegModal.hide();
    fetchRegs(null);
}

document.addEventListener("DOMContentLoaded", (e) => {
    fetchRegs(null);

    const regTable = document.getElementById("regTable");
    const addRegButton = document.querySelector("#addRegButton");
    const regSaveBtn = document.querySelector("#regSaveButton");

    regTable.addEventListener("click", async (e) => {
        const edit = e.target.closest(".usr-edit-btn");
        const del = e.target.closest(".usr-del-btn");

        if (edit) {
            e.preventDefault();

            const row = edit.closest("tr");
            const id = row.getAttribute("data-id");

            openEditDialog(id);
        } else if (del) {
            e.preventDefault();

            const row = del.closest("tr");
            const id = row.getAttribute("data-id");

            prepareDisableReg(id);
        }
    });

    
    regModal = document.getElementById("regModal");
    bstrpRegModal = new bootstrap.Modal(regModal);

    addRegButton.addEventListener("click", (e) => {
        e.preventDefault();
        openCreateDialog();
    });

    regSaveBtn.addEventListener("click", handleSubmit);

    const searchForm = document.querySelector("#searchForm");
    searchForm.querySelector("button").addEventListener("click", searchRegs);

});

async function searchRegs() {
    const searchForm = document.querySelector("#searchForm");
    const queryField = searchForm.querySelector("input");
    const query = queryField.value.trim();

    if (query != "") {
        fetchRegs(query);
    } else {
        fetchRegs(null);
    }
}