let carModal;
let bstrpCarModal;
let mode;

async function fetchCars() {
    try {
        const response = await fetch("../api/cars.php");
        const data = await response.json();
        const carTable = document.getElementById("carTable");

        let template = "";
        data.forEach(car => {
            template += `
            <tr data-id="${car.car_id}">
                <td>${car.car_id}</td>
                <td>${car.license}</td>
                <td>${car.model}</td>
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

        carTable.querySelector("tbody").innerHTML = template;
    } catch (e) {
        popNotif("Nevarēja atvērt datus!", "text-danger");
        console.error(e);
    }
}

function openCreateDialog() {
    const form = document.querySelector("#carForm");
    form.reset();

    const imgPreview = document.querySelector("#carImagePreview");
    imgPreview.classList.add("d-none");

    form.querySelector("#carImageField").required = "required";
    
    const moddedDates = document.querySelector("#moddedDates");
    moddedDates.classList.add("d-none");

    mode = "create";
    bstrpCarModal.show();
}

async function createCar(e) {
    const carForm = carModal.querySelector("#carForm");
    const input = new FormData(carForm);

    try {
        const imginput = document.querySelector("input[type='file']");
        const b64 = await imageToB64(imginput.files[0]);
        input.set("imagehex", b64);

        const entries = Object.fromEntries(input);

        const response = await fetch("../api/cars.php", {
            method: "POST",
            header: {"Content-Type": "application/json"},
            body: JSON.stringify(entries)
        });
        
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error);
        }

        popNotif("Sekmīgi pievienota mašīna!", "text-success");


    } catch (e) {
        popNotif("Nevarēja pievienot mašīnu!", "text-danger");
        console.error(e);
    }
}


async function updateCar(e) {
    const carForm = carModal.querySelector("#carForm");
    const input = new FormData(carForm);

    try {
        const imginput = document.querySelector("input[type='file']");
        
        if (imginput.files[0]) {
            const b64 = await imageToB64(imginput.files[0]);
            input.set("imagehex", b64);
        } else {
            input.delete("imagehex");
        }

        const entries = Object.fromEntries(input);
        console.log(entries)

        const response = await fetch("../api/cars.php?id=" + entries.id, {
            method: "PUT",
            header: {"Content-Type": "application/json"},
            body: JSON.stringify(entries)
        });
        
        const data = await response.text();

        console.log(data)

        if (!response.ok) {
            throw new Error(data.error);
        }

        popNotif("Sekmīgi rediģēta mašīna!", "text-success");


    } catch (e) {
        popNotif("Nevarēja rediģēt mašīnu!", "text-danger");
        console.error(e);
    }
}

async function openEditDialog(id) {
    const form = document.querySelector("#carForm");
    form.reset();

    try {
        const imgPreview = document.querySelector("#carImagePreview");
        const response = await fetch("../api/cars.php?id=" + id);
        const data = await response.json();

        form.querySelector("#carModelField").value = data.model;
        form.querySelector("#carLicenseField").value = data.license;
        form.querySelector("#carSeatField").value = data.seats;

        form.querySelector("#carImageField").required = "";
        form.querySelector("#carIdField").value = id;

        form.querySelector("#carHide").checked = (data.hidden == "1") ? "checked" : "";
        
        imgPreview.classList.remove("d-none");
        imgPreview.src = data.imagehex;

        const moddedDates = document.querySelector("#moddedDates");
        moddedDates.classList.remove("d-none");

        moddedDates.querySelector("#moddedDateStart").textContent = "Izveidota: " + data.created;

        if (data.edited) {
            moddedDates.querySelector("#moddedDateEdit").textContent = "Rediģēta: " + data.edited;
        }

        mode = "edit";
        bstrpCarModal.show();
    } catch (e) {
        popNotif("Nevarēja atvērt datus!", "text-danger");
        console.error(e);
    }
}



async function handleSubmit(e) {
    const carForm = carModal.querySelector("#carForm");
    
    if (!carForm.reportValidity()) {
        return;
    }
    
    if (mode == "edit") {
        await updateCar(e);
    } else if (mode == "create") {
        await createCar();
    }

    mode = "";
    
    bstrpCarModal.hide();
    fetchCars();
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

async function prepareDisableCar(id) {
    if (confirm("Tiešām noņemt šo mašīnu?")) {
        try {

            const response = await fetch("../api/cars.php?id=" + id, {
                method: "DELETE",
            });
        
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error);
            }

            popNotif("Sekmīgi noņemta mašīna!", "text-success");


            fetchCars();

        } catch (e) {
            popNotif("Nevarēja noņemt mašīnu!", "text-danger");
            console.error(e);

        }
    }

}

document.addEventListener("DOMContentLoaded", (e) => {
    fetchCars();

    const carTable = document.getElementById("carTable");
    const addCarButton = document.querySelector("#addCarButton");
    const carSaveBtn = document.querySelector("#carSaveBtn");

    carTable.addEventListener("click", async (e) => {
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

            prepareDisableCar(id);
        }
    });

    carModal = document.getElementById("carModal");
    bstrpCarModal = new bootstrap.Modal(carModal);

    addCarButton.addEventListener("click", (e) => {
        e.preventDefault();
        openCreateDialog();
    });

    carSaveBtn.addEventListener("click", handleSubmit);
});