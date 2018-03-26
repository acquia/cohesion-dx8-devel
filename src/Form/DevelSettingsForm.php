<?php

namespace Drupal\cohesion_devel\Form;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements the  form controller.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class DevelSettingsForm extends ConfigFormBase {

    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('cohesion_devel.settings');

        $form["show_json_fields"] = array(
            "#type" => "checkbox",
            "#title" => $this->t("Show JSON fields"),
            "#required" => false,
            "#default_value" => $config ? $config->get("show_json_fields") : false
        );

        $form["supress_errors_and_warnings"] = array(
            "#type" => "checkbox",
            "#title" => $this->t("Suppress errors and warnings"),
            "#required" => false,
            "#default_value" => $config ? $config->get("supress_errors_and_warnings") : false
        );

        // Group submit handlers in an actions element with a key of "actions" so
        // that it gets styled correctly, and so that other modules may add actions
        // to the form. This is not required, but is convention.
        $form['actions'] = [
            '#type' => 'actions',
        ];

        // Add a submit button that handles the submission of the form.
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return [
            'cohesion_devel.settings',
        ];
    }

    /**
     * Getter method for Form ID.
     *
     * @return string
     *   The unique ID of the form defined by this class.
     */
    public function getFormId() {
        return 'cohesion_devel_settings_form';
    }

    /**
     * @param array $form
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $config = $this->config('cohesion_devel.settings');
        $config->set("show_json_fields", $form_state->getValue("show_json_fields"));
        $config->set("supress_errors_and_warnings", $form_state->getValue("supress_errors_and_warnings"));
        $config->save();
        parent::submitForm($form, $form_state);
    }

}
