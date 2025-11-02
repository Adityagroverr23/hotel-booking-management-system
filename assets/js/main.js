/* ============================================================
   Hotel Booking Management System - main.js
   Author: Anuj | Stack: HTML + CSS + JS + PHP + MySQL
   ============================================================ */

/* ------------------- Navbar Scroll Effect ------------------- */
window.addEventListener("scroll", function () {
  const navbar = document.querySelector(".navbar");
  if (window.scrollY > 50) {
    navbar.classList.add("navbar-scrolled");
  } else {
    navbar.classList.remove("navbar-scrolled");
  }
});

/* ------------------- Auto-hide Alert Messages ------------------- */
document.addEventListener("DOMContentLoaded", function () {
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      alert.classList.add("fade-out");
      setTimeout(() => {
        alert.style.display = "none";
      }, 500);
    }, 3000); // hide after 3 seconds
  });
});

/* ------------------- Smooth Scroll for Anchor Links ------------------- */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      window.scrollTo({
        top: target.offsetTop - 80,
        behavior: "smooth"
      });
    }
  });
});

/* ------------------- Mobile Navbar Toggle ------------------- */
document.addEventListener("DOMContentLoaded", function () {
  const toggler = document.querySelector(".navbar-toggler");
  const menu = document.querySelector(".navbar-collapse");
  
  if (toggler) {
    toggler.addEventListener("click", () => {
      menu.classList.toggle("show");
    });
  }
});

/* ------------------- Confirmation Before Delete/Cancel ------------------- */
function confirmAction(message = "Are you sure you want to proceed?") {
  return confirm(message);
}

// You can call confirmAction() in your HTML buttons like:
// <a href="delete.php?id=1" onclick="return confirmAction('Delete this booking?')">Delete</a>

/* ------------------- Fade Out Animation (CSS Helper) ------------------- */
/* Add this in CSS:
.fade-out {
  opacity: 0;
  transition: opacity 0.5s ease-out;
}
*/
