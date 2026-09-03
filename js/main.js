document.addEventListener("DOMContentLoaded", function () {

    /* NAVBAR CON SOMBRA AL HACER SCROLL */
    const navbar = document.querySelector(".navbar");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 20) {
            navbar.classList.add("navbar-scroll");
        } else {
            navbar.classList.remove("navbar-scroll");
        }
    });


    const elementosAnimados = document.querySelectorAll(
    ".hero-texto, .mini-promo, .beneficio, .card-curso, .curso-horizontal, .formulario, .contacto, .estadisticas div, .filtros-niveles, .inscripcion-hero-contenido, .resumen-curso-inscripcion, .formulario-inscripcion, .pago-hero-contenido, .resumen-pago, .formulario-pago"
);

    const observador = new IntersectionObserver(function (entradas) {
        entradas.forEach(function (entrada) {
            if (entrada.isIntersecting) {
                entrada.target.classList.add("mostrar");
            }
        });
    }, {
        threshold: 0.15
    });

    elementosAnimados.forEach(function (elemento) {
        elemento.classList.add("oculto");
        observador.observe(elemento);
    });


    /* EFECTO ESCRITURA EN SUBTÍTULO */
    const subtitulo = document.querySelector(".subtitulo-hero");

    if (subtitulo) {
        const textoOriginal = subtitulo.textContent;
        subtitulo.textContent = "";
        subtitulo.classList.add("cursor-escritura");

        let i = 0;

        function escribirTexto() {
            if (i < textoOriginal.length) {
                subtitulo.textContent += textoOriginal.charAt(i);
                i++;
                setTimeout(escribirTexto, 70);
            } else {
                subtitulo.classList.remove("cursor-escritura");
            }
        }

        setTimeout(escribirTexto, 500);
    }


    /* CONTADOR ANIMADO EN ESTADÍSTICAS */
    const numeros = document.querySelectorAll(".estadisticas strong");

    const observadorNumeros = new IntersectionObserver(function (entradas) {
        entradas.forEach(function (entrada) {
            if (entrada.isIntersecting) {
                animarNumero(entrada.target);
                observadorNumeros.unobserve(entrada.target);
            }
        });
    }, {
        threshold: 0.5
    });

    numeros.forEach(function (numero) {
        observadorNumeros.observe(numero);
    });

    function animarNumero(elemento) {
        const texto = elemento.textContent.replace("+", "");
        const objetivo = parseInt(texto);
        let actual = 0;
        const duracion = 1200;
        const incremento = objetivo / (duracion / 20);

        const intervalo = setInterval(function () {
            actual += incremento;

            if (actual >= objetivo) {
                elemento.textContent = "+" + objetivo;
                clearInterval(intervalo);
            } else {
                elemento.textContent = "+" + Math.floor(actual);
            }
        }, 20);
    }


    /* MINI PROMO CON MOVIMIENTO SUAVE */
    const miniPromo = document.querySelector(".mini-promo");

    if (miniPromo) {
        document.addEventListener("mousemove", function (e) {
            const x = (e.clientX / window.innerWidth - 0.5) * 12;
            const y = (e.clientY / window.innerHeight - 0.5) * 12;

            miniPromo.style.transform = `translate(${x}px, ${y}px)`;
        });

        document.addEventListener("mouseleave", function () {
            miniPromo.style.transform = "translate(0, 0)";
        });
    }


    /* CAMPO TARJETA SOLO SI ELIGEN TARJETA */
const metodoPago = document.querySelector("select[name='metodo_pago']");
const numeroTarjeta = document.querySelector("input[name='numero_tarjeta']");
const grupoTarjeta = document.querySelector(".grupo-tarjeta");

if (metodoPago && numeroTarjeta && grupoTarjeta) {

    function controlarTarjeta() {
        if (metodoPago.value === "tarjeta") {
            grupoTarjeta.style.display = "block";
            numeroTarjeta.required = true;
        } else {
            grupoTarjeta.style.display = "none";
            numeroTarjeta.required = false;
            numeroTarjeta.value = "";
        }
    }

    controlarTarjeta();
    metodoPago.addEventListener("change", controlarTarjeta);
}

    /* ANIMACIÓN MODERNA PARA LOS CURSOS */
const cursos = document.querySelectorAll(".curso-horizontal");

if (cursos.length > 0) {
    const observadorCursos = new IntersectionObserver(function (entradas) {
        entradas.forEach(function (entrada, index) {
            if (entrada.isIntersecting) {
                setTimeout(function () {
                    entrada.target.classList.add("mostrar-curso");
                }, index * 120);

                observadorCursos.unobserve(entrada.target);
            }
        });
    }, {
        threshold: 0.15
    });

    cursos.forEach(function (curso) {
        observadorCursos.observe(curso);
    });
}


/* EFECTO ACTIVO EN FILTROS */
const linksFiltros = document.querySelectorAll(".filtros-niveles a");

linksFiltros.forEach(function (link) {
    link.addEventListener("click", function () {
        linksFiltros.forEach(function (item) {
            item.classList.remove("activo");
        });

        link.classList.add("activo");
    });
});

/* =========================
   BUSCADOR AJAX DE CURSOS
========================= */

const buscadoresAjax = document.querySelectorAll(".buscador-ajax");

buscadoresAjax.forEach(function (buscador) {
    const input = buscador.querySelector(".input-busqueda-ajax");
    const cajaResultados = buscador.querySelector(".resultados-ajax");

    if (!input || !cajaResultados) return;

    let temporizador;

    input.addEventListener("input", function () {
        const texto = input.value.trim();
        const categoria = input.dataset.categoria || "";

        clearTimeout(temporizador);

        if (texto.length < 2) {
            cajaResultados.innerHTML = "";
            cajaResultados.classList.remove("mostrar-resultados");
            return;
        }

        temporizador = setTimeout(function () {
            fetch(`/sistema-cursos/actions/buscar_cursos.php?buscar=${encodeURIComponent(texto)}&categoria=${encodeURIComponent(categoria)}`)
                .then(function (respuesta) {
                    return respuesta.json();
                })
                .then(function (cursos) {
                    cajaResultados.innerHTML = "";

                    if (cursos.length === 0) {
                        cajaResultados.innerHTML = `
                            <div class="resultado-vacio">
                                No se encontraron cursos
                            </div>
                        `;
                        cajaResultados.classList.add("mostrar-resultados");
                        return;
                    }

                    cursos.forEach(function (curso) {
                        const item = document.createElement("a");
                        item.href = `inscripcion.php?curso_id=${curso.id}`;
                        item.classList.add("resultado-item");

                        item.innerHTML = `
                            <div class="resultado-img">
                                <img src="img/${curso.imagen ? curso.imagen : 'curso-default.png'}" alt="${curso.titulo}">
                            </div>

                            <div class="resultado-info">
                                <strong>${curso.titulo}</strong>
                                <span>${curso.categoria}</span>
                            </div>
                        `;

                        cajaResultados.appendChild(item);
                    });

                    cajaResultados.classList.add("mostrar-resultados");
                })
                .catch(function () {
                    cajaResultados.innerHTML = `
                        <div class="resultado-vacio">
                            Error al buscar cursos
                        </div>
                    `;
                    cajaResultados.classList.add("mostrar-resultados");
                });
        }, 300);
    });

    document.addEventListener("click", function (e) {
        if (!buscador.contains(e.target)) {
            cajaResultados.innerHTML = "";
            cajaResultados.classList.remove("mostrar-resultados");
        }
    });
});

});