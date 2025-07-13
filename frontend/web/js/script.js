document.addEventListener('DOMContentLoaded', () => {
    navigationMenu();
    resetBtn();
    logoutBtn();
})

function navigationMenu() {

    const hamBurger = document.querySelector(".toggle_btn");

    hamBurger.addEventListener("click", function () {
        document.querySelector("#sidebar").classList.toggle("expand");
    });

}

function resetBtn() {
    const resetBtn = document.getElementById('resetBtn');
    if (!resetBtn) return;

    resetBtn.onclick = () => {
        parent.location.href = "index.php";
    }
}

function logoutBtn() {
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutForm = document.getElementById('logoutForm');
    if (!logoutBtn || !logoutForm) return;

    logoutForm.style.display = 'none'

    logoutBtn.addEventListener('click', (e) => {
        e.preventDefault()
        logoutForm.submit()
    })

}