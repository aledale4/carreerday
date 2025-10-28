# Career Day

L’iniziativa nasce dall’esigenza di favorire l’incontro tra aziende e studenti prossimi al conseguimento del diploma. L’applicazione deve consentire alle aziende la registrazione autonoma sul sito web dedicato all’iniziativa fornendo, oltre ai dati generali, l’elenco delle posizioni aperte richieste. Inoltre deve consentire agli studenti la registrazione autonoma, fornendo una “lettera di presentazione”, una foto profilo, il CV e link a risorse web (ad es. account GitHub, profili social, ecc.) che possano aiutare a presentare lo studente alle aziende. 

Durante l’evento in presenza le aziende illustreranno ai ragazzi la propria mission e il proprio fabbisogno in termini di competenze. Ogni azienda avrà un box riservato identificato da un QR-code (generato dall’applicazione al termine della registrazione). Gli studenti, dopo aver ascoltato la presentazione dell’azienda, possono decidere di prenotarsi per un colloquio conoscitivo inquadrando il QR-code dell’azienda di interesse. L’applicazione, in questa fase, gestirà l’elenco delle prenotazioni garantendo il rispetto di un numero massimo di prenotazioni per ciascuna azienda.

Dovendo gestire nel tempo diversi eventi “Career day”, anche rivolti ad indirizzi di studio diversi da “Informatica e Telecomunicazioni”, l’applicazione deve consentire agli utenti “admin” la creazione della giornata evento, specificando l’indirizzo di riferimento, la data, l’ora e la location. Pertanto il QR-code sarà riferito alla giornata specifica e consentirà la prenotazione dello studente all’incontro “Career day” specifico per un’azienda specifica. Gli utenti admin devono quindi poter associare le aziende ad ogni giornata “Career day”.
Dopo aver sostenuto il colloquio conoscitivo le aziende potranno accedere al profilo che lo studente ha precedentemente caricato sull’applicazione web. Si osservi che le aziende 
potranno accedere solo ai dati degli studenti che hanno incontrato di persona in sede di colloquio conoscitivo, non potranno quindi accedere autonomamente all’intera popolazione studentesca presente nell’applicazione. Lo studente, prenotando il colloquio con il QR-code in occasione del Career-day, acconsente al trattamento dei suoi dati da parte dell’azienda scelta. Le aziende potranno inserire commenti, note e considerazione sullo colloquio e sullo studente. Dovremmo prevedere una sezione feedback da parte delle aziende sull’evento e possibili proposte.

## Tipologie di utenti

L’applicazione deve gestire tre tipologie di utenti:

users - utenti “amministratore”, devono poter:
resettare le password di accesso per aziende e studenti
visualizzare l’elenco completo delle aziende e degli studenti
creare le giornate Career Day e associarle alle aziende aderenti
visualizzare i feedback ricevuti dalle aziende dopo le giornate Career day

studenti - utente studente della scuola, deve poter:
registrarsi sulla piattaforma
accedere e modificare la propria password
chiedere il reset della password in caso di problemi di login
creare il proprio profilo, come sopra descritto
prenotare il colloquio con l’azienda mediante QR-code

aziende - aderiscono alla piattaforma e devono poter:
registrarsi sulla piattaforma
accedere e modificare la propria password
chiedere il reset della password in caso di problemi di login
creare il proprio profilo, come sopra descritto
vedere l’elenco delle prenotazioni in occasione del Career Day
inserire feedback dell’evento
inserire note specifiche riferite al singolo studente













from PIL import Image
import numpy as np
import potrace

# 1️⃣ Carica il logo
input_path = "logo-careerday.png"
img = Image.open(input_path).convert("L")  # Grayscale

# 2️⃣ Binarizza (0=nero, 1=bianco)
threshold = 200  # regola se serve più o meno dettaglio
bitmap = img.point(lambda x: 0 if x < threshold else 1, '1')
bitmap_array = np.array(bitmap)

# 3️⃣ Crea la bitmap per potrace
bmp = potrace.Bitmap(bitmap_array)
path = bmp.trace()

# 4️⃣ Crea il file SVG con un colore uniforme (arancione #FF6600)
with open("logo-careerday_vector.svg", "w") as f:
    f.write('<?xml version="1.0" standalone="no"?>\n')
    f.write('<svg xmlns="http://www.w3.org/2000/svg" ')
    f.write(f'width="{img.width}" height="{img.height}" viewBox="0 0 {img.width} {img.height}">\n')
    f.write('<path d="')
    
    # Scrivi tutti i tracciati
    for curve in path:
        f.write(f'M {curve.start_point[0]} {curve.start_point[1]} ')
        for segment in curve:
            if segment.is_corner:
                c = segment.c
                f.write(f'L {c[1][0]} {c[1][1]} ')
            else:
                c = segment.c
                f.write(f'C {c[0][0]} {c[0][1]} {c[1][0]} {c[1][1]} {segment.end_point[0]} {segment.end_point[1]} ')
        f.write('Z ')
    
    f.write('" fill="#FF6600" stroke="none"/>\n')
    f.write('</svg>')

print("✅ File vettoriale creato: logo-careerday_vector.svg")
