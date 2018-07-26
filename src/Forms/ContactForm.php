<?php

/**
 * Schets van form management / builder
 *
 * eerste fase:
 * zelf opgezette forms die niet beheerbaar zijn qua structuur, labels en error messages worden via squanto beheerd.
 * de submission wordt per type formulier (contact, whitepaper download, event registratie, demo aanvraag) bijgehouden
 * in de database en afhankelijk van het formulier ook melding via mail gegeven. Sommige formulieren vragen geen
 * bewaring in database maar kn rechtstreeks naar mailchimp worden doorgegeven. Dit is te bepalen per form.
 *
 * tweede fase:
 * Veralgemening van de submit logica. Elk type formulier heeft een eigen model waarin de specifieke logica wordt opgezet.
 * Hierin wordt bepaald wat de gevraagde velden zijn, de validatie voor het formulier en de bijhorende flow.
 *
 * Derde fase:
 * Veralgemening van de formulieren qua weergave. De verschillende velden en labels kunnen worden opgesteld per model.
 * Dit is vergelijkbaar met de chief opzet van custom translatable fields van een page.
 *
 * Vierde fase:
 * Beheerbaarheid van de klant van formulieren. zelf formulieren kunnen aanmaken en de flow ervan bepalen.
 */
namespace Thinktomorrow\Chief\Forms;
 
class ContactForm extends ChiefForm{

    public function customFields(){
        return ['firstname', 'lastname', 'email', 'content'];
    }

    public function validation(){
        // return new ValidationData($rules, $messages, $attributes);
    }

    // OPtions are: mail, mailchimp, database, notifications
    public function flow()
    {
        return ['mail', 'database'];
    }

    public function recipients()
    {
        return config('admin');
    }

    public function data()
    {
        return [];
    }
}
