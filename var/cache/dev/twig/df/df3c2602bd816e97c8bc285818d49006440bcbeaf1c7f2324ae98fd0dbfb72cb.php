<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* dashboard/index.html.twig */
class __TwigTemplate_c2e36f8a088bbd3dbb403b394a15f646f1b9e09cb838f9d666672504324f8c48 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "dashboard/index.html.twig"));

        // line 1
        echo "<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset=\"UTF-8\">
        <link rel=\"stylesheet\" href=\"/css/newCascadeStyleSheet.css\">
        <title></title>
    </head>
    <body>
        <div class =\"choose\">
        <a href =\"/dashboard/home/login\">Zaloguj</a>
        <a href =\"/dashboard/home/registerNewUser\">Zarejestruj się</a>
         <!--przekazanie zmiennej do widoku 
        <a href =\"Register.php\">{ { zmienna } }</a>!-->
        </div>
    </body>
</html>";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "dashboard/index.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset=\"UTF-8\">
        <link rel=\"stylesheet\" href=\"/css/newCascadeStyleSheet.css\">
        <title></title>
    </head>
    <body>
        <div class =\"choose\">
        <a href =\"/dashboard/home/login\">Zaloguj</a>
        <a href =\"/dashboard/home/registerNewUser\">Zarejestruj się</a>
         <!--przekazanie zmiennej do widoku 
        <a href =\"Register.php\">{ { zmienna } }</a>!-->
        </div>
    </body>
</html>", "dashboard/index.html.twig", "C:\\Users\\adamz\\Desktop\\Php repozytorium\\PokerLeague\\templates\\dashboard\\index.html.twig");
    }
}
