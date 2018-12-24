<?php

/* layout-reset.twig */
class __TwigTemplate_6dad42c8e0fdb2813cf08c17db8edcacd0c9fd53f2ab2071a8d09ffde146166d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"wpml-section\" id=\"wpml_ls_reset\">
\t<div class=\"wpml-section-header\">
\t\t<h3>";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</h3>
\t</div>
\t<div class=\"wpml-section-content\">
\t\t<p>";
        // line 6
        echo (isset($context["description"]) ? $context["description"] : null);
        echo "</p>

\t\t";
        // line 8
        if ((isset($context["theme_config_file"]) ? $context["theme_config_file"] : null)) {
            // line 9
            echo "\t\t\t<p class=\"explanation-text\">";
            echo (isset($context["explanation_text"]) ? $context["explanation_text"] : null);
            echo "</p>
\t\t";
        }
        // line 11
        echo "
\t\t<p class=\"buttons-wrap\">
\t\t\t<a class=\"button button-secondary\" onclick=\"if(!confirm('";
        // line 13
        echo twig_escape_filter($this->env, (isset($context["confirmation_message"]) ? $context["confirmation_message"] : null), "html", null, true);
        echo "')) return false;\"
\t\t\t   href=\"";
        // line 14
        echo twig_escape_filter($this->env, (isset($context["restore_page_url"]) ? $context["restore_page_url"] : null), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, (isset($context["restore_button_label"]) ? $context["restore_button_label"] : null), "html", null, true);
        if ((isset($context["theme_config_file"]) ? $context["theme_config_file"] : null)) {
            echo " *";
        }
        echo "</a>
\t\t</p>
\t</div>
</div>";
    }

    public function getTemplateName()
    {
        return "layout-reset.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 14,  46 => 13,  42 => 11,  36 => 9,  34 => 8,  29 => 6,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layout-reset.twig", "/home/websiteladz/public_html/demo/realsalesvideo/wp-content/plugins/46795_WPML_[v3.7.1]/templates/language-switcher-admin-ui/layout-reset.twig");
    }
}
