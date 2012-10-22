<?php

namespace Kunstmaan\AdminBundle\Helper;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormError;

/**
 * Helper class for forms
 */
class FormHelper
{

    /**
     * Return if there are error messages.
     *
     * @param FormView $formView
     *
     * @return bool
     */
    public function hasRecursiveErrorMessages(FormView $formView)
    {
        if (!empty($formView->vars['errors'])) {
            return true;
        }
        if ($formView->count()) {
            foreach ($formView->children as $child) {
                if ($this->hasRecursiveErrorMessages($child)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get the error messages.
     *
     * @param FormView[]|FormView $formViews The form views
     * @param array               &$errors   The errors
     *
     * @return array
     */
    public function getRecursiveErrorMessages($formViews, array &$errors = array())
    {
        if (is_array($formViews)) {
            foreach ($formViews as $formView) {
                $this->getRecursiveErrorMessages($formView, $errors);
            }
        } else {
            /**
             * @var $formViews FormView
             * @var $error     FormError
             */
            foreach ($formViews->vars['errors'] as $error) {

                $template   = $error->getMessageTemplate();
                $parameters = $error->getMessageParameters();

                foreach ($parameters as $var => $value) {
                    $template = str_replace($var, $value, $template);
                }

                $errors[] = $error;
            }
            if ($formViews->count()) {
                foreach ($formViews->children as $child) {
                    $this->getRecursiveErrorMessages($child, $errors);
                }
            }
        }

        return $errors;
    }

}
