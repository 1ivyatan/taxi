
let usrModal;
let bstrpUsrModal;
let mode;

async function fetchUsers() {
    try {
        const response = await fetch("../api/users.php");
        const data = await response.json();

        const usrtable = document.getElementById("usrtable");

        let template = "";
        data.forEach(usr => {
            template += `
            <tr data-id="${usr.usr_id}">
                <td>${usr.usr_id}</td>
                <td>${usr.name} ${usr.fname}</td>
                <td>${usr.usr}</td>
                <td class="d-flex justify-content-end">
                    <a href="#" class="btn usr-edit-btn">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    ` + ((usr.cand) ?  `<a href="#" class="btn usr-del-btn"><i class="fa-solid fa-trash"></i></a>` : "") + `
                </td>
            </tr>`;
        });



        usrtable.querySelector("tbody").innerHTML = template;
    } catch (e) {
        popNotif("Nevarēja atvērt datus!", "text-danger");
        console.error(e);
    }
}

const toggleImageView = (imageField, mode) => {
    switch (mode) {
        case "all":
            imageField.classList.remove("d-none");
            imageField.querySelector("#usrImageField").classList.remove("d-none");
            imageField.querySelector("#usrImageRemoveField").classList.remove("d-none");
            break;

        case "image":
            imageField.classList.remove("d-none");
            imageField.querySelector("#usrImageField").classList.add("d-none");
            imageField.querySelector("#usrImageRemoveField").classList.add("d-none");

            break;

        case "none":
            imageField.classList.add("d-none");
            break;
    
        default: break;
    }
};

async function openEditDialog(id) {
    const textForm = usrModal.querySelector("#usrTextForm");

    textForm.reset();

    try {
        const response = await fetch("../api/users.php?id=" + id);
        const data = await response.json();

        //console.log(data)

        textForm.querySelector("#usrPasswdContainer").required = "";
        textForm.querySelector("#usrUsrField").value = data.usr;
        textForm.querySelector("#usrNameField").value = data.name;
        textForm.querySelector("#usrFnameField").value = data.fname;
        textForm.querySelector("#usrEmailField").value = data.email;
        textForm.querySelector("#usrPhoneField").value = data.phone;
        textForm.querySelector("#usrDescField").value = data.descr;
        textForm.querySelector("#usrIdField").value = data.usr_id;
        
        const usrSaveBtn = usrModal.querySelector("#usrSaveBtn");
        const usrRoleContainer = textForm.querySelector("#usrRoleContainer");
        const roleField = textForm.querySelector("#usrRoleField");
       
        const usrPasswdContainer = textForm.querySelector("#usrPasswdContainer");
        const usrPasswdfield = textForm.querySelector("#usrPasswdfield");
        
        const imgPreview = textForm.querySelector("#usrImagePreview");
        const usrImageRemoveField = textForm.querySelector("#usrImageRemoveField");
        const wholeImageField = textForm.querySelector("#wholeImageField");

        const usrImageRemove = textForm.querySelector("#usrImageRemove");
        
        if (data.role) {
            roleField.disabled = "";
            roleField.value = data.role;
            usrRoleContainer.classList.remove("d-none");
        } else {
            usrRoleContainer.classList.add("d-none");
            roleField.disabled = "disabled";
        }

        if (data.canpasswd) {
            usrPasswdfield.disabled = "";
            usrPasswdContainer.classList.remove("d-none");
        } else {
            usrPasswdfield.disabled = "disabled";
            usrPasswdContainer.classList.add("d-none");
        }

        usrPasswdfield.required = "";

        if (data.caned) {
            usrSaveBtn.disabled = "";
            usrSaveBtn.classList.remove("d-none");

            toggleImageView(wholeImageField, "all");

        } else {

            if (!data.imagehex) {
                toggleImageView(wholeImageField, "none");
            } else {
                toggleImageView(wholeImageField, "image");
            }
            
            usrSaveBtn.disabled = "disabled";
            usrSaveBtn.classList.add("d-none");
        }

        if (data.imagehex) {
            imgPreview.src = data.imagehex;
            imgPreview.classList.remove("d-none");
        } else {
            imgPreview.src = "";
            imgPreview.classList.add("d-none");
        }

        mode = "edit";
        bstrpUsrModal.show();

    } catch (e) {
        popNotif("Nevarēja atvērt datus!", "text-danger");
        console.error(e);
    }
    
}

async function openCreateDialog() {
    const textForm = usrModal.querySelector("#usrTextForm");

    textForm.reset();

    textForm.querySelector("#usrPasswdContainer").required = "required";

    const usrSaveBtn = usrModal.querySelector("#usrSaveBtn");
    const usrRoleContainer = textForm.querySelector("#usrRoleContainer");
    const roleField = textForm.querySelector("#usrRoleField");
       
    const usrPasswdContainer = textForm.querySelector("#usrPasswdContainer");
    const usrPasswdfield = textForm.querySelector("#usrPasswdfield");
        
    const imgPreview = textForm.querySelector("#usrImagePreview");
    const usrImageRemoveField = textForm.querySelector("#usrImageRemoveField");
    const wholeImageField = textForm.querySelector("#wholeImageField");

    const usrImageRemove = textForm.querySelector("#usrImageRemove");


    

    usrPasswdfield.disabled = "";
    usrPasswdfield.required = "required";
    usrPasswdContainer.classList.remove("d-none");

    roleField.disabled = "disabled";
    usrRoleContainer.classList.add("d-none");
    
    imgPreview.classList.add("d-none");

    roleField.value = "driver";

    mode = "create";
    bstrpUsrModal.show();
}

async function prepareDisableUser(id) {
    if (confirm("Tiešām atspējot šo lietotāju?")) {
        try {

            const response = await fetch("../api/users.php?id=" + id, {
                method: "DELETE",
            });
        
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error);
            }

            popNotif("Sekmīgi atspējots lietotājs!", "text-success");


            fetchUsers();

        } catch (e) {
            popNotif("Nevarēja atspējot lietotāju!", "text-danger");
            console.error(e);

        }
    }
}

document.addEventListener("DOMContentLoaded", (e) => {
    fetchUsers();

    const usrtable = document.getElementById("usrtable");

    usrtable.addEventListener("click", async (e) => {
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

            prepareDisableUser(id);
        }
    });

    usrModal = document.getElementById("usrModal");
    const usrSaveBtn = usrModal.querySelector("#usrSaveBtn");
    bstrpUsrModal = new bootstrap.Modal(usrModal);

    usrSaveBtn.addEventListener("click", handleSubmit);

    const addUsrButton = document.querySelector("#addUsrButton");
    
    addUsrButton.addEventListener("click", async (e) => {
        e.preventDefault();
        openCreateDialog();
    });


});

async function handleSubmit(e) {
    const usrTextForm = usrModal.querySelector("#usrTextForm");
    
    if (!usrTextForm.reportValidity()) {
        return;
    }
    
    if (mode == "edit") {
        await updateUsr(e);
    } else if (mode == "create") {
        await createUsr();
    }

    mode = "";
    
    bstrpUsrModal.hide();
    fetchUsers();
}

async function imageToB64(file) {
    const reader = (file) => new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (event) => {
            resolve(event.target.result)
        };
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
    
    return await reader(file);
}

async function createUsr(e) {
    const usrTextForm = usrModal.querySelector("#usrTextForm");
    const textInput = new FormData(usrTextForm);

    try {
        const pfpinput = usrTextForm.querySelector("input[type='file']");
        if (pfpinput.files && pfpinput.files[0]) {
            const b64 = await imageToB64(pfpinput.files[0]);
            textInput.set("imagehex", b64);
        } else {
            textInput.delete("imagehex");
        }

        const entries = Object.fromEntries(textInput);

        const response = await fetch("../api/users.php", {
            method: "POST",
            header: {"Content-Type": "application/json"},
            body: JSON.stringify(entries)
        });
        
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error);
        }

        popNotif("Sekmīgi izveidots lietotājs!", "text-success");
        
    } catch (e) {
        popNotif("Nevarēja izveidot lietotāju!", "text-danger");
        console.error(e);
    }
}

async function updateUsr(e) {
    const usrTextForm = usrModal.querySelector("#usrTextForm");
    const textInput = new FormData(usrTextForm);

    try {
        const pfpinput = usrTextForm.querySelector("input[type='file']");
        if (pfpinput.files && pfpinput.files[0]) {
            const b64 = await imageToB64(pfpinput.files[0]);
            textInput.set("imagehex", b64);
        } else {
            textInput.delete("imagehex");
        }

        const usrPasswdfield = usrTextForm.querySelector("#usrPasswdfield");

        if (usrPasswdfield.value == "") {
            textInput.delete("passwd");
        }
           
        const entries = Object.fromEntries(textInput);

        const response = await fetch("../api/users.php?id=" + entries.id, {
            method: "PUT",
            header: {"Content-Type": "application/json"},
            body: JSON.stringify(entries)
        });
        
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error);
        }

        popNotif("Sekmīgi saglabāti dati!", "text-success");
    } catch (e) {
        popNotif("Nevarēja saglabāt datus!", "text-danger");
        console.error(e);
    }
}