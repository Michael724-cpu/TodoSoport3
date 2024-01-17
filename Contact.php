<?php

include 'header/header.php';

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

  <!-- Contact Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="row g-5 mb-5 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="col-lg-6">
                        <h1 class="display-5 mb-0">Contáctanos</h1>
                    </div>
                </div>
                <p class="mb-4">En TodoSoport3, nuestro equipo altamente capacitado está listo para atender tus necesidades y proporcionarte asesoramiento sobre nuestros servicios.</p>
                <form method="POST" action="https://formspree.io/f/mdorevln" id="contact-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nombre Completo" required>
                                <label for="name">Nombre Completo</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Correo Electrónico" required>
                                <label for="email">Correo Electrónico</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Teléfono" required>
                                <label for="phone">Teléfono</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Mensaje" id="message"
                                   name="message" style="height: 100px" required></textarea>
                                <label for="message">Mensaje</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary py-3 px-5" type="submit">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s" style="min-height: 450px;">
                <div class="position-relative rounded overflow-hidden h-100">
                    <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1880.0376182613713!2d-99.19991379026077!3d19.53838322679305!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d21d5add79c90b%3A0x21ed3e264d36350d!2sAv%20Sor%20Juana%20In%C3%A9s%20de%20La%20Cruz%2014%2C%20Tlalnepantla%20Centro%2C%2054000%20Tlalnepantla%20de%20Baz%2C%20M%C3%A9x.!5e0!3m2!1ses!2smx!4v1686601018225!5m2!1ses!2smx"
                    width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->

<script>
    const contactForm = document.querySelector('#contact-form');

    contactForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const formData = new FormData(contactForm);

        const response = await fetch(contactForm.action, {
            method: contactForm.method,
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            contactForm.reset();
            swal("Mensaje Enviado Exitosamente", "Nos pondremos en contacto contigo pronto.", "success");
        } else {
            swal("Error al Enviar el Mensaje", "Por favor, intenta nuevamente más tarde.", "error");
        }
    });
</script>

<?php

include 'footer/footer.php';

?>
