# BlueCalendar
Un'applicazione PHP il cui scopo principale è la gestione delle ferie e dei permessi.

# Istruzioni

**ATTENZIONE! Queste istruzioni sono work in progress, e pertanto potrebbero risultare incomplete.**

# Prerequisiti: Installazione di Docker

Dalla pagina web https://www.docker.com/get-started/ selezionare "Download Docker Desktop", come indicato nell'immagine
<br/>
<br/>
<img width="1896" height="867" alt="Screenshot 2026-02-18 110130" src="https://github.com/user-attachments/assets/51b21a78-b4bc-4b41-821e-7f709d64129b" />
<br/>
<br/>
- Seguire le procedure dell'installer.

# Creazione del container (una tantum)

- Una volta installato Docker, aprire un terminale (cmd o powershell) e recarsi nella cartella dove è contenuto il codice sorgente di BlueCalendar
- Eseguire il seguente comando per installare il container sulla propria macchina:

  ```
  docker compose up --build
  ```

Dopo aver installato il container, possiamo visualizzare, in locale:
- La webapp sulla porta 80
- Il database sulla porta 3306

**ATTENZIONE! - Il programma prevede l'invio di e-mail, ma in questa versione è stato testato in locale sulla porta 1025 e 8025 usando mailhog**
<br/>
Per fare in modo che la webapp invii effettivamente mail quindi, bisognerà agganciare il programma alla porta SMTP desiderata

Ora il container si trova sulla vostra macchina, e non ci sarà più bisogno di fare la build.

# Fermare l'esecuzione di un container

Per fermare un container eseguire il comando:

  ```
  docker compose stop
  ```
# Riprendere l'esecuzione di un container fermato

Per riprendere l'esecuzione di un container spento, eseguire il comando:

  ```
  docker compose start
  ```
# Funzione watch (per debug)

E' anche possibile eseguire un container in modalità "watch", in modo tale da mostrare subito le modifiche al codice sorgente, senza rifare la build.
<br/>
Per farlo, eseguire il seguente comando all'avvio del container:

  ```
  docker compose up --watch
  ```

# ISTRUZIONI PER L'USO DI BLUECALENDAR

# Primo accesso a BlueCalendar (admin)
<img width="1919" height="856" alt="Screenshot 2026-02-17 115802" src="https://github.com/user-attachments/assets/bd54c2d4-5d04-49c2-943c-bbeb4b665f9a" />
<br/>
<br/>
In questa schermata inserire come utente adminbc e come password adminbc ,
<br/>
Sarà possibile modificare la password dopo il primo accesso, e per farlo basterà premere sul lucchetto in alto a destra.
<br/>
<br/>
<img width="1919" height="868" alt="Screenshot 2026-02-16 155751" src="https://github.com/user-attachments/assets/86057c9e-538f-4366-9241-397e6d81e371" />
<br/>
<br/>
Non è possibile eseguire la registrazione a BlueCalendar, bensì saranno gli admin a creare le utenze per ogni dipendente ed affidargli una password (scelta dall'admin in fase di creazione dell'utente)

# Creazione utente
Per creare un'utente, cliccare su Amministrazione e successivamente "Crea utente"
<br/>
<br/>
<img width="1919" height="869" alt="Screenshot 2026-02-16 163014" src="https://github.com/user-attachments/assets/97441b3b-25e7-4510-ab58-e1ce18d0ede3" />
<br/>
<br/>
Vi ritroverete davanti a questa schermata:
<br/>
<br/>
<img width="1919" height="868" alt="Screenshot 2026-02-16 163411" src="https://github.com/user-attachments/assets/1fa1e4ff-438b-4484-af99-7c8f734c0ee2" />
<br/>
<br/>
Inserire tutti i campi presenti, tenendo conto che:
- Il nome utente viene generato automaticamente seguendo il criterio "prima lettera del nome+cognome", quindi questo crea problemi per dei cognomi con uno spazio, ma si può modificare andando a togliere lo spazio in questione.
- Per gestore si intende il responsabile di quel determinato dipendente. Il gestore sarà colui che approva le richieste di ferie/permessi dei dipendenti a lui assegnati. Inoltre riceve delle mail ogni volta che uno dei dipendenti a lui assegnati manda una richiesta di ferie/permesso. **Per essere gestori, tuttavia, è necessario essere admin**, quindi se voglio inserire un'utente che so che potrà approvare le ferie dei dipendenti, sotto la voce "Ruolo" dovrò selezionare HR admin.
- Per ruolo si intendono i "privilegi" che il nuovo utente avrà sulla piattaforma, gli admin e gli HR admin possono creare utenti, approvare ferie, e molto altro che vedremo successivamente, mentre gli user possono solo mandare richieste di ferie/permessi e visualizzare i calendari che mostrano le ferie dei dipendenti dell'azienda.
- Nella sezione "contratto" inizialmente vedremo solamente "global", ma gli admin potranno successivamente inserire dei vari tipi di contratto, in modo tale che ci sia una panoramica anche sulla tipologia di contratto per ogni dipendente.
- Nella sezione "Entità" inizialmente vedremo solamente "LMS root", ma gli admin potranno successivamente creare l'organigramma dell'azienda, in modo tale da assegnare un utente ad una particolare "branchia" dell'azienda (un esempio potrebbe essere: Bluecube, che al suo interno ha developer, tecnici, commerciali, ecc...)
- Nella sezione "Posizione" inizialmente vedremo solamente "employee", ma gli admin potranno successivamente creare varie posizioni (ad esempio: dipendente, collaboratore partita IVA, ecc...)
- Come matricola interna/aziendale abbiamo pensato a BCXXX (quindi BC001, BC002, e così via...), non viene generata automaticamente alla creazione utente in modo tale da lasciare libertà agli admin sulla loro gestione (anche futura). Un'altro esempio potrebbe essere BCDEV01, BCCOM01, e così via...
<br/>
<br/>
E' possibile modificare un utente cliccando su Amministrazione e successivamente "Elenco utenti"
<br/>
<br/>
<img width="1919" height="871" alt="Screenshot 2026-02-17 121445" src="https://github.com/user-attachments/assets/01b48bee-5380-4f33-980d-ffc6ff933a50" />
<br/>
<br/>
Da qui poi basterà cliccare l'icona della matita di fianco all'utente che vogliamo modificare.
<br/>
Se l'utente è admin, può modificare ogni utente, se l'utente è user può modificare solo il suo utente.
<br/>
<br/>
Andiamo ora a vedere come modificare i vari campi di cui abbiamo parlato sopra.

# Aggiungere un nuovo tipo di contratto
Cliccare su HR e successivamente "Elenco dei contratti"
<br/>
<br/>
<img width="1919" height="870" alt="Screenshot 2026-02-16 165628" src="https://github.com/user-attachments/assets/0d5cb622-f057-4908-b8ef-6eabab53aaab" />
<br/>
<br/>
- Cliccare su "Crea un contratto"
- Compilare tutti i campi e selezionare una tipologia di ferie predefinita, inizialmente vedrete molte scelte in inglese ma anche queste potranno essere modificate dagli admin successivamente.
- Una volta che avete creato i vari tipi di contratto vi ritroverete di fronte ad una schermata simile:
<br/>
<br/>
<img width="1919" height="868" alt="Screenshot 2026-02-18 115859" src="https://github.com/user-attachments/assets/67dad559-3b5c-4bbb-b255-4307b88118bb" />
<br/>
<br/>
Da qui,
<br/>
<br/>

- Cliccare sulla matita nel riquadro per modificare i giorni spettanti per ogni tipo di contratto.

<br/>
<br/>

**ATTENZIONE!**
Tutte le richieste che vengono effettuate senza avere il numero di giorni od ore sufficienti vengono comunque accettate dal sistema, e possono essere accettate dagli admin, ma finiscono nella sezione di reportistica come errore "non spettanti"

<br/>

- Cliccare sull'icona del calendario per impostare le festività per quel tipo di contratto 
 
# Creare nuove tipologie di ferie
Cliccare su Amministrazione e successivamente "Elenco tipologie"

<img width="1919" height="867" alt="Screenshot 2026-02-16 170241" src="https://github.com/user-attachments/assets/e5168df3-c5dd-4665-b3d8-056156bbb0dd" />

Cliccare sulla matita di fianco al nome delle ferie per modificarle con quelle desiderate, ad esempio: Ferie, Permesso, Maternità, 104, ecc..

# Creare l'organigramma
Cliccare su HR e successivamente "Organigramma"
<br/>
<br/>
<img width="1919" height="870" alt="Screenshot 2026-02-17 115938" src="https://github.com/user-attachments/assets/601c5ce5-0066-4adf-9aa4-55cb8f28cda6" />
<br/>
<br/>
Usando il tasto destro su "LMS root" possiamo rinominare la radice dell'organigramma a nostro piacimento
<br/>
<br/>
<img width="1919" height="869" alt="Screenshot 2026-02-17 120349" src="https://github.com/user-attachments/assets/cd5fa68d-52c9-48eb-972d-f141089d921a" />
<br/>
<br/>
E' consigliabile tenere la radice con un nome generico tipo "Admin", di seguito un esempio di organigramma:
<br/>
<br/>
<img width="1919" height="870" alt="Screenshot 2026-02-17 121039" src="https://github.com/user-attachments/assets/77125dd7-25d8-4737-ba1c-f289b286b2c2" />
<br/>
<br/>
Per ogni entità è possibile aggiungere fino a due supervisori, che riceveranno le e-mail in cc riguardanti le richieste di ferie/permessi dei dipendenti che fanno parte di quell'entità.
<br/>
Una mail viene comunque inviata (non in cc, ma come destinatario) al responsabile del dipendente che richiede ferie/permessi.
<br/>
Ogni dipendente può stare solo in una entità.

# Aggiungere una nuova posizione
Cliccare su HR e successivamente "Elenco posizioni"
<br/>
<br/>
<img width="1919" height="867" alt="Screenshot 2026-02-17 122104" src="https://github.com/user-attachments/assets/fd74bfcd-ea15-4aaf-b8a8-1a32bc91b597" />
<br/>
<br/>
Da qui sarà possibile aggiungere nuove posizioni e modificare quelle esistenti.

# Assegnare ad un singolo dipendente dei giorni di ferie/permesso
Possiamo anche assegnare dei giorni di ferie/permesso in più ad un singolo dipendente.
<br/>
<br/>
Cliccare su HR e successivamente "Elenco dipendenti"
<br/>
<br/>
<img width="1919" height="868" alt="Screenshot 2026-02-17 123139" src="https://github.com/user-attachments/assets/d0d7b9d5-67b8-466d-9cf2-416704fed94f" />
<br/>
<br/>
Da qui, usando il tasto destro sul dipendente a cui si vogliono assegnare delle ore, possiamo vedere la schermata sottostante.
Cliccare su "giorni spettanti"
<br/>
<br/>
<img width="1919" height="872" alt="Screenshot 2026-02-17 123600" src="https://github.com/user-attachments/assets/28193ba3-1775-4e08-99fa-c6ebc75005bd" />
<br/>
<br/>
Dalla nuova schermata aperta "Giorni spettanti" cliccare su "Aggiungi" e ci ritroveremo davanti questa schermata:
<br/>
<br/>
<img width="1919" height="866" alt="Screenshot 2026-02-17 123752" src="https://github.com/user-attachments/assets/9c4f311a-b8b6-4a17-b358-10a7768339d4" />
<br/>
<br/>
Da qui:
- Cliccare su "attuale" per inserire l'anno solare attuale
- Selezionare la tipologia di ferie a cui assegnare dei **GIORNI** (**ATTENZIONE! NON ORE**)
- Se lo si desidera, è possibile inserire una descrizione.
<br/>

**E' importante avere delle tipologie di ferie ben strutturate per poter assegnare i giorni spettanti nel modo corretto.**

<br/>
Ad esempio 30 giorni di ferie, 10 di permessi, 90 di maternità, ecc...  
