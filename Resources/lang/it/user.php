<?php return array (
  'navigation' => 
  array (
    'name' => 'Utente',
    'plural' => 'Utenti',
    'group' => 
    array (
      'name' => 'Admin',
    ),
  ),
  'fields' => 
  array (
    'first_name' => 'Nome',
    'last_name' => 'Cognome',
    'name' => 
    array (
      'label' => 'Nome',
    ),
    'email' => 
    array (
      'label' => 'Email',
    ),
    'created_at' => 'Data Creazione',
    'updated_at' => 'Ultima Modifica',
    'role' => 
    array (
      'name' => 
      array (
        'label' => 'role.name',
      ),
    ),
    'active' => 'Attivo',
    'id' => 'ID',
    'password' => 'Password',
    'password_confirmation' => 'Conferma Password',
    'email_verified_at' => 
    array (
      'label' => 'Email Verificata Il',
    ),
    'teams' => 
    array (
      'name' => 
      array (
        'label' => 'teams.name',
      ),
    ),
    'roles' => 
    array (
      'name' => 
      array (
        'label' => 'roles.name',
      ),
    ),
    'password_expires_at' => 
    array (
      'label' => 'password_expires_at',
    ),
    'verified' => 
    array (
      'label' => 'verified',
    ),
    'unverified' => 
    array (
      'label' => 'unverified',
    ),
  ),
  'filters' => 
  array (
    'active_users' => 'Utenti Attivi',
    'creation_date' => 'Data di Creazione',
    'date_from' => 'Dal',
    'date_to' => 'Al',
    'verified' => 'Utenti Verificati',
    'unverified' => 'Utenti Non Verificati',
  ),
  'messages' => 
  array (
    'no_records' => 'Nessun utente trovato',
    'loading' => 'Caricamento utenti...',
    'search' => 'Cerca utenti...',
  ),
  'actions' => 
  array (
    'toggle_layout' => 'Cambia Layout',
    'create' => 'Crea Utente',
    'delete' => 'Elimina Utente',
    'associate' => 'Associa Utente',
    'bulk_delete' => 'Elimina Selezionati',
    'bulk_detach' => 'Scollega Selezionati',
    'attach_user' => 'Collega Utente',
    'associate_user' => 'Associa Utente',
    'user_actions' => 'Azioni Utente',
    'view' => 'Visualizza',
    'edit' => 'Modifica',
    'detach' => 'Scollega',
    'row_actions' => 'Azioni',
    'delete_selected' => 'Elimina Selezionati',
    'confirm_detach' => 'Sei sicuro di voler scollegare questo utente?',
    'confirm_delete' => 'Sei sicuro di voler eliminare gli utenti selezionati?',
    'success_attached' => 'Utente collegato con successo',
    'success_detached' => 'Utente scollegato con successo',
    'success_deleted' => 'Utenti eliminati con successo',
    'import' => 
    array (
      'fields' => 
      array (
        'import_file' => 'Seleziona un file XLS o CSV da caricare',
      ),
    ),
    'export' => 
    array (
      'filename_prefix' => 'Aree al',
      'columns' => 
      array (
        'name' => 'Nome area',
        'parent_name' => 'Nome area livello superiore',
      ),
    ),
    'change_password' => 'Cambio password',
  ),
  'modals' => 
  array (
    'create' => 
    array (
      'heading' => 'Crea Utente',
      'description' => 'Crea un nuovo utente',
      'actions' => 
      array (
        'submit' => 'Crea',
        'cancel' => 'Annulla',
      ),
    ),
    'edit' => 
    array (
      'heading' => 'Modifica Utente',
      'description' => 'Modifica le informazioni utente',
      'actions' => 
      array (
        'submit' => 'Salva Modifiche',
        'cancel' => 'Annulla',
      ),
    ),
    'delete' => 
    array (
      'heading' => 'Elimina Utente',
      'description' => 'Sei sicuro di voler eliminare questo utente?',
      'actions' => 
      array (
        'submit' => 'Elimina',
        'cancel' => 'Annulla',
      ),
    ),
    'associate' => 
    array (
      'heading' => 'Associa Utente',
      'description' => 'Seleziona un utente da associare',
      'actions' => 
      array (
        'submit' => 'Associa',
        'cancel' => 'Annulla',
      ),
    ),
    'detach' => 
    array (
      'heading' => 'Scollega Utente',
      'description' => 'Sei sicuro di voler scollegare questo utente?',
      'actions' => 
      array (
        'submit' => 'Scollega',
        'cancel' => 'Annulla',
      ),
    ),
    'bulk_delete' => 
    array (
      'heading' => 'Elimina Utenti Selezionati',
      'description' => 'Sei sicuro di voler eliminare gli utenti selezionati?',
      'actions' => 
      array (
        'submit' => 'Elimina Selezionati',
        'cancel' => 'Annulla',
      ),
    ),
  ),
);