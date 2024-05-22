
    var menu_btn = document.querySelector("#menu-btn");
    var menu_btn2 = document.querySelector("#menu-btn2");
    var sidebar = document.querySelector("#sidebar");
    var sidebarLogo = document.querySelector("#sidebarLogo");
    var container = document.querySelector(".my-container");

    sidebarLogo.classList.add("active-nav");
    $('#menu-btn').hide();

    menu_btn.addEventListener("click", () => {
        $('#menu-btn').hide();
        container.classList.toggle("active-cont");
        sidebar.classList.toggle("active-nav");
    });
    
    menu_btn2.addEventListener("click", () => {
        $('#menu-btn').show();
        container.classList.toggle("active-cont");
        sidebar.classList.toggle("active-nav");
    });
