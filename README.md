# BlueCalendar
Un'applicazione PHP per la gestione delle ferie e dei permessi.

# Istruzioni

# Primo accesso a BlueCalendar (admin)
<img width="1919" height="858" alt="Screenshot 2026-02-16 155539" src="https://github.com/user-attachments/assets/9aa1a10f-5640-49ea-b484-90e290e6d88f" />
<br/>
<br/>
In questa schermata inserire come utente adminbc e come password bbalet ,
<br/>
Sarà possibile modificare la password dopo il primo accesso, e per farlo basterà premere sul lucchetto in alto a destra.
<br/>
<br/>
<img width="1919" height="868" alt="Screenshot 2026-02-16 155751" src="https://github.com/user-attachments/assets/86057c9e-538f-4366-9241-397e6d81e371" />
<br/>
<br/>
Non è possibile eseguire la registrazione a BlueCalendar, bensì saranno gli admin a creare le utenze per ogni dipendente ed affidargli una password (scelta dall'admin in fase di creazione dell'utente)

# Creazione utente
Per creare un'utente, cliccare su Amministrazione e successivamente su Crea utente
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
- Per gestore si intende il responsabile di quel determinato dipendente. Il gestore sarà colui che approva le richieste di ferie/permessi dei dipendenti a lui assegnati. Inoltre riceve delle mail ogni volta che uno dei dipendenti a lui assegnati manda una richiesta di ferie/permesso. **Per essere gestori, tuttavia, è necessario essere admin**, quindi se voglio inserire un'utente che so che potrà approvare le ferie dei dipendenti, dovrò selezionare admin.
- Per ruolo si intendono i "privilegi" che il nuovo utente avrà sulla piattaforma, gli admin e gli HR admin possono creare utenti, approvare ferie, e molto altro che vedremo successivamente, mentre gli user possono solo mandare richieste di ferie/permessi e visualizzare i calendari che mostrano le ferie dei dipendenti dell'azienda.
- Nella sezione "contratto" inizialmente vedremo solamente "global", ma gli admin potranno successivamente inserire dei vari tipi di contratto, in modo tale che ci sia una panoramica anche sulla tipologia di contratto per ogni dipendente.
- Nella sezione "Entità" inizialmente vedremo solamente "LMS root", ma gli admin potranno successivamente creare l'organigramma dell'azienda, in modo tale da assegnare un utente ad una particolare "branchia" dell'azienda (un esempio potrebbe essere: Bluecube, che al suo interno ha developer, tecnici, commerciali, ecc...)
- Nella sezione "Posizione" inizialmente vedremo solamente "employee", ma gli admin potranno successivamente creare varie posizioni (ad esempio: dipendente, collaboratore partita IVA, ecc...)
- Come matricola interna/aziendale abbiamo pensato a BCXXX (quindi BC001, BC002, e così via...), non viene generata automaticamente alla creazione utente in modo tale da lasciare libertà agli admin sulla loro gestione (anche futura). Un'altro esempio potrebbe essere BCDEV01, BCCOM01, e così via...

Andiamo ora a vedere come modificare i vari campi di cui abbiamo parlato sopra.
# Aggiungere un nuovo tipo di contratto
Cliccare su RU e successivamente "Elenco dei contratti"
<br/>
<br/>
<img width="1919" height="870" alt="Screenshot 2026-02-16 165628" src="https://github.com/user-attachments/assets/0d5cb622-f057-4908-b8ef-6eabab53aaab" />
<br/>
<br/>
- Cliccare su "Crea un contatto"
- Compilare tutti i campi e selezionare una tipologia di ferie predefinita, inizialmente vedrete molte scelte in inglese ma anche queste potranno essere modificate dagli admin successivamente.

# Creare nuove tipologie di ferie
Cliccare su Amministrazione e successivamente "Elenco tipologie"

<img width="1919" height="867" alt="Screenshot 2026-02-16 170241" src="https://github.com/user-attachments/assets/e5168df3-c5dd-4665-b3d8-056156bbb0dd" />

Cliccare sulla matita di fianco al nome delle ferie per modificarle con quelle desiderate, ad esempio: Ferie, Permesso, Maternità, 104, ecc..

**WORK IN PROGRESS**
