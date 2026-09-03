<?php
require_once "config/database.php";
include "includes/header.php";
?>

<section class="hero hero-video">

    <div class="video-fondo">
        <iframe
            src="https://www.youtube.com/embed/bLiVhw3Jb-E?autoplay=1&mute=1&loop=1&playlist=bLiVhw3Jb-E&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&iv_load_policy=3&disablekb=1"
            title="Video institucional"
            frameborder="0"
            allow="autoplay; encrypted-media"
            allowfullscreen>
        </iframe>
    </div>

    <div class="capa-azul"></div>
    <div class="capa-centro"></div>

    <div class="hero-contenido hero-doble">

    <div class="hero-texto">
        <h1>Bienvenidos a Academia Innova</h1>
        <h2 class="subtitulo-hero">Tu futuro comienza acá</h2>

        <p>
            Después de más de 20 años, nos enfrentamos a nuevos retos,
            nuevos desafíos, nuevas maneras de vivir.
        </p>

        <p>
            El mundo se adelantó y nosotros de la mano con él, por eso
            hoy nace un nuevo objetivo: instruir cada paso de quienes nos
            eligen día a día, trayendo el futuro a las exigencias de hoy.
        </p>

        <p>
            Te esperamos con nuevas propuestas, metodologías, recursos
            y sobre todo con nuevas experiencias de aprendizaje.
        </p>

        <a href="cursos.php" class="btn-principal">Ofertas académicas</a>
    </div>

    <div class="mini-promo">
        <p class="mini-texto">Los mejores profesores</p>
        <h3>Más de - cursos</h3>

       <form action="cursos.php" method="GET" class="mini-buscador buscador-ajax">
    <input 
        type="text" 
        name="buscar" 
        class="input-busqueda-ajax"
        placeholder="¿Qué querés estudiar?"
        autocomplete="off"
        data-categoria=""
    >

    <button type="submit">🔍</button>

    <div class="resultados-ajax"></div>
</form>

        <div class="mini-cuotas">
            <span>3</span>
            <p>CUOTAS<br><small>sin interés</small></p>
        </div>
    </div>

</div>

</section>

<section class="estadisticas">
    <div>
        <strong>-</strong>
        <span>Años de trayectoria</span>
    </div>

    <div>
        <strong>-</strong>
        <span>Alumnos formados</span>
    </div>

    <div>
        <strong>-</strong>
        <span>Cursos disponibles</span>
    </div>
</section>

<section id="quienes-somos" class="seccion elegirnos">
    <h2>¿Por qué elegirnos?</h2>

    <div class="beneficios">
        <div class="beneficio">
            <img src="img/educacion.png" alt="Educación integral">
            <h3>Educación integral</h3>
            <p>
                Formamos profesionales en diferentes áreas integrando la mayor
                oferta académica de la región. Cursos de formación profesional,
                carreras oficiales y especializaciones. Con el respaldo de empresas
                y organizaciones para el desarrollo de prácticas profesionales
                y pasantías de trabajo.
            </p>
        </div>

        <div class="beneficio">
            <img src="img/formacion.png" alt="Formación distribuida">
            <h3>Formación distribuida</h3>
            <p>
                Contamos con metodologías de vanguardia para que el alumno sea
                eficiente en su propio proceso educativo, accediendo a un abanico
                de opciones que le permita ajustar sus tiempos a los diferentes
                cursados con acompañamiento constante en cada etapa de formación.
            </p>
        </div>

        <div class="beneficio">
            <img src="img/innovacion.png" alt="Innovación continua">
            <h3>Innovación continua</h3>
            <p>
                Como insignia de nuestra institución que se refleja en la organización
                académica, tecnología educativa, infraestructura y en todos nuestros
                servicios pensados para el alumno y docente: la educación ha cambiado,
                y la institución se compromete al esfuerzo de evaluación y gestión
                que requiere dichos cambios.
            </p>
        </div>
    </div>
</section>

<section id="contacto" class="seccion contacto">
    <h2>Contacto</h2>

    <p><strong>Dirección:</strong> Av. Principal 1234, Ciudad Ejemplo</p>
    <p><strong>Teléfono:</strong> 376 4000000</p>
    <p><strong>Email:</strong> administracion@sistemacursos.test</p>
</section>

<a href="#" class="whatsapp">
    WhatsApp
</a>

<?php include "includes/footer.php"; ?>