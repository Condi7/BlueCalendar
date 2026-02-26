# BlueCalendar
Un'applicazione PHP il cui scopo principale è la gestione delle ferie, dei permessi e degli straordinari.

# ISTRUZIONI PER L'INSTALLAZIONE E L'USO DI BLUECALENDAR

# Prerequisiti: Installazione di Docker

Dalla pagina web https://www.docker.com/get-started/ selezionare "Download Docker Desktop", come indicato nell'immagine
<br/>
<br/>
<img width="1896" height="867" alt="Screenshot 2026-02-18 110130" src="https://github.com/user-attachments/assets/51b21a78-b4bc-4b41-821e-7f709d64129b" />
<br/>
<br/>
- Seguire le procedure dell'installer.

# 1 - GESTIONE DEL CONTAINER DOCKER

# Creazione del container (una tantum)

- Una volta installato Docker, aprire Docker Desktop, e poi aprire un terminale (cmd o powershell) e recarsi nella cartella dove è contenuto il codice sorgente di BlueCalendar
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

# Fermare l'esecuzione del container

Per fermare un container eseguire il comando:

  ```
  docker compose stop
  ```
# Riprendere l'esecuzione del container fermato

Per riprendere l'esecuzione di un container spento, eseguire il comando:

  ```
  docker compose start
  ```
# Funzione watch (per debug)

E' anche possibile eseguire un container in modalità "watch", in modo tale da mostrare subito sulla webapp le modifiche effettuate al codice sorgente, senza rifare la build.
<br/>
Per farlo, eseguire il seguente comando all'avvio del container:

  ```
  docker compose up --watch
  ```

# 2 - SETUP AMBIENTE APPLICATIVO - riservato agli admin

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

# Creazione delle tipologie di ferie
Cliccare su Amministrazione e successivamente "Elenco tipologie"

<img width="1919" height="867" alt="Screenshot 2026-02-16 170241" src="https://github.com/user-attachments/assets/e5168df3-c5dd-4665-b3d8-056156bbb0dd" />

Cliccare sulla matita di fianco al nome delle ferie per modificarle con quelle desiderate, ad esempio: Ferie, Permesso, Maternità, 104, ecc.. (**ATTENZIONE! - Non sarà possibile modificare la tipologia di ferie con ID 0, in quanto è riservata per la gestione degli straordinari.**)

# Creazione dei contratti, dei giorni spettanti di ferie e delle festività
Cliccare su HR e successivamente "Elenco dei contratti"
<br/>
<br/>
<img width="1919" height="870" alt="Screenshot 2026-02-16 165628" src="https://github.com/user-attachments/assets/0d5cb622-f057-4908-b8ef-6eabab53aaab" />
<br/>
<br/>
- Qui possiamo vedere i tipi di contratti esistenti. Per crearne uno nuovo, cliccare su "Crea un contratto"
- Compilare tutti i campi e selezionare una tipologia di ferie predefinita.
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

**ATTENZIONE!**
Tutte le richieste che vengono effettuate senza avere il numero di giorni od ore sufficienti vengono comunque accettate dal sistema, e possono essere accettate dagli admin, ma finiscono nella sezione di reportistica come errore "non spettanti"

Ed una volta fatto, tornare indietro alla schermata dell'ultima foto e:

- Cliccare sull'icona del calendario per impostare le festività per quel tipo di contratto (**ATTENZIONE! -  Questo passaggio è obbligatorio in quanto influirà sul calcolo delle ore lavorate**)
- Per inserire un nuovo giorno di festivo basterà cliccare sul giorno in questione, e compilare i campi richiesti (consigliato per festività nazionali come Natale, Capodanno, ecc...)
- Per inserire una nuova serie di giorni festivi cliccare su "Serie di giorni festivi" e compilare i campi richiesti (consigliato per i sabati e le domeniche)

# Creazione dell'organigramma
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
Per ogni entità è possibile aggiungere fino a due supervisori, che riceveranno le e-mail in cc riguardanti le richieste di ferie/permessi/straordinari dei dipendenti che fanno parte di quell'entità.
<br/>
Una mail viene comunque inviata (non in cc, ma come destinatario) al responsabile del dipendente che richiede ferie/permessi/straordinari.
<br/>
Ogni dipendente può stare solo in una entità.

# Creazione dei ruoli
Cliccare su HR e successivamente "Elenco ruoli"
<br/>
<br/>
<img width="1919" height="959" alt="Screenshot 2026-02-23 155344" src="https://github.com/user-attachments/assets/273921b1-8999-4ad8-9240-61f34b5a1536" />
<br/>
<br/>
Da qui sarà possibile aggiungere nuovi ruoli e modificare quelli esistenti.

# Creazione di un nuovo utente
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
- Per gestore si intende il responsabile di quel determinato dipendente. Il gestore sarà colui che approva le richieste di ferie/permessi/straordinari dei dipendenti a lui assegnati. Inoltre riceve delle mail ogni volta che uno dei dipendenti a lui assegnati manda una richiesta di ferie/permesso. **Per essere gestori, tuttavia, è necessario essere admin**, quindi se voglio inserire un'utente che so che potrà approvare le ferie dei dipendenti, sotto la voce "Ruolo" dovrò selezionare HR admin.
- Per ruolo si intendono i "privilegi" che il nuovo utente avrà sulla piattaforma, gli admin e gli HR admin possono creare utenti, approvare ferie, e molto altro che vedremo successivamente, mentre gli user possono solo mandare richieste di ferie/permessi/straordinari e visualizzare i calendari che mostrano le ferie dei dipendenti dell'azienda.
- Come matricola interna/aziendale abbiamo pensato a BCXXX (quindi BC001, BC002, e così via...), non viene generata automaticamente alla creazione utente in modo tale da lasciare libertà agli admin sulla loro gestione (anche futura). Un'altro esempio potrebbe essere BCDEV01, BCCOM01, e così via...
<br/>
<br/>
E' possibile modificare un utente cliccando su Amministrazione e successivamente "Elenco utenti"
<br/>
<br/>
<img width="1919" height="871" alt="Screenshot 2026-02-17 121445" src="https://github.com/user-attachments/assets/01b48bee-5380-4f33-980d-ffc6ff933a50" />
<br/>
<br/>
Da qui basterà cliccare l'icona della matita di fianco all'utente che vogliamo modificare.
<br/>
Se l'utente è admin, può modificare ogni utente, se l'utente è user può modificare solo il suo utente.
<br/>
<br/>

# Aggiungere delle ferie ad un utente (al di fuori di quelle spettanti da contratto)
Possiamo anche assegnare dei giorni di ferie/permesso in più ad un singolo dipendente. Questo torna utile quando un dipendente ha maturato più ferie di quante ne 
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
- Selezionare la tipologia di ferie a cui assegnare dei **giorni** (**ATTENZIONE! Non ore**)
- Se lo si desidera, è possibile inserire una descrizione.
<br/>

**E' importante avere delle tipologie di ferie ben strutturate per poter assegnare i giorni spettanti nel modo corretto.**

<br/>
Ad esempio 30 giorni di ferie, 10 di permessi, 90 di maternità, ecc...  

# 3 - TUTORIAL PER GLI ADMIN

# Diagnostica

Cliccando su Amministrazione e successivamente su "Diagnostica"

<img width="1919" height="868" alt="Screenshot 2026-02-23 160136" src="https://github.com/user-attachments/assets/16cc0284-a76e-46f9-8fe6-393191f90dea" />
<br/>
<br/>
Possiamo visualizzare una pagina contenente tutti gli errori all'interno di BlueCalendar, come ad esempio richieste di ferie che sforano il saldo ore, contratti non utilizzati, ecc...)

# Deleghe

Cliccando su Validazione e successivamente su "Deleghe"

<img width="1919" height="870" alt="Screenshot 2026-02-23 160625" src="https://github.com/user-attachments/assets/69dfd9be-58a2-4241-ba34-9397f65c2ce6" />
<br/>
<br/>
Possiamo scegliere un dipendente e delegarlo ad accettare richieste di ferie, permessi e straordinari al posto nostro.

# Montante ore aziendale e riepilogo richieste

Cliccando su HR possiamo selezionare due opzioni di report molto comode:
<br/>
<br/>
<img width="1919" height="871" alt="Screenshot 2026-02-23 161113" src="https://github.com/user-attachments/assets/c51439e3-98ae-4209-9097-fdf192e25ce3" />
<br/>
<br/>
La prima, "Montante ore aziendale", una volta premuto il tasto "Avvia" mostra la lista di tutti i dipendenti (fare attenzione a selezionare l'entità corretta), e il relativo saldo ore per ciascuno di loro, diviso per categoria di ferie.
<br/>
<br/>
<img width="1919" height="871" alt="Screenshot 2026-02-23 163556" src="https://github.com/user-attachments/assets/e23bb03e-d744-439e-b101-4802e2aba392" />
<br/>
<br/>
La seconda, "Riepilogo richieste", funziona allo stesso modo, ma mostra le ore di ferie sfruttate da ogni dipendente, divise per categoria di ferie, nel corso del mese selezionato in alto a sinistra. Inoltre, questa tabella mostra anche le ore di assenza totali e le ore lavorate totali del mese (ricordiamo che per quest'ultima colonna è necessario inserire le festività in tutti i contratti in uso).
<br/>
<br/>
<img width="1919" height="866" alt="Screenshot 2026-02-23 164215" src="https://github.com/user-attachments/assets/d1364ef0-b372-4c67-8d5f-41bca6622254" />

# Validazione ferie/straordinari

Gli admin (ed eventuali loro delegati) devono accettare le richieste di ferie/permessi/straordinari inviate dai dipendenti di cui sono responsabili.
<br/>
Per farlo, cliccare su Validazione (possiamo vedere il numero di richieste ricevute in arancione) e successivamente su "Ferie" (che comprende anche i permessi) o su "Straordinario"
<br/>
<br/>
<img width="1919" height="867" alt="Screenshot 2026-02-23 165413" src="https://github.com/user-attachments/assets/d4940d28-2f57-4430-9fb3-2d267dfec84a" />
<br/>
<br/>
Una volta cliccata la sezione desiderata (nel caso della foto sottostante è "Ferie"), si ritroveranno di fronte a questa schermata:
<br/>
<br/>
<img width="1919" height="869" alt="Screenshot 2026-02-23 165528" src="https://github.com/user-attachments/assets/9db6c467-2b10-4485-a84b-8721dbdc948d" />
<br/>
<br/>
Da qui, coi quattro simbolini sulla sinistra evidenziati, potranno:
- Accettare la richiesta (simbolo della spunta)
- Rifiutare la richiesta (simbolo della X)
- Visualizzare i dettagli della richiesta (simbolo dell'occhio)
- Visualizzare lo storico di quella richiesta (simbolo della cronologia - potranno vedere quando è stata creata, inviata, se ha avuto variazioni di stato, ecc...)

# 4 - TUTORIAL GENERICI

# Le mie richieste e i miei straordinari

- Cliccando su La mia area e successivamente "Le mie richieste" oppure sul simbolo dell'elenco alla destra della scritta "BlueCalendar", sarà possibile vedere le mie richieste di ferie.
- Cliccando invece su La mia area e successivamente "Elenco straordinari", sarà possibile vedere la mia lista di straordinari (anche in attesa di approvazione).
<br/>
<br/>
<img width="1919" height="869" alt="Screenshot 2026-02-23 170400" src="https://github.com/user-attachments/assets/6840c9d8-4838-4ddb-9a0b-7a4b53b0e2a0" />

# Montante ore personale

Cliccando su La mia area e successivamente su "Montante ore personale" accedo ad una schermata che mostra una tabella riassuntiva del mio saldo ore di ferie, ed inoltre mostra anche le ore di ferie usufruite, quelle pianificate e quelle richieste.

# Calendari

In BlueCalendar esistono due tipi di calendari, entambi accessibili cliccando su Calendari: 

- Il calendario personale mostra un calendario annuale molto comodo per vedere le festività e le varie richieste di ferie e permessi (ma non straordinari).
- Il calendario globale mostra un calendario mensile che indica i periodi di ferie/permesso (ma non gli straordinari) accettati o in attesa di approvazione/annullamento di tutti i dipendenti che fanno parte dell'entità selezionata

# Richiesta straordinario

**Premessa sul funzionamento dello straordinario:**
<br/>
Quando un dipendente effettua più ore delle 8 giornaliere, può fare richiesta di straordinario. Queste ore non verranno conteggiate nel totale delle ore lavorate mensili. Tuttavia, se la richiesta di straordinario viene accettata da un admin, il dipendente in questione avrà N ore usufruibili per la richiesta di ferie o permessi, andando a indicare come tipologia nella richiesta la tipologia "Straordinario".

**Come inviare una richiesta di straordinario**
<br/>
Cliccare su La mia area e successivamente "Richiedi straordinario"
<br/>
<br/>
<img width="1919" height="868" alt="Screenshot 2026-02-23 172119" src="https://github.com/user-attachments/assets/26f9b145-9420-44f3-84da-2f5a797e0f31" />
<br/>
<br/>
Compilare i campi richiesti, accertandosi che lo stato sia "Richiesta"

# Richiesta ferie

Cliccare su Nuova richiesta:
<br/>
<br/>
<img width="1919" height="868" alt="Screenshot 2026-02-23 172906" src="https://github.com/user-attachments/assets/85974513-50b6-4329-8f7f-35c2edfe6266" />
<br/>
<br/>
e compilare i campi richiesti.
