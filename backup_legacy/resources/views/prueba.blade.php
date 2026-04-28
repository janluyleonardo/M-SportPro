<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<title>Aparece y desaparece un texto cuando se pulsa un enlace.</title>

<!--
Codigo presentado por

www.uterra.com
 -->

<script languaje="Javascript">

document.write('<style type="text/css">div.cp_oculta{display: none;}</style>');
function MostrarOcultar(capa,enlace)
{
    if (document.getElementById)
    {
        var aux = document.getElementById(capa).style;
        aux.display = aux.display? "":"block";
    }
}
</script>

</head>

<body>

        <p>
          <a class="texto" href="javascript:MostrarOcultar('texto1');">
            👇
          </a>
        </p>

            <div class="cp_oculta" id="texto1">
            <p>El lenguaje HTML es el lenguaje más elemental de una página Web, sin él,
            la Web, como lo conocemos hoy, no existiría. El dominio del lenguaje HTML
            es esencial para emprender cualquier tipo de proyecto Web.</p>
            <p>El código HTML se interpreta y ejecuta en el navegador. </p>
            </div>

        <p><a class="texto" href="javascript:MostrarOcultar('texto2');">
        ¿Como definir un lenguaje de programación como PHP?</a></p>

            <div class="cp_oculta" id="texto2">
            <p>Para que una Web permita un alto grado de interactividad con sus usuarios
            es preciso usar un lenguaje de alto nivel, como por ejemplo, PHP. Gracias a PHP
            podremos crear foros, redes sociales, y cualquier aplicación imaginable. PHP no
            se ejecuta en el navegador, sino en el servidor. </p>
            <p>Aprender a programar en PHP lleva tiempo, pero merece la pena.</p>
            </div>

        <p><a class="texto" href="javascript:MostrarOcultar('texto3');">
        ¿Para que sirven las bases de datos?</a></p>

            <div class="cp_oculta" id="texto3">
            <p>Las bases de datos permiten guardar la información de manera organizada y
            acceder a ella en cualquier momento. Para poder usar una base de datos en
            nuestra Web precisamos de un lenguaje de alto nivel como PHP. Las bases de
            datos nos permiten cosas que hacemos cada momento, como identificarnos en
            nuestra red social favorita.</p>
            </div>

</body>

</html>
