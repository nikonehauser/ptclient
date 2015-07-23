Processes & Events
==================


##Glossar:
VS
> Vergütungs Stufe

Parent
> Meine übergeordnete Person in dessen Werber Hierarchie ich aufgeführt bin.
> Das heißt eben diese Personen bekommen Provision für meine Mitgliedschaft
> Wenn ich VS2 bin habe ich keinen so einen "Parent" mehr.

ADV_LVL1
> Vergütung für das Werben eines Mitglieds wenn ich VS1 bin - derzeit 5$

ADV_LVL2
> Vergütung für das Werben eines Mitglieds wenn ich VS2 bin - derzeit 20$

ADV_INDIRECT
> Vergütun dafür das einer meiner geworbenen Mitglieder ein weiteres
> Mitglied wirbt (Nur für die ersten beiden die er wirbt)
> - derzeit 15$

Werber Hierarchi
> Alle Mitglieder dessen "Parent" ich bin und damit ADV_INDIRECT bekomme wenn
> diese ein Mitglied werben




###E1 - Geldeingang (ausstehende Komponente Schnittstelle zur Bank)
 - (täglich) werden alle Geldeingänge abgerufen und verarbeitet
 - Verarbeitung eines Geldeingangs wie folgt:
   - Verwendungszweck beinhaltet die M-Nummer des entsprechenden Mitglieds
   - Nummer existiert im System -> rufe auf ###P1
   - Nummer existiert nicht -> speichere Geldeingang im System, Benachrichtige entsprechende Personen
    (Prozess noch nicht implementiert)



###P1 - Geldeingang von Mitglied - {@see Member->onReceivedMemberFee()}
- wenn: hat mein "Parent" gezahlt? - ###C1
    nein:
      - speichere die Information das dieses Mitglied bezahlt hat und auf den
        Geldeingang des "Parent" wartet - {@see Member->reserveReceivedMemberFeeEvent(...)}
      - beende Prozess

    ja:
      - bezahle meine "Parent" dafür das er mich geworben hat -> rufe auf ###P2
      - erhöhe geworbene Mitglieder Zähler für mein "Parent"
      - wenn dieser Zähler jetzt 2 entspricht setze "Parent" VS auf 2
        und entferne den derzeitigen "Parent" - dadurch erhält niemand mehr
        ADV_INDIRECT wenn ich weitere Mitglieder werbe
      - rufe auf ###P3




###P2 - Zahle Provisionen für Werbung aus - {@see Member->payAdvertisingFor()}
INFO: Das Mitglied das hier verarbeitet wird (im folgenden "ich", "meinen", "mich")
  ist bereits der "Parent" des Mitglieds dessen
  Zahlungseingang registriert wurde. Im folgenden bezeichnen
  wir "GeworbenesMitglied" als das Mitglied das dessen Zahlungseingang registriert wurde)

 - wenn ich VS1
   - zahl mir ADV_LVL1
   - hole mein "Parent"
   - zahle diesem "Parent" ADV_INDIRECT (dieses Mitglied ist
     dann entsprechend der "Parent""Parent" des "GeworbenesMitglied")

   - setze "Parent" von "GeworbenesMitglied" auf meinen "Parent"
     (das heißt da ich VS1 bin bleibt dieses Mitglied nicht in meiner Werber Hierarchi
     sondern wird meinem "Parent" unterstellt)

 - wenn ich VS2
   - zahl mir ADV_LVL2
   - setze mich als "Parent" von "GeworbenesMitglied" (füge meiner Werber Hierarchi hinzu)

 - bezahle Bonuse an die Personen die zu diesem Mitglied gespeichert sind
   - {@see MemberBonusIds::payBonuses(...)}




###P3 - Verarbeite zuvor zurückgehaltene Zahlungseingänge - {@see Member->fireReservedReceivedMemberFeeEvents()}
 - verarbeite jetzt alle zuvor gespeicherten Geldeingänge, welche
   darauf gewartet haben das dieses Mitglied
   bezahlt.
   Dieser Vorgang löst sofort alle zusammenhängende/verkettete Zahlungseingänge aus.
   Pseudo Code:
   - erzeuge MitgliederContainer
   - füge das zu verarbeitende Mitglied zum MitgliederContainer hinzu (Das Mitglied das gerade bezahlt hat).
   - wiederhole: solange bis MitgliederContainer leer ist
     - nimm erstes Mitglied aus Container
     - Prüfe ob zu verarbeitende Zahlungseingänge vorliegen
     - ja:
       - Verarbeite Zahlungseingänge
       - Füge diese Mitglieder zum MitgliederContainer hinzu
     - nein:
       - gehe zu wiederhole




###C1 - hat mein "Parent" gezahlt?
Durch diese Prüfung kann eine unendliche Hierarchie nach unten entstehn.
Da ich selbst nicht auf bezahlt gesetzt werde solange mein "Parent" das nicht ist
werden alle nachfolgenden Geldeingänge festgehalten und erst verarbeitet sobald
das höchste Glied bezahlt oder gelöscht wird.




###E2 - Ein neues Mitglied hat sich angemeldet
 -> rufe auf ###P4




###P4 - Erstelle Signup Mitglied
 - Speichere Signup Daten
 - Setze "Parent" dieses neuen Mitglieds anhand der Mitglieds Nummer
 - Setze Bonus Personen für dieses Mitglied auf den selben Wert
   wie des "Parent"
 - Wenn "Parent" MitgliedsTyp höher als Mitglied (z.B. Promoter, Orgleiter etc.)
   füge diesen "Parent" zu den BonusPersonen hinzu.




###E3 - Tägliches Script sucht Personen die:
  nicht bezahlt haben und dessen Signup Date < Heute - 2 Wochen

 -> rufe auf ###P5




###P5 - Entferne Mitglied - {@see Member->deleteAndUpdateTree()}
INFO: "ich" ist im folgenden das Mitglied das entfernt wird.

 - Hohle alle Mitglieder von denen ich "Parent" bin. Im folgenden "Kinder".
 - Hohle meinen "Parent"
 - Ersetze mich mit meinem "Parent" für alle noch zu verarbeitende Zahlungseingänge
 - Setze meinen "Parent" also parent für alle meine "Kinder"
 - rufe für meinen "Parent" ###P3 auf um alle ausstehenden Zahlungseingänge
   zu verarbeiten

 - makiere mich als gelöscht