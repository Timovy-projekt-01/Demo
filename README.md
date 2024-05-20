Inštalačná príručka k webovej aplikácii Security domain ontology browser

Treba mať :
- webový server
- databázu
- Python
- PHP

Z Python balíkov treba mať nainštalované :
-  owlready2
-  mitreattack-python
-  mysql-connector-python

V prípade potreby (ak by nefungovalo uploadovanie .owl súborov) je potrebné si zväčšiť limit na upload v php.ini súbore. 
Treba upraviť 2 premenné (upload_max_filesize , post_max_size).

Postup :
1.	Vytvoriť .env súbor (v koreňovom adresári) podľa .env.example súboru, ktorý sa nachádza v koreňovom priečinku (tie tri čiarky ``` na konci súboru nekopírovať!)
2.	Vytvoriť si databázu s názvom ‚laravel‘ a nastaviť databázové spojenie na základe údajov vyplnených v .env.example file prípadne zmeniť ich podľa potreby
(v prípade, že nemáte nastavené heslo ku databáze vyplňte DB_PASSWORD ako ‘ ““ ‘ )

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1 
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=’””’
```
 
3.	V koreňovom priečinku spustiť cez terminál príkaz `composer install`
4.	V koreňovom priečinku spustiť cez terminál príkaz `npm install`
5.	V koreňovom priečinku spustiť cez terminál príkaz `php artisan key:generate`
6.	V koreňovom priečinku spustiť cez terminál príkaz `php artisan migrate --seed`

Príkazy v krokoch 7, 8 a 9 budú bežať v troch termináloch súčasne:

7.	V koreňovom priečinku spustiť cez terminál príkaz `php artisan serve`
8.	V koreňovom priečinku spustiť cez ďalší terminál príkaz `php artisan bg`
9.	V koreňovom priečinku spustiť cez ďalší terminál príkaz `npm run dev`

Webová aplikácia bude bežať na http://127.0.0.1:8000/ (ak ste si nezmenili DB_HOST v kroku 2)

10.	Aby bolo možné používať webovú aplikáciu na prehliadanie dát, je potrebné nimi naplniť databázu 
- je potrebné sa v záložke ‘Upload’ v navigačnom paneli prihlásiť údajmi poskytnutými nižšie
- kliknúť na plochu pre upload súboru
- vybrať súbor, ktorý sa nachádza od koreňového adresára na ceste  /app/bin/malware/output/malware.owl
pozor: upload bude trvať aj niekoľko minút (ide o veľký súbor)
- keď sa pozadie plochy na upload sfarbí na zeleno a objaví sa tlačidlo pre upload, treba ho stlačiť a počkať
- po úspešnom uploade dát do databázy budete presmerovaní naspäť na podstránku pre upload
- v profile v záložke configs si treba v príslušnom konfiguračnom súbore nastaviť pole searchable na konkrétny prefix, podľa ktorého bude aplikácia vyhľadávať (vzor: http://stufei/ontologies/malware#hasName)
- teraz už je možné vyhľadávať dáta pomocou search baru na domovskej stránke webovej aplikácie

Prihlasovacie údaje do admin systému :

Meno: superAdmin@gmail.com

Heslo: Pa55w0rd
 

