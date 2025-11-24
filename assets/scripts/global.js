
document.addEventListener("DOMContentLoaded", (e) => {
    /* theme mode switch */
    let themeMode = getCookie("themeMode");

    if (themeMode == "") {
        setCookie("themeMode", "light", 30);
    }

    const themeswitchNav = document.getElementById("themeswitch-nav");

    if (themeswitchNav) {
        const toDarkString = "<i class=\"fa-solid fa-moon\"></i> Tumšs";
        const toLightString = "<i class=\"fa-solid fa-lightbulb\"></i> Gaišs";

        const setText = () => {
            let themeMode = getCookie("themeMode");
            if (themeMode == "light") {
                themeswitchNav.innerHTML = toDarkString;
            } else if (themeMode == "dark") {
                themeswitchNav.innerHTML = toLightString;
            }
        };

        themeswitchNav.addEventListener("click", (e) => {
            switchTheme();
            setText();
        });
        
        setText();
    }

});

function main() {
    getTheme();
}

function getTheme() {
    let themeMode = getCookie("themeMode");

    if (themeMode == "light") {
        document.documentElement.removeAttribute("data-bs-theme");
    } else if (themeMode == "dark") {
        document.documentElement.setAttribute("data-bs-theme", "dark");
    }
}

function switchTheme() {
    let themeMode = getCookie("themeMode");

    if (themeMode == "light") {
        setCookie("themeMode", "dark", 30);

        document.documentElement.setAttribute("data-bs-theme", "dark");

    } else if (themeMode == "dark") {
        setCookie("themeMode", "light", 30);
        document.documentElement.removeAttribute("data-bs-theme");
    }
}

function popNotif(text, colorClass) {
    const notifBox = document.getElementById('notifBoxTemplate');
    const notifInstance = bootstrap.Toast.getOrCreateInstance(notifBox);

    const notifboxText = notifBox.querySelector("#notifboxText");
    const notifBoxColor = notifBox.querySelector("#notifBoxColor");

    notifboxText.textContent = text;
    notifBoxColor.classList.value = "fa-solid fa-square " + colorClass;

    notifInstance.show();
}

/* cookie funcs */
function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function setCookie(cname, cvalue, exdays) {
  const d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  let expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

main();