<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 */

$lang['admin_diagnostic_title'] = 'Diagnostica';
$lang['admin_diagnostic_description'] = 'Rilevamento dei problemi di configurazione e dei dati';
$lang['admin_diagnostic_no_error'] = 'Nessun errore';

$lang['admin_diagnostic_requests_tab'] = 'Richieste ferie';
$lang['admin_diagnostic_requests_description'] = 'Richieste ferie accettate ma duplicate';
$lang['admin_diagnostic_requests_thead_id'] = 'ID';
$lang['admin_diagnostic_requests_thead_employee'] = 'Dipendente';
$lang['admin_diagnostic_requests_thead_start_date'] = 'Data inizio';
$lang['admin_diagnostic_requests_thead_status'] = 'Stato';
$lang['admin_diagnostic_requests_thead_type'] = 'Tipologia';

$lang['admin_diagnostic_datetype_tab'] = 'Pomeriggio/Mattina';
$lang['admin_diagnostic_datetype_description'] = 'Richieste ferie con tipo inizio/fine non valido.';
$lang['admin_diagnostic_datetype_thead_id'] = 'ID';
$lang['admin_diagnostic_datetype_thead_employee'] = 'Dipendente';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'Data';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'Inizio';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'Fine';
$lang['admin_diagnostic_datetype_thead_status'] = 'Stato';

$lang['admin_diagnostic_entitlements_tab'] = 'Giorni spettanti';
$lang['admin_diagnostic_entitlements_description'] = 'Elenco dei contratti e dei dipendenti con spettanze su più di un anno.';
$lang['admin_diagnostic_entitlements_thead_id'] = 'ID';
$lang['admin_diagnostic_entitlements_thead_type'] = 'Tipologia';
$lang['admin_diagnostic_entitlements_thead_name'] = 'Nome';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'Data inizio';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'Data fine';
$lang['admin_diagnostic_entitlements_type_contract'] = 'Contratto';
$lang['admin_diagnostic_entitlements_type_employee'] = 'Dipendente';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'Eliminazione incompleta nel database.' ;

$lang['admin_diagnostic_daysoff_tab'] = 'Giorni non lavorativi';
$lang['admin_diagnostic_daysoff_description'] = 'Numero di giorni (per contratto) per cui è stata definita una durata non lavorativa.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'ID';
$lang['admin_diagnostic_daysoff_thead_name'] = 'Nome';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'Anno precedente';
$lang['admin_diagnostic_daysoff_thead_y'] = 'Anno corrente';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'Anno successivo';

$lang['admin_diagnostic_overtime_tab'] = 'Straordinari';
$lang['admin_diagnostic_overtime_description'] = 'Richieste di straordinario con durata negativa';
$lang['admin_diagnostic_overtime_thead_id'] = 'ID';
$lang['admin_diagnostic_overtime_thead_employee'] = 'Dipendente';
$lang['admin_diagnostic_overtime_thead_date'] = 'Data';
$lang['admin_diagnostic_overtime_thead_duration'] = 'Durata';
$lang['admin_diagnostic_overtime_thead_status'] = 'Stato';
$lang['admin_diagnostic_daysoff_description'] = 'Richieste di straordinario con durata negativa';
$lang['admin_diagnostic_daysoff_thead_id'] = 'ID';
$lang['admin_diagnostic_daysoff_thead_employee'] = 'Dipendente';
$lang['admin_diagnostic_daysoff_thead_date'] = 'Data';
$lang['admin_diagnostic_daysoff_thead_duration'] = 'Durata';
$lang['admin_diagnostic_daysoff_thead_status'] = 'Stato';

$lang['admin_diagnostic_contract_tab'] = 'Contratti';
$lang['admin_diagnostic_contract_description'] = 'Contratti non utilizzati (verifica che il contratto non sia duplicato).';
$lang['admin_diagnostic_contract_thead_id'] = 'ID';
$lang['admin_diagnostic_contract_thead_name'] = 'Nome';

$lang['admin_diagnostic_balance_tab'] = 'Saldo';
$lang['admin_diagnostic_balance_description'] = 'Richieste ferie per cui non esistono spettanze.';
$lang['admin_diagnostic_balance_thead_id'] = 'ID';
$lang['admin_diagnostic_balance_thead_employee'] = 'Dipendente';
$lang['admin_diagnostic_balance_thead_contract'] = 'Contratto';
$lang['admin_diagnostic_balance_thead_start_date'] = 'Data inizio';
$lang['admin_diagnostic_balance_thead_status'] = 'Stato';

$lang['admin_diagnostic_overlapping_tab'] = 'Sovrapposizioni';
$lang['admin_diagnostic_overlapping_description'] = 'Richieste ferie sovrapposte su due periodi annuali.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Dipendente';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Contratto';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Data inizio';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'Data fine';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Stato';

$lang['admin_oauthclients_title'] = 'Client OAuth e sessioni';
$lang['admin_oauthclients_tab_clients'] = 'Client';
$lang['admin_oauthclients_tab_clients_description'] = 'Elenco dei client autorizzati a utilizzare la REST API';
$lang['admin_oauthclients_thead_tip_edit'] = 'modifica client';
$lang['admin_oauthclients_thead_tip_delete'] = 'elimina client';
$lang['admin_oauthclients_button_add'] = 'Aggiungi';
$lang['admin_oauthclients_popup_add_title'] = 'Aggiungi client OAuth';
$lang['admin_oauthclients_popup_select_user_title'] = 'Associa a un utente esistente';
$lang['admin_oauthclients_error_exists'] = 'Questo client_id esiste già';
$lang['admin_oauthclients_confirm_delete'] = 'Sei sicuro di voler procedere?';
$lang['admin_oauthclients_tab_sessions'] = 'Sessioni';
$lang['admin_oauthclients_tab_sessions_description'] = 'Elenco delle sessioni OAuth REST API attive';
$lang['admin_oauthclients_button_purge'] = 'Pulisci';
