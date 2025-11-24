async function fetchUser() {
    const form = document.querySelector("#proTextForm");
    const response = await fetch("../api/profile.php");
    const data = await response.json();
    
    form.querySelector("#proUsrField").value = data.usr;
    form.querySelector("#proNameField").value = data.name;
    form.querySelector("#proFnameField").value = data.fname;
    form.querySelector("#proEmailField").value = data.email;
    form.querySelector("#proPhoneField").value = data.phone;
    form.querySelector("#proPasswdfield").value = "";
    form.querySelector("#proDescField").value = data.descr;

    if (data.imagehex) {
        form.querySelector("#proImagePreview").src = data.imagehex;
        form.querySelector("#proImagePreview").classList.remove("d-none");
    } else {
        form.querySelector("#proImagePreview").classList.add("d-none");
    }
}

async function saveUser() {
    const form = document.querySelector("#proTextForm");
    
    if (!form.reportValidity()) {
        return;
    }

    try {
        const input = new FormData(form);

        const imginput = document.querySelector("input[type='file']");

        if (imginput.files[0]) {
            const b64 = await imageToB64(imginput.files[0]);
            input.set("imagehex", b64);
        } else {
            input.delete("imagehex");
        }

        if (!form.querySelector("#proPasswdfield").value) {
            input.delete("passwd");
        }

        const entries = Object.fromEntries(input);
        console.log(entries)

        const response = await fetch("../api/profile.php", {
            method: "PUT",
            header: {"Content-Type": "application/json"},
            body: JSON.stringify(entries)
        });
        
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error);
        }

        popNotif("Sekmīgi saglabāti dati!", "text-success");

        await fetchUser();
    } catch (e) {
        popNotif("Nevarēja rediģēt profilu!", "text-danger");
        console.error(e);
    }
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

document.addEventListener("DOMContentLoaded", (e) => {
    fetchUser();

    document.querySelector("#proSaveBtn").addEventListener("click", (e) => {
        saveUser();
    });
});