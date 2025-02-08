const menuItemsDropdown = document.querySelectorAll(".menu-item-dropdown");
const menuItemStatic = document.querySelectorAll(".menu-item-static");

const sidebarPlantilla = document.getElementById("sidebar-plantilla");
const btnArrowSidebar = document.getElementById("menu-btn-arrow-sidebar");

const btnHamburguerSidebar = document.getElementById("sidebar-menu-btn"); //BOTON HAMBURGUESA

// MINIMIZAR TAMAÑO DEL SIDEBAR CON EL BOTON DE FLECHA
btnArrowSidebar.addEventListener("click", () => {
  sidebarPlantilla.classList.toggle("minimizar-sidebar");
});

//OCULAR Y MOSTRAR EL SIDEBAR CON EL BOTON HAMBURGUESA
btnHamburguerSidebar.addEventListener("click", () => {
  document.body.classList.toggle("sidebar-hidden");
});

// MOSTRAR Y OCULTAR LOS SUBMENÚS DEL SIDEBAR
menuItemsDropdown.forEach((menuItem) => {
  menuItem.addEventListener("click", () => {
    const subMenu = menuItem.querySelector(".sub-menu");
    const iconoSubMenu = menuItem.querySelector(".menu-link i:last-child");

    // Alternar la visibilidad del submenú
    const isActive = subMenu.classList.toggle("sub-menu-toggle");
    subMenu.style.height = isActive ? `${subMenu.scrollHeight + 6}px` : "0";
    subMenu.style.padding = isActive ? "0.2rem 0" : "0";

    // Rotar el icono
    iconoSubMenu.classList.toggle("rotate", isActive);

    // Cerrar otros submenús
    menuItemsDropdown.forEach((item) => {
      if (item !== menuItem) {
        const otrosSubMenu = item.querySelector(".sub-menu");
        if (otrosSubMenu) {
          otrosSubMenu.classList.remove("sub-menu-toggle");
          otrosSubMenu.style.height = "0";
          otrosSubMenu.style.padding = "0";
          const otrosIcon = item.querySelector(".menu-link i:last-child");
          otrosIcon.classList.remove("rotate");
        }
      }
    });
  });
});

//EVENTO CUNADO EL SIDEBAR ESTÁ MINIMIZADO CON EL SUBMENÚ ESTÁTICO
menuItemStatic.forEach((menuItem) => {
  menuItem.addEventListener("mouseenter", () => {
    if (!sidebarPlantilla.classList.contains("minimizar-sidebar")) return;
    menuItemsDropdown.forEach((item) => {
      if (item !== menuItem) {
        const otrosSubMenu = item.querySelector(".sub-menu");
        if (otrosSubMenu) {
          otrosSubMenu.classList.remove("sub-menu-toggle");
          otrosSubMenu.style.height = "0";
          otrosSubMenu.style.padding = "0";
        }
      }
    });
  });
});

const checkWindowsSize = () => {
  sidebarPlantilla.classList.remove("minimizar-sidebar");
};

checkWindowsSize();
window.addEventListener('resize', checkWindowsSize);


document.querySelectorAll('.subMenuBtn').forEach((btn) => {
  btn.addEventListener('click', (event) => {
    event.preventDefault();
  });
});