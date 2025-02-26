<?php
/**
 * @ilCtrl_isCalledBy ilPreventDevToolsAndCopyPasteConfigGUI: ilObjComponentSettingsGUI
 *
 * Konfigurations-GUI für das Plugin PreventDevToolsAndCopyPaste.
 */
class ilPreventDevToolsAndCopyPasteConfigGUI extends ilPluginConfigGUI
{
    /**
     * @var ilPreventDevToolsAndCopyPastePlugin
     */
    protected $plugin;

    /**
     * Wird vom Plugin-Manager aufgerufen.
     */
    public function performCommand(string $cmd): void
    {
        global $DIC;
        $this->plugin = $this->getPluginObject();
        $ctrl = $DIC->ctrl();
        $tpl  = $DIC->ui()->mainTemplate();

        switch ($cmd) {
            case 'configure':
            case 'save':
                $this->$cmd();
                break;
            default:
                $this->configure();
                break;
        }
    }

    /**
     * Zeigt das Konfigurationsformular an.
     */
    protected function configure(): void
    {
        global $DIC;
        $tpl  = $DIC->ui()->mainTemplate();
        $form = $this->initForm();
        $tpl->setContent($form->getHTML());
    }

    /**
     * Speichert die Einstellungen.
     * (Erfolgsmeldung wurde entfernt.)
     */
    protected function save(): void
    {
        global $DIC;
        $ctrl = $DIC->ctrl();
        $tpl  = $DIC->ui()->mainTemplate();

        $form = $this->initForm();
        if ($form->checkInput()) {
            // Neues Setting: plugin_enabled statt global_block
            $plugin_enabled = $form->getInput("plugin_enabled") ? "1" : "";
            $refid_list     = $form->getInput("refid_list") ?? "";

            $cfg = $this->plugin->getConfig();
            $cfg->set("plugin_enabled", $plugin_enabled);
            $cfg->set("refid_list", trim($refid_list));

            // Keine Erfolgsmeldung – direkt umleiten
            $ctrl->redirect($this, "configure");
        } else {
            $form->setValuesByPost();
            $tpl->setContent($form->getHTML());
        }
    }

    /**
     * Baut das Formular (Checkbox + Textfeld).
     */
    protected function initForm(): ilPropertyFormGUI
    {
        global $DIC;
        $ctrl = $DIC->ctrl();

        $form = new ilPropertyFormGUI();
        $form->setTitle("Prevent DevTools & Copy/Paste - Einstellungen");
        $form->setFormAction($ctrl->getFormAction($this));

        $cfg = $this->plugin->getConfig();
        // Neues Setting aus DB holen
        $savedEnabled = $cfg->get("plugin_enabled");
        $savedRefids  = $cfg->get("refid_list");

        // Neue Checkbox: "Plugin aktivieren"
        $cb = new ilCheckboxInputGUI("Plugin aktivieren", "plugin_enabled");
        $cb->setInfo("Wenn aktiviert, blockiert das Plugin Copy&Paste / DevTools basierend auf ref_ids.");
        $cb->setChecked($savedEnabled === "1");
        $form->addItem($cb);

        // Textfeld: ref_ids
        $ti = new ilTextInputGUI("ref_ids (Kommagetrennt)", "refid_list");
        $ti->setInfo("Wenn das Plugin aktiviert ist, blockiert es nur bei diesen ref_ids, vorausgesetzt es ist ein active_id > 0 vorhanden.");
        $ti->setValue($savedRefids);
        $form->addItem($ti);

        $form->addCommandButton("save", "Speichern");
        return $form;
    }
}
