(function () {
    const items = document.querySelectorAll(".task-list li");
    const aviso = document.querySelector(".filtro-vacio");
    let estado = "todas";
    let fecha = "todas";

    function iso(fechaBase) {
        const mes = String(fechaBase.getMonth() + 1).padStart(2, "0");
        const dia = String(fechaBase.getDate()).padStart(2, "0");
        return fechaBase.getFullYear() + "-" + mes + "-" + dia;
    }

    function coincideFecha(valor) {
        if (fecha === "todas") {
            return true;
        }
        if (!valor) {
            return false;
        }
        const ahora = new Date();
        if (fecha === "hoy") {
            return valor === iso(ahora);
        }
        if (fecha === "mes") {
            return valor.slice(0, 7) === iso(ahora).slice(0, 7);
        }
        const lunes = new Date(ahora);
        lunes.setDate(ahora.getDate() - ((ahora.getDay() + 6) % 7));
        const domingo = new Date(lunes);
        domingo.setDate(lunes.getDate() + 6);
        return valor >= iso(lunes) && valor <= iso(domingo);
    }

    function aplicar() {
        let visibles = 0;
        items.forEach(function (li) {
            const visible = (estado === "todas" || li.dataset.estado === estado) && coincideFecha(li.dataset.fecha);
            li.classList.toggle("oculta", !visible);
            if (visible) {
                visibles += 1;
            }
        });
        if (aviso) {
            aviso.hidden = visibles !== 0;
        }
    }

    document.querySelectorAll("[data-filtro]").forEach(function (grupo) {
        grupo.addEventListener("click", function (event) {
            const boton = event.target.closest(".filter-button");
            if (!boton) {
                return;
            }
            if (grupo.dataset.filtro === "estado") {
                estado = boton.dataset.valor;
            } else {
                fecha = boton.dataset.valor;
            }
            grupo.querySelectorAll(".filter-button").forEach(function (item) {
                item.classList.toggle("active", item === boton);
            });
            aplicar();
        });
    });
})();
