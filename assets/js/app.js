const darkModeToggle =
document.getElementById("darkModeToggle");

if(localStorage.getItem("darkMode") === "enabled"){
    document.body.classList.add("dark-mode");
}

if(darkModeToggle){

    darkModeToggle.addEventListener("click", () => {

        document.body.classList.toggle("dark-mode");

        if(document.body.classList.contains("dark-mode")){
            localStorage.setItem("darkMode", "enabled");
        }else{
            localStorage.setItem("darkMode", "disabled");
        }

    });

}
const themeToggleBtn =
document.getElementById("themeToggleBtn");

if(themeToggleBtn){

    themeToggleBtn.addEventListener("click", () => {

        document.body.classList.toggle("dark-mode");

        if(document.body.classList.contains("dark-mode")){
            localStorage.setItem("darkMode", "enabled");
        }else{
            localStorage.setItem("darkMode", "disabled");
        }

    });

}

function showToast(message){

    const toast = document.createElement("div");

    toast.classList.add("toast");

    toast.innerText = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    },3000);

}
const counters = document.querySelectorAll(".counter");

counters.forEach(counter => {

    const updateCounter = () => {

        const target = +counter.getAttribute("data-target");

        const current = +counter.innerText;

        const increment = target / 40;

        if(current < target){

            counter.innerText =
            Math.ceil(current + increment);

            setTimeout(updateCounter,40);

        }else{

            counter.innerText = target;

        }

    };

    updateCounter();

});


